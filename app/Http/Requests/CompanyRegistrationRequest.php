<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRegistrationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'companyName' => 'required|string',
            'email' => 'required|email|unique:auth',
            'password' => 'required|min:8',
            'type_id' => 'required|exists:types,id',
        ];
    }
}
