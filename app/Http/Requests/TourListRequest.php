<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TourListRequest extends FormRequest
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
            'priceFrom' => ['numeric'],
            'priceTo' => ['numeric'],
            'dateFrom' => ['date'],
            'dateTo' => ['date'],
            'sortPrice' => [Rule::in(['asc', 'desc'])],
        ];
    }
}
