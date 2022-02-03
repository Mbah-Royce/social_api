<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentFormCreationRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'matricule' => 'required|unique:students',
            'email' => 'required|unique:users,email',
            'level' => 'required',
            'field' => 'nullable',
            'current_address' => 'nullable',
            'account_status'=> 'nullable',
            'status'=> 'nullable',
        ];
    }
}
