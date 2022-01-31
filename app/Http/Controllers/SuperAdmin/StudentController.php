<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Method to create resource students Account
     * 
     * @param @request
     * @param @schoolId
     */
    public function create(Request $request, $shcoolId)
    {
        try {
            $data = [];
            $message = "students created successfully";
            $statusCode = 201;
            $school = School::findorfail($shcoolId);
            $unsuccessfulStudents = [];
            $seederFile = $school->studSeederFile()->where('class_room_id',$request->class_id)->first();
            $classRoom = $school->classRooms()->where('id', $request->class_id)->first();
            if ($classRoom) {
                $students = importCSV($seederFile->path);
                if($students != false){
                    foreach ($students as $key => $record) {
                        $isExist  = User::where('email', $record['email'])->first();
                        if (!$isExist) {
                            $randomString = generateRandomString();
                            $user = User::create([
                                'email' => $record['email'],
                                'last_name' => $record['last_name'],
                                'first_name' => $record['first_name'],
                                'gender' => $record['gender'],
                                'password' => $randomString
                            ]);
                            $user->studentAccounts()->create([
                                'school_id' => $school->id,
                                'class_room_id' => $classRoom->id,
                                'level' => '2',
                                'field' => 'SE',
                            ]);
                            $content = [
                                'passowrd' => $randomString
                            ];
                            // emailNotification($record['email'], '', 'Account Creation', $content);
                        } else {
                            $unsuccessfulStudents[$key] = [
                                'email' => $record['email'],
                                'last_name' => $record['last_name'],
                                'first_name' => $record['first_name'],
                                'gender' => $record['gender'],
                                'field' => $record['field'],
                            ];
                        }
                    }
                    if ($unsuccessfulStudents) {
                        $data = $unsuccessfulStudents;
                        // emailNotification($shcool->email, '', 'Account Creation', $unsuccessfulStudents);
                    }
                } else{
                    $message = __("response_message.File_Not_Found");
                    $statusCode = 422; 
                }
                
            } else {
                $message = __("response_message.ClassRoom_Not_BelongTo_School");
                $statusCode = 403;
            }
        } catch (ModelNotFoundException $exception) {
            $data = [];
            $message = __("response_message.School_Not_Found");
            $statusCode = 404;
        } catch (Exception $expection) {
            $data = [];
            $message = $expection->getMessage();
            $statusCode = 500;
        }
        return httpResponse($data, $message, $statusCode);
    }
}
