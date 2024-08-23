<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        // Rules change slightly if the current method is put, or patch

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time'
        ];

        // If method is put or post (updating), make the 'required' fields 'sometimes'
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['name'] = 'sometimes|string|max:255';
            $rules['start_time'] = 'sometimes|date';
            $rules['end_time'] = 'sometimes|date|after:start_time';
        }

        return $rules;
    }
}
