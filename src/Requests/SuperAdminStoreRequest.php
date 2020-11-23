<?php

namespace AwemaPL\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use AwemaPL\Auth\Rules\ValidPhone;

class SuperAdminStoreRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
        ];
    }
}
