<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use auth;

class SocialAuthGoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }


    public function callback()
    {
        try {
            
        
            $googleUser = Socialite::driver('google')->user();
            
            $existUser = User::where('email',$googleUser->email)->first();
            

            if($existUser) {
                auth::loginUsingId($existUser->id);
            }
            else 
            {
            	
                $user = new User;
                $user->name = $googleUser->name;
                $user->email = $googleUser->email;
                $user->google_id = $googleUser->email;
                $user->password = md5(rand(1,10000));
                $user->save();
                auth::loginUsingId($user->id);
            }
            return redirect()->to('/home');
       } 
        catch (Exception $e) {
            return 'error';
        }
    }
}
