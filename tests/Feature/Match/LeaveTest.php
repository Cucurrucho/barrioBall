<?php

namespace Tests\Feature\Match;

use App\Events\Match\UserLeft;
use App\Models\Match;
use App\Models\User;
use Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaveTest extends TestCase {
	use RefreshDatabase;

	protected $match;
	protected $manager;


	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create();
		$this->manager = factory(User::class)->create();

		$this->match->addManager($this->manager);
	}

	/**
	 * @test
	 * @group Match
	 * @group leaveMatch
	 */
	public function test_player_can_leave_match(): void {
		Event::fake();
		$this->match->addPlayer($this->manager);

		$this->actingAs($this->manager)->delete(action('Match\MatchUsersController@leaveMatch', $this->match))
			->assertSessionHas('alert', __('match/show.left'));

		$this->assertFalse($this->manager->inMatch($this->match));
		Event::assertDispatched(UserLeft::class, function ($event) {
			return $event->user->id == $this->manager->id;
		});

	}

	/**
	 * @test
	 * @group Match
	 * @group leaveMatch
	 */
	public function test_unjoined_cant_leave_match(): void {
		Event::fake();

		$this->actingAs($this->manager)
			->delete(action('Match\MatchUsersController@leaveMatch', $this->match))
			->assertStatus(403);
		Event::assertNotDispatched(UserLeft::class, function ($event) {
			return $event->user->id == $this->manager->id;
		});
	}


	/**
	 * @test
	 * @group Match
	 * @group leaveMatch
	 */
	public function test_not_logged_cant_leave_match(): void {
		Event::fake();
		$this->delete(action('Match\MatchUsersController@leaveMatch', $this->match))
			->assertRedirect(action('Auth\LoginController@showLoginForm'));

		Event::assertNotDispatched(UserLeft::class, function ($event) {
			return $event->user->id == $this->manager->id;
		});
	}


}
