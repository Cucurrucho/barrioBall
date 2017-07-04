<?php

namespace App\Http\Controllers;




use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUsernameRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{



    public function edit(Request $request)
    {
        $user = $request->user();
        return view('user.profile', compact('user'));
    }



    public function updateUsername(UpdateUsernameRequest $request)
    {
        $user = $request->commit();
        $message =  __('profile/update.updatedUsername');
        return back()->with('alert', $message);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {


        $user = $request->commit();
        $message = __('profile/update.updatedPassword');
        return back()->with('alert', $message);
    }

    public function updateEmail(UpdateEmailRequest $request)
    {

        $user = $request->commit();
        $message = __('profile/update.updatedEmail');
        return back()->with('alert', $message);
    }

    public function updateLanguage(UpdateLanguageRequest $request)
    {

        $user = $request->commit();
        $message = __('profile/update.updatedLanguage');
        return back()->with('alert', $message);
    }

    public function destroy(DeleteUserRequest $request)
    {
        //
        $user = $request->commit();


        return redirect('/home');
    }

//    public function getMatches(Request $request)
//    {
//        $matches = $request->user()->matches()->withPivot('role');
//
//        if ($request->has('sort')) {
//            $matches = $matches->orderBy($request->input('sort'));
//        } else {
//            $matches = $matches->latest();
//        }
//
//        if ($request->has('filter')) {
//            $filterVal = "%{$request->input('filter')}%";
//            $matches->where(function ($query) use ($filterVal) {
//                $query->where('name', 'like', $filterVal);
//            })->orWherePivot('role', 'like', $filterVal);
//        }
//
//        return $matches->paginate($request->input('per_page'));
//    }
}
