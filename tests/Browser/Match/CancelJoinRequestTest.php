<?php

namespace Tests\Browser\Match;

use App\Models\Match;
use App\Models\User;
use Tests\Browser\Pages\Match\ShowPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CancelJoinRequestTest extends DuskTestCase {
	use DatabaseMigrations;

	protected $match;
	protected $player;
	protected $manager;


	public function setUp() {
		parent::setUp();
		$this->match = factory(Match::class)->create();
		$this->player = factory(User::class)->create();
	}

	/**
	 * @test
	 * @group cancelJoinRequest
	 * @group match
	 */
	public function test_cant_see_cancel_button_before_sending_request(): void {
		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->player)
				->visit(new ShowPage($this->match))
				->assertDontSee(__('match/show.cancelJoinRequest', [], $this->player->language));
		});
	}

	/**
	 * @test
	 * @group cancelJoinRequest
	 * @group match
	 * @group current
	 */
	public function test_cancel_join_request(): void {
		$this->match->addJoinRequest($this->player);
		$this->browse(function (Browser $browser) {
			$browser->loginAs($this->player)
				->visit(new ShowPage($this->match))
				->click('@dropdown-button')
				->assertSee(__('match/show.cancelJoinRequest', [], $this->player->language))
				->click('@cancel-join-button')
				->assertSee(__('match/show.cancelMessage', [], $this->player->language));
		});

		$this->assertFalse($this->match->hasJoinRequest($this->player));
	}
}
