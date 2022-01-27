<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolRegistrationRequest;
use App\Models\School;
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
    public function create(SchoolRegistrationRequest $request){
        $data = [];
        $message = __("response_message.Successful_School_Registration");
        $statusCode = 201;
        School::create([$request->all()]);
        httpResponse($data, $message, $statusCode);
    }
}
