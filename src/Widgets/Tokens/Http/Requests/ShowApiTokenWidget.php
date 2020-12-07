<?php

namespace AwemaPL\Auth\Widgets\Tokens\Http\Requests;

use AwemaPL\Auth\Sections\Options\Models\Option;
use AwemaPL\Auth\Widgets\Tokens\Http\Requests\Rules\PasswordValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowApiTokenWidget extends FormRequest
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
            'password' => ['required', 'string', 'max:255', new PasswordValidation()],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'password' =>  _p('auth::requests.widget.token.attributes.password', 'password'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
