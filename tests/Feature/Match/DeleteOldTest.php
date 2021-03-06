<?php

namespace Tests\Feature\Match;

use App\Events\Match\DeletedOldMatch;
use App\Models\Match;
use App\Models\User;
use App\Notifications\Match\OldMatchDeleted;
use Cache;
use Carbon\Carbon;
use Event;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteOldTest extends TestCase {
	use RefreshDatabase;

	protected $user;
	protected $match;

	/**
	 * @test
	 * @group deleteOld
	 * @group match
	 */
	public function test_doesnt_delete_not_old_matches(): void {
		Event::fake();
		$this->createManagedMatch();
		$this->artisan('match:deleteOld');

		$this->assertEquals(1, Match::count());
		Event::assertNotDispatched(DeletedOldMatch::class);
	}

	/**
	 * @param string $date - the date of the match
	 */
	protected function createManagedMatch(string $date = 'today'): void {
		$this->user = factory(User::class)->create();
		$this->match = factory(Match::class)->create([
			'date_time' => new Carbon($date),
		]);
		$this->match->addManager($this->user);
	}

	/**
	 * @test
	 * @group deleteOld
	 * @group match
	 */
	public function test_deletes_old_matches(): void {
		Event::fake();

		$this->createManagedMatch('8 days ago');

		$this->artisan('match:deleteOld');
		$this->assertEquals(0, Match::count());

		Event::assertDispatched(DeletedOldMatch::class);
	}

	/**
	 * @test
	 * @group deleteOld
	 * @group match
	 */
	public function test_handles_match_deleted(): void {
		Notification::fake();
		$this->createManagedMatch('8 days ago');
		$this->match->addPlayer($player = factory(User::class)->create());

		Cache::shouldReceive('tags')->once()->with("{$this->user->username}_managed")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');
		Cache::shouldReceive('forget')->once()->with(sha1("{$this->user->username}_hasManagedMatches"));

		Cache::shouldReceive('forget')->once()->with(sha1("{$player->username}_nextMatch"));
		Cache::shouldReceive('tags')->once()->with("{$player->username}_played")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');
		Cache::shouldReceive('tags')->once()->with("admin_matches")
			->andReturn(\Mockery::self())->getMock()->shouldReceive('flush');
		Cache::shouldReceive('forget')->once()->with(sha1("match_{$this->match->id}"));


		$this->artisan('match:deleteOld');
		$this->assertEquals(0, Match::count());

		Notification::assertSentTo($this->user, OldMatchDeleted::class);
	}

}
