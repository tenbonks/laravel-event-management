<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        // Rules change slightly if the current method is put, or patch

        $rules = [
            
        ];

        return $rules;
    }
}
