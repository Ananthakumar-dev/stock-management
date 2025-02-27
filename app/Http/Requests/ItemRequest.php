<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'measurement_id' => 'required|exists:measurements,id',
            'attributes' => 'nullable|array',
            'attributes.*.id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required|string|max:255',
            'availability' => 'required|in:0,1',
        ];
    }

    /**
     * custom messages
     */
    public function messages()
    {
        return [
            'attributes.*.id' => 'Attribute is required',
            'attributes.*.value' => 'Attribute value is required',
        ];
    }
}
