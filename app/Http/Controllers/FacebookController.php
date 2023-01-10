<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class FacebookController extends Controller
{
    public function loginUsingFacebook()
 {
    return Socialite::driver('facebook')->redirect();
 }
    public function callbackFromFacebook()
 {
  try {
       $user = Socialite::driver('facebook')->user();

       $saveUser = User::updateOrCreate([
           'facebook_id' => $user->getId(),
       ],[
           'name' => $user->getName(),
           'email' => $user->getEmail(),
           'password' => Hash::make($user->getName().'@'.$user->getId())
            ]);

       Auth::loginUsingId($saveUser->id);

       return redirect()->route('home');
       } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
