<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OtpController extends Controller
{
    /**
     * Send OTP Code to email
     * 
     * @param email
     * @return array|json
     */
    public function sendOtpCode(Request $request){
        $request->validate([
            'email' => 'required|string|email|exists:users,email'
        ]);
        $code = generateRandomNum(5);
        $template = 'emails.changeDefaultPassword';
        $subject = 'Change Default Password';
        $queue = 'otpcode';
        $seconds = 6000;
        if (Cache::has($request->email)) {
            Cache::forget($request->email);
        }
        Cache::put($request->email, $code, $seconds);
        emailNotification($request->email,$template,$subject,$code,$queue);
        $message = "Code Send Successfully";
        $statusCode = 200;
        $data = [];
        return httpResponse($data,$message,$statusCode);
    }

    /**
     * Verify OTP Code to 
     * 
     * @param email
     * @return array|json
     */
    public function verifyOtpCode(Request $request){
        $request->validate([
            'code' => 'required|string',
            'email' => 'required|string|email'
        ]);
        $data = [];
        $statusCode = 200;
        $code = $request->code;
        $email = $request->email;
        if (Cache::has($email)) {
            $storedOtp = Cache::get($email);
            if ($code == $storedOtp){
                $user = User::where('email',$email)->first();
                $token = $user->createToken('pass_reset_token', ['server:reset_password'])->plainTextToken;
                $data = [
                    'user'=> $user,
                    'token' => $token,
                ];
                $message = "Successfull";
                Cache::forget($email);
            }else{
                $statusCode = 422;
                $message = "Otp Wrong";
            }
            
        }else{
            $statusCode = 422;
            $message = "Invalid Request";
        }
        return httpResponse($data,$message,$statusCode);
    }
}
