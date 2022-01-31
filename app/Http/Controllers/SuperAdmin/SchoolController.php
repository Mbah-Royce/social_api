<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolRegistrationRequest;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * 
     * List School Resource
     * 
     * @return array|json
     */
    public function index(){
        $data = [];
        $message = __("response_message.Successful_School_Listing");
        $statusCode = 200;
        $data = School::paginate();
        return httpResponse($data, $message, $statusCode);
    }
    
    /**
     * 
     * Create School Resource
     * 
     * @param $request
     * @return array|json
     */
    public function create(Request $request){
        $data = [];
        $message = __("response_message.Successful_School_Registration");
        $statusCode = 201;
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => 'password'
        ]);
        $school = School::create([
            'name' => $request->school_name,
            'email' => $request->school_email,
            'password' => 'password'
        ]);
        $school->user()->attach($user->id);
        $data = [
            'user' => $user,
            'school' => $school
        ];
        return httpResponse($data, $message, $statusCode);
    }
}
