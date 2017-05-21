<?php

namespace App\Http\Controllers\Match;

use App\Http\Requests\Match\InviteMangersRequest;
use App\Http\Requests\Match\JoinMatchRequest;
use App\Http\Requests\Match\leaveMatchRequest;
use App\Models\Match;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MatchUsersController extends Controller
{
	public function joinMatch(JoinMatchRequest $request, Match $match){
		$message = $request->commit();
		return back()->with('alert', $message);
	}

	public function leaveMatch(leaveMatchRequest $request, Match $match){
		$request->commit();
		return back()->with('alert', 'You left the match');
	}

	public function searchUsers(Request $request){
		return User::select('id','username')->where('username','LIKE',"%{$request->get('query')}%")->get();
	}

	public function inviteManagers(InviteMangersRequest $request, Match $match){
		$request->commit();
		return back()->with('alert', 'Invitation Sent');
	}
}
