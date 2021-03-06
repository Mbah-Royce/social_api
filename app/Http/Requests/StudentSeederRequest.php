<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentSeederRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
    * Indicates if the validator should stop on the first rule failure.
    *
    * @var bool
    */
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'class_id' => ['required','exists:class_rooms,id'],
            'student_file' => ['required','string'],
        ];
    }
}
