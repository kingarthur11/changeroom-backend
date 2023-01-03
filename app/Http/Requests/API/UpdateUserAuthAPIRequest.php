<?php

namespace App\Http\Requests\API;

use App\Models\User;
use InfyOm\Generator\Request\APIRequest;

class UpdateUserAuthAPIRequest extends APIRequest
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
        $rules = [
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|string|max:100',
            'phone_number' => 'nullable|min:9|max:11',
            'country_id' => 'nullable|integer|exists:countries,id',
        ];

        return $rules;
    }
}
