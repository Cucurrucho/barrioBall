<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{


    protected function validatorUsername(array $data)
    {
        return Validator::make($data, [

            'username' => 'required|max:255|unique:users'
        ]);
    }

    protected function validatorEmail(array $data)
    {
        return Validator::make($data, [
       'email' => 'required|email|max:255|unique:users'

        ]);
    }

    protected function validatorPassword(array $data)
    {
        return Validator::make($data, [
            'password' => 'required|min:6|confirmed'

        ]);
    }

    protected function validatorLanguage(array $data)
    {
        return Validator::make($data, [

            'language' => 'required|in:en,es'
        ]);
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        return view('user.profile', compact('user'));
    }



//    public function update(Request $request)
//    {
//        //
//
//        $user = $request->user();
//        $password = bcrypt($request['password']);;
//
//        $user->username= $request->get('username');
//        $user->password = $password;
//        $user->save();
//
//        return back();
//    }

    public function updateUsername(Request $request)
    {
        $this->validatorUsername($request->all())->validate();
        $user = $request->user();
        $user->username = $request->get('username');
        $user->save();
        $message =  __('profile/update.updatedUsername');
        return back()->with('alert', $message);
    }

    public function updatePassword(Request $request)
    {

        $this->validatorPassword($request->all())->validate();
        $user = $request->user();
        $user->password = bcrypt($request->get('password'));
        $user->save();
        $message = __('profile/update.updatedPassword');
        return back()->with('alert', $message);
    }

    public function updateEmail(Request $request)
    {

        $this->validatorEmail($request->all())->validate();
        $user = $request->user();
        $user->email = $request->get('email');
        $user->save();
        $message = __('profile/update.updatedEmail');
        return back()->with('alert', $message);
    }

    public function updateLanguage(Request $request)
    {

        $this->validatorLanguage($request->all())->validate();
        $user = $request->user();
        $user->language = $request->get('language');
        $user->save();
        $message = __('profile/update.updatedLanguage');
        return back()->with('alert', $message);
    }

    public function destroy()
    {
        //
        $user = Auth::user();
        $user->delete();

        return redirect('/home');
    }

    public function getMatches(Request $request)
    {
        $matches = $request->user()->matches()->withPivot('role');

        if ($request->has('sort')) {
            $matches = $matches->orderBy($request->input('sort'));
        } else {
            $matches = $matches->latest();
        }

        if ($request->has('filter')) {
            $filterVal = "%{$request->input('filter')}%";
            $matches->where(function ($query) use ($filterVal) {
                $query->where('name', 'like', $filterVal);
            })->orWherePivot('role', 'like', $filterVal);
        }

        return $matches->paginate($request->input('per_page'));
    }
}
