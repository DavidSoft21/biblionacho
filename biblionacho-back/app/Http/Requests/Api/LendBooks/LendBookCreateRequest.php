<?php

namespace App\Http\Requests\Api\LendBooks;

use Illuminate\Foundation\Http\FormRequest;

class LendBookCreateRequest extends FormRequest
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
        return [
            'identification' => 'required|string|max:20|exists:users',
            'isbn' => 'required|string|max:10|min:5',
            'observations' => 'required|string|max:100',
            // 'deadline' => 'required|string|max:100|date_format:d-m-Y|after:today',
            'returned' => 'required|boolean',
            'user_id' => 'required|int|min:1|exists:users,id',
            'book_id' => 'required|int|min:1|exists:books,id'
        ];
    }
}
