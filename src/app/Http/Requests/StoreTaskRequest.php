<?php

namespace App\Http\Requests;

use App\Models\Setting;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $allowDuplicates = Setting::where('param', 'allow_duplicates')->first();

        if ($allowDuplicates !== null && (bool) $allowDuplicates->getAttribute('value') === true) {
            return [];
        }

        return [
            'label' => 'required|unique:tasks',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 422));
    }
}
