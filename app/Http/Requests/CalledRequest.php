<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CalledRequest extends FormRequest
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

    public function all($keys = null)
    {
        return $this->cleanCheckBoxInputs(parent::all());
    }

    public function cleanCheckBoxInputs(array $inputs)
    {
        $inputs['typeProblem'] = array_filter($inputs['typeProblem']);
        $inputs['actionsTaken'] = array_filter($inputs['actionsTaken']);
        return $inputs;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'establishment_code' => 'required',
            'id_link' => 'required|numeric',
            'hr_down' => 'required',
            'typeProblem' => 'required|min:1'
        ];
    }
}
