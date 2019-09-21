<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LinksRequest extends FormRequest
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
           'type_link' => 'required',
           'link_identification' => 'required|min:3',
           'bandwidth' => ['required', 'regex:/^[0-9]{1,}KB|MB|GB/'],
           'telecommunications_company' => 'required',
           'monitoring_ip' => 'required|ipv4',
           'local_ip_router' => 'required|ipv4',
           'establishment_id' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'bandwidth.regex' => 'O campo banda precisa corresponder o formato solicitado: Ex.: 512MB, 2MB ...'
        ];
    }
}
