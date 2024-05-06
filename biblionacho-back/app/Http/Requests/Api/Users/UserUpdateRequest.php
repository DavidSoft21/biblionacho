<?php

namespace App\Http\Requests\Api\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $passwordRules = $this->getMethod() == 'POST' ? 'required|string|min:8|max:100|regex:/^\S*$/|confirmed' : 'nullable|string|min:8|max:100|regex:/^\S*$/|confirmed';

        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $this->user()->id,
            'password' => $passwordRules,
        ];
    }
}
