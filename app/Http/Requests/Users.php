<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Users extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'name' => 'required',
           'email' => 'required|unique:users,email',
           'password' => 'required|same:password_rep',
           'password_rep' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'password.same' => 'As Senhas informadas não coincidem!'
        ];
    }


}
