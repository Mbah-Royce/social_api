<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
            'email' => 'required|string|email'
        ]);
        $code = generateRandomString();
        $template = 'emails.changeDefaultPassword';
        $subject = 'Change Default Password';
        $queue = 'otpcode';
        $seconds = 3000;
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
                $message = "Successfull";
            }else{
                $statusCode = 422;
                $message = "Otp Wrong";
            }
            Cache::forget($email);
        }else{
            $statusCode = 422;
            $message = "Invalid Request";
        }
        return httpResponse($data,$message,$statusCode);
    }
}
