<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Users extends FormRequest
{
    private $inputs;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    public function all($keys = null)
    {
       return $this->inputs = parent::all();
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
           'email' =>  (!empty($this->inputs['id']) ? '' : 'required|unique:users,email'),
           'password' => (!empty($this->inputs['password']) ? 'required|same:password_rep' : ''),
           'password_rep' => (!empty($this->inputs['password']) ? 'required' : '')
        ];
    }

    public function messages()
    {
        return [
            'password.same' => 'As Senhas informadas não coincidem!'
        ];
    }


}
