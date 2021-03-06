<?php

namespace App\Models;

use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Match extends Model {

	protected $appends = [
		'url',
		'date',
		'time',
	];
	protected $dates = [
		'created_at',
		'updated_at',
		'date_time',
	];

	/**
	 * @return string
	 */
	public function getDateAttribute(): string {
		return $this->date_time->format('d/m/y');
	}


	/**
	 * @return string
	 */
	public function getTimeAttribute(): string {
		return $this->date_time->format('H:i');
	}

	/**
	 * @param User $user
	 */
	public function addManager(User $user): void {
		$this->addUser($user, true);
	}

	/**
	 * @param User $user
	 * @param bool $manager
	 */
	public function addUser(User $user, bool $manager = false): void {
		$this->users()->attach($user, [
			'role' => $manager ? 'manager' : 'player',
		]);
	}

	/**
	 * @return BelongsToMany
	 */
	public function users(): BelongsToMany {
		return $this->belongsToMany(User::class)
			->withPivot('role');
	}

	/**
	 * @param User $user
	 */
	public function removeManager(User $user): void {
		$this->managers()->detach($user);
	}

	/**
	 * @return BelongsToMany
	 */
	public function managers(): BelongsToMany {
		return $this->belongsToMany(User::class)
			->wherePivot('role', 'manager');
	}

	/**
	 * @param User $user
	 */
	public function addPlayer(User $user): void {
		$this->addUser($user, false);
	}

	/**
	 * @param User $user
	 */
	public function removePlayer(User $user): void {
		$this->registeredPlayers()->detach($user);
	}

	/**
	 * @return BelongsToMany
	 */
	public function registeredPlayers(): BelongsToMany {
		return $this->belongsToMany(User::class)
			->wherePivot('role', 'player');
	}

	/**
	 * @return string
	 */
	public function getUrlAttribute(): string {
		return action('Match\MatchController@showMatch', $this);
	}

	/**
	 * @param User $user
	 *
	 * @return bool
	 */
	public function hasJoinRequest(User $user): bool {
		return $this->joinRequests()->where('id', $user->id)->exists();
	}

	/**
	 * @return BelongsToMany
	 */
	public function joinRequests(): BelongsToMany {
		return $this->belongsToMany(User::class, 'join_match_requests')
			->withTimestamps();
	}

	/**
	 * @param User $user
	 *
	 * @return bool
	 */
	public function hasPlayer(User $user): bool {
		return $this->registeredPlayers()->where('id', $user->id)->exists();
	}

	/**
	 * @param User $user
	 *
	 * @return bool
	 */
	public function hasManager(User $user): bool {
		return $this->managers()->where('id', $user->id)->exists();
	}

	/**
	 * @param User $user
	 */
	public function addJoinRequest(User $user): void {
		$this->joinRequests()->save($user);
	}

	/**
	 * @param User $user
	 */
	public function cancelJoinRequest(User $user): void {
		$this->joinRequests()->detach($user);
	}

	/**
	 * @return bool
	 */
	public function isFull(): bool {
		return Cache::rememberForever(sha1("{$this->id}_isFull"), function () {
			return $this->players != 0 && $this->registeredPlayers()->count() >= $this->players;
		});
	}


	/**
	 * @return bool
	 */
	public function ended(): bool {
		return Carbon::now() > $this->date_time;
	}

	/**
	 * @param User $user
	 */
	public function inviteManager(User $user): void {
		$this->managerInvites()->save($user);
	}

	/**
	 * @return BelongsToMany
	 */
	public function managerInvites(): BelongsToMany {
		return $this->belongsToMany(User::class, 'manager_invites')
			->withTimestamps();
	}

	/**
	 * @param User $user
	 */
	public function removeManageInvitation(User $user): void {
		$this->managerInvites()->detach($user);
	}

	/**
	 * @param User $user
	 *
	 * @return bool
	 */
	public function hasManagerInvite(User $user): bool {
		return $this->managerInvites()->where('id', $user->id)->exists();
	}
}
