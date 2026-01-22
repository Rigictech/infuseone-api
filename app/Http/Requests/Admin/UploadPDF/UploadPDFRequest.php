<?php

namespace App\Http\Requests\Admin\UploadPDF;

use Illuminate\Foundation\Http\FormRequest;

class UploadPDFRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'pdf'   => 'nullable|mimes:pdf|max:10240', // 10MB
        ];
    }
}
