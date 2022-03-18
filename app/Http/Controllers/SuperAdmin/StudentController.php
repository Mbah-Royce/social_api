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
            $seederFile = $school->studSeederFile()->where('class_room_id', $request->class_id)->first();
            $classRoom = $school->classRooms()->where('id', $request->class_id)->first();
            if ($classRoom) {
                // $students = importCSV($seederFile->path);
                $path = '../database/seeders/students.csv';
                $students = importCSV($path);
                if ($students != false) {
                    foreach ($students as $key => $record) {
                        $isExist  = User::where('email', $record['email'])->first();
                        if (!$isExist) {
                            $randomString = generateRandomString();
                            $user = User::create([
                                'email' => $record['email'],
                                'last_name' => $record['last_name'],
                                'first_name' => $record['first_name'],
                                'gender' => $record['gender'],
                                'password' => 'password',
                                'pwd_changed' => true
                            ]);
                            $user->studentAccounts()->create([
                                'school_id' => $school->id,
                                'class_room_id' => $classRoom->id,
                                'matricule' => generateRandomString(),
                                'level' => '2',
                                'field' => 'SE',
                            ]);
                            $content =  $randomString;
                            // emailNotification($record['email'], 'emails.student.studentPassword', 'Account Creation', $content);
                        } else {
                            $unsuccessfulStudents[$key] = [
                                'email' => $record['email'],
                                'last_name' => $record['last_name'],
                                'first_name' => $record['first_name'],
                                'gender' => $record['gender'],
                                'field' => 'SE',
                            ];
                        }
                    }
                    if ($unsuccessfulStudents) {
                        $data = $unsuccessfulStudents;
                        // emailNotification($school->email, 'emails.student.creationError', 'Account Creation', $unsuccessfulStudents);
                    }
                } else {
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

    public function studentRegister(Request $request)
    {

        $path = '../database/seeders/students.csv';
        if (file_exists($path)) {
        }
        if (!file_exists(public_path($path)) || !is_readable(public_path($path)))
            return false;
        $header = null;
        $data = array();
        if (($handle = fopen($path, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }
}
