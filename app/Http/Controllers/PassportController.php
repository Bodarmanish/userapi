<?php
 
namespace App\Http\Controllers;
 
use App\User;
use Illuminate\Auth\attempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use auth;
 
class PassportController extends Controller
{
    
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
 		
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
 
        $token = $user->createToken('TutsForWeb')->accessToken;
 
        return response()->json(['token' => $token], 200);
    }
 
   
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
 
        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('TutsForWeb')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }

    public function changePassword(Request $request)
    {

        $data = $request->all();
        $user = auth()->user();
        
         //Changing the password only if is different of null
        if( isset($data['oldPassword']) && !empty($data['oldPassword']) && $data['oldPassword'] !== "" && $data['oldPassword'] !=='undefined') 
        {
         //checking the old password first
        
            if(Hash::check($data['oldPassword'], $user->password))
            {
                 if(isset($data['newPassword']) && !empty($data['newPassword']) && $data['newPassword'] !== "" && $data['newPassword'] !=='undefined') {
                     $user->password = bcrypt($data['newPassword']);
                     //$user->isFirstTime = false; //variable created by me to know if is the dummy password or generated by user.
                     
                     $user->token()->revoke();
                     $token = $user->createToken('newToken')->accessToken;
                    
                     //Changing the type
                     $user->save();

                    return response()->json(['token' => $token], 200);//sending the new token
                 }
                 else 
                 {
                     return "Wrong password information";
                 }
            }
            else 
            {
                   return "Wrong oldpassword information";
            }

        }
     return "Wrong password information";
    }
 
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
}