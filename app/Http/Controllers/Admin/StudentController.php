<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentCreationRequest;
use App\Models\School;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function create(StudentCreationRequest $request, $shcoolId){
        try {
            $data = [];
            $message = __("Uploaded successfully");
            $statusCode = 200;
            $school = School::findorfail($shcoolId);
            $classRoom = $school->classRooms()->where('id',$request->class_id)->first();
            $path = fileUpload("/public/seed", $request->student_seeder_file);
            if($classRoom){
                $data = $school->studSeederFile()->create([
                    'path' => $path,
                    'class_room_id' => $classRoom->id,
                ]);
            }
        } catch (ModelNotFoundException $exception) {
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
}
