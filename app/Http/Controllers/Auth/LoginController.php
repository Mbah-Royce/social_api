<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * 
     *Login user
     * 
     * @param $request
     * @return array|json
     */
    public function Login(Request $request){
        $request->validate([
            'password' => 'string',
            'email' => 'string|email'
        ]);
       if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
        $user = User::where('email', $request->email)->first();
        if($user->pwd_changed){
            Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;
            $message = 'User login successfully';
            $statusCode = 200;
            $data = [
                'user'=> $user,
                'token' => $token,
                'student_accounts' => $user->studentAccounts
            ];
        }else{
            $statusCode = 200;
            $message = "Successful Please change password";
            $data = [
                'user'=> $user,
            ];
        }
        
       } else{
        $statusCode = 403;
        $message = "Passowrd or Email incorrect";
        $data = [];
       }
       return httpResponse($data, $message, $statusCode);
    }
}
