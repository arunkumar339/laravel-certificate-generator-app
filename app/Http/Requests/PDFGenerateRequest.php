<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PDFGenerateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name-x'=>'required|numeric',
            'name-y'=>'required|numeric',
            'date-x'=>'required|numeric',
            'date-y'=>'required|numeric',
            'pdf-file'=>'required|file|mimes:pdf',
            'csv-file'=>'required|file',
        ];
    }
}
