<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowLocationPriceRequest extends FormRequest
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
            //Avec l'aide de l'ia pour nullable et le date_format prompt : "comment je valide des date en laravel ?"
            //Documentation : https://laravel.com/docs/12.x/validation#form-request-validation et : https://laravel.com/docs/12.x/validation#rule-date-format
            'mindate' => ['nullable', 'date_format:Y-m-d'],
            'maxdate' => ['nullable', 'date_format:Y-m-d']
        ];
    }
}
