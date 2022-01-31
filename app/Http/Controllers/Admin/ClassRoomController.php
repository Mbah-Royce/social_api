<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\School;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    /**
     * 
     * List ClassRoom resource
     * 
     * @param $id
     * @return array|json
     */
    public function index($id){
        $data = [];
        $statusCode = 200;
        try {
            $school = School::findorfail($id);
            $data = $school->classRooms->paginate();
            $message = __("response_message.Class_Room_Listing",["schoolName" => $school->name]);
        } catch (ModelNotFoundException $expection) {
            $data = [];
            $message = __("response_message.School_Not_Found");
            $statusCode = 404;
        } catch(Exception $expection){
            $data = [];
            $message = $expection->getMessage();
            $statusCode = 500;
        }
        return httpResponse($data, $message, $statusCode);
    }
    
    /**
     * 
     * Create ClassRoom resource
     * 
     * @param $request
     * @param $schoolId
     * @return array|json
     */
    public function create(Request $request, $schoolId){
        $request->validate([
            'name' => 'required|string'
        ]);
        $data = [];
        $message = __("response_message.Successful_Class_Creation");
        $statusCode = 201;
        try {
            $school = School::findorfail($schoolId);
            $data = $school->classRooms()->create($request->all());
        } catch (ModelNotFoundException $expection) {
            $data = [];
            $message = __("response_message.School_Not_Found");
            $statusCode = '404';
        } catch (Exception $expection) {
            $data = [];
            $message = $expection->getMessage();
            $statusCode = 500;
        }
        return httpResponse($data, $message, $statusCode);
    }

    /**
     * 
     * Show ClassRoom resource
     * 
     * @param $id
     * @return array|json
     */
    public function show($id){
        $data = [];
        $statusCode = 200;
        $message = __("response_message.Class_Room_Show");
        try {
            $data = ClassRoom::findorfail($id);
        } catch (ModelNotFoundException $expection) {
            $data = [];
            $message = __("response_message.Class_Not_Found");
            $statusCode = 404;
        } catch(Exception $expection){
            $data = [];
            $message = $expection->getMessage();
            $statusCode = 500;
        }
        return httpResponse($data, $message, $statusCode);
    }

    /**
     * 
     * Delete ClassRoom 
     * 
     * @param $id
     * @return array|json
     */
    public function destroy($id){
        $data = [];
        $statusCode = 200;
        try {
            $classRoom = ClassRoom::findorfail($id);
            $message = __("response_message.Class_Room_Delete",["className" => $classRoom->name]);
        } catch (ModelNotFoundException $expection) {
            $data = [];
            $message = __("response_message.Class_Not_Found");
            $statusCode = 404;
        } catch(Exception $expection){
            $data = [];
            $message = $expection->getMessage();
            $statusCode = 500;
        }
        return httpResponse($data, $message, $statusCode);  
    }
}
