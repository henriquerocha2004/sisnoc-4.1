<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EstablishmentRequest extends FormRequest
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
            'establishment_code' =>
            ['required', (!empty($this->request->all()['id']) ? Rule::unique('establishment', 'establishment_code')->ignore($this->request->all()['id']) : Rule::unique('establishment', 'establishment_code'))],
            'address' => 'required|min:3',
            'neighborhood' => 'required|min:3',
            'city' => 'required|min:3',
            'state' => 'required|max:2',
            'manager_name' => 'required|min:3',
            'manager_contact' => 'required|min:13',
            'regional_manager_code' => 'required|numeric',
            'technician_code' => 'required|numeric',
            'establishment_status' => "in:open,close"
        ];
    }
}
