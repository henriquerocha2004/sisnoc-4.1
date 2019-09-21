<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TechnicalManagerRequest extends FormRequest
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
            'name' => 'required|min:3',
            'contact' => 'required|min:13',
            'email' => 'required|email',
            'selected_establishment' => 'digits_between:1,100'
        ];
    }

    public function messages()
    {
        return [
            'selected_establishment.digits_between' => 'Favor informar um estabelecimento para associar!'
        ];
    }
}
