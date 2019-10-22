<?php

namespace App\Http\Requests;

use App\Utils\DateUtils;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CalledRequest extends FormRequest
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
        $this->inputs = parent::all();
        return $this->trateInputs();
    }

    public function trateInputs()
    {
        $this->inputs['typeProblem'] = array_filter($this->inputs['typeProblem']);
        $this->inputs['actionsTaken'] = array_filter($this->inputs['actionsTaken']);
        $this->inputs['hr_down'] = DateUtils::convertDataDataBase($this->inputs['hr_down']);

        if(!empty($this->inputs['hr_up'])){
            $this->inputs['hr_up'] = DateUtils::convertDataDataBase($this->inputs['hr_up']);
        }

        return $this->inputs;
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
            'status' => 'required',
            'id_link' => 'required|numeric',
            'hr_down' => 'required',
            'typeProblem' => 'required|min:1',
            'actionsTaken' => 'required|min:1',
            'next_action' => 'required|numeric',
            'otrs' => 'required_if:next_action, 3',
            'sisman' => 'required_if:next_action,4',
            'hr_up' => 'required_if:next_action,1',
            'call_telecommunications_company' => 'required_if:next_action,2',
            'deadline' => 'required_if:next_action, 2',
            'id_problem_cause' => 'required_if:next_action,1',
            'attachment' => 'max:5',
            'attachment.*' => (!empty($this->inputs['attachment']) ? 'image|mimes:jpeg,png,jpg,gif,svg|max:1024' : ''),
            'hr_down' => '',
            'hr_up' => (!empty($this->inputs['hr_up']) ? 'after:hr_down' : '')
        ];
    }


    public function messages()
    {
        return [
            'hr_up.after' => 'A hora de normalização não pode ser menor que a hora do incidente!'
        ];
    }
}
