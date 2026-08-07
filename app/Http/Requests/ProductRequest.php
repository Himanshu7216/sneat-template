<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator;


class ProductRequest extends FormRequest
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
            'sku'                => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9-_]+$/', 'unique:products,sku'],
            'productname'        => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'description'        => ['required', 'string', 'max:1000'],
            'color'              => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z\s]+$/'],
            'size'               => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9\s.-]+$/'],
            'price'              => ['required', 'numeric', 'min:0'],
            'category'           => ['required', 'exists:categories,id', 'integer'],
            // Images are pre-uploaded by Dropzone; filenames are sent back as uploaded_images[]
            'uploaded_images'    => ['required', 'array', 'min:1'],
            'uploaded_images.*'  => ['string'],
        ];
    }
     protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $validator->errors()
        ], 422));
    }
}
