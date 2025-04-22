<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class StoreTravelRequest extends FormRequest
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
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'number_of_days' => ['required', 'numeric'],
            'is_public' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        Request::merge([
            'is_public' => Request::boolean('is_public'),
        ]);
    }
}
