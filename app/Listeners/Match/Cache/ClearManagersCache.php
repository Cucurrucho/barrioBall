<?php

namespace App\Listeners\Match\Cache;

use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearManagersCache {
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct() {
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  object $event
	 *
	 * @return void
	 */
	public function handle($event): void {
		Cache::forget(sha1("{$event->match->id}_managers"));
	}
}
