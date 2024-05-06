<?php

namespace App\Http\Requests\Api\Books;

use Illuminate\Foundation\Http\FormRequest;

class BookCreateRequest extends FormRequest
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
            'isbn' => 'required|string|max:10|min:5|unique:books',
            'title' => 'required|string|max:100',
            'author' => 'required|string|max:100',
            'editorial' => 'required|string|min:8|max:100',
            'edition' => 'required|string|max:100',
            'year' => 'required|string|digits:4',
            'language' => 'required|string|max:4',
            'pages' => 'required|string|min:2|max:5'
        ];
    }
}
