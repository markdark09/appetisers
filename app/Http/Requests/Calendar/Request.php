<?php

namespace App\Http\Requests\Calendar;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
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
            'event' => 'required',
            'from'  => 'required|date|date_format:Y-m-d',
            'to'    => 'required|date|date_format:Y-m-d|after_or_equal:from',
            'days' => 'array'
        ];
    }
}
