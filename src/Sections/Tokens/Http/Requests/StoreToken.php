<?php

namespace AwemaPL\Auth\Sections\Tokens\Http\Requests;

use AwemaPL\Auth\Sections\Options\Models\Option;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreToken extends FormRequest
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
            'user_id' => 'bail|required|integer',
            'name' => ['required', 'string', 'max:255', Rule::unique('personal_access_tokens')->where(function ($query) {
                return $query->where('tokenable_id', $this->user_id)
                    ->where('tokenable_type', config('auth.providers.users.model'))
                    ->where('name', $this->name);
            })],

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
            'user_id' => _p('auth::requests.token.attributes.user_id', 'user'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => _p('auth::requests.token.messages.name_assigned_to_user', 'This name is already assigned to this user.'),
        ];
    }
}
