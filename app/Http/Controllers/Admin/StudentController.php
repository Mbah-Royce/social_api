<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentCreationRequest;
use App\Http\Requests\StudentFormCreationRequest;
use App\Http\Requests\StudentUpateRequest;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Student resource listing
     * 
     * @param $request
     * @param $schoolId
     */
    public function index($schoolId, $classRoomId){
        $data = [];
        $statusCode = 200;
        $message = __("Uploaded successfully");
        $classRoom = School::find($schoolId)->classRooms()->where('id', $classRoomId)->first();
        $data = $classRoom->students()->paginate();
        return httpResponse($data, $message, $statusCode);
    }

    /**
     * Student resource creation
     * 
     * @param $request
     * @param $schoolId
     */
    public function create(StudentCreationRequest $request, $shcoolId, $classRoomId){
        try {
            $data = [];
            $message = __("Uploaded successfully");
            $statusCode = 200;
            $school = School::findorfail($shcoolId);
            $classRoom = $school->classRooms()->where('id',$classRoomId)->first();
            $path = fileUpload("/public/seed", $request->student_seeder_file);
            if($classRoom){
                $data = $school->studSeederFile()->create([
                    'path' => $path,
                    'class_room_id' => $classRoomId,
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

    /**
     * Student resource creation
     * 
     * @param $request
     * @param $schoolId
     */
    public function formCreate(StudentFormCreationRequest $request, $schoolId, $classRoomId){
        $data = [];
        $statusCode = 201;
        $message = __("Uploaded successfully");
        $isClassRoom = ClassRoom::where(['id' => $request->class_room_id,'school_id' => $request->school_id])->first();
        if(!$isClassRoom){
            $user = User::create([
                'first_name'=> $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => generateRandomString(),
                'phone' => $request->phone,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'profile_picture' => $request->profile_picture,
                'cover_picture' => $request->cover_picture,
            ]);
            $user->studentAccounts()->create([
                'school_id' => $schoolId,
                'class_room_id'=> $classRoomId,
                'level'=> $request->level,
                'field'=> $request->field,
                'matricule' => $request->matricule,
            ]);
            emailNotification($request->email, 'emails.student.studentPassword', 'Account Creation', $user->password);
        }else{
            $statusCode = 404;
            $message = __("Class not found");
        }
        return httpResponse($data, $message, $statusCode);
    }

    /**
     * Student resource show
     * 
     * @param $request
     * @param $schoolId
     */
    public function show($schoolId, $classRoomId, $studentId){
        $data = [];
        $statusCode = 200;
        $message = __("Student successfully");
        $classRoom = School::find($schoolId)->classRooms()->where('id', $classRoomId)->first();
        $student = $classRoom->students()->where('id',$studentId)->first();
        $userInfo = $student->user()->first();
        $data = [
            'student_info' => $student,
            'user_info' => $userInfo
        ]; 
        return httpResponse($data, $message, $statusCode); 
    }

    /**
     * Student resource show
     * 
     * @param $request
     * @param $schoolId
     */
    public function update(StudentUpateRequest $request, $schoolId, $classRoomId, $studentId){
        $request->merge(['student_id']);
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);
        $data = [];
        $statusCode = 200;
        $message = __("Student updated successfully");
        $student = School::find($schoolId)->classRooms()->where('id', $classRoomId)->students()->where('id',$studentId)->first();
        $student->update([
            'level'=> $request->level,
            'field'=> $request->field,
            'matricule' => $request->matricule,
            'field' => $request->field,
            'status' => $request->status
        ]);
        return httpResponse($data, $message, $statusCode); 
    }
    /**
     * Delete student resource
     * 
     * @param $request
     * @param $schoolId
     */
    public function destroy($schoolId, $classRoomId, $studentId){
        $data = [];
        $statusCode = 200;
        $message = __("Student deleted successfully");
        $classRoom = School::find($schoolId)->classRooms()->where('id', $classRoomId)->first();
        $isStudent = $classRoom->students()->where('id',$studentId)->first();
        if ($isStudent) $isStudent->delete();
        else $message = __("Student not found");
        return httpResponse($data, $message, $statusCode); 
    }
}
