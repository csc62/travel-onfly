<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTravelOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // depois vamos conectar com auth
    }

    public function rules(): array
    {
        return [
            'destination'     => 'required|string|max:255',
            'departure_date'  => 'required|date|after_or_equal:today',
            'return_date'     => 'required|date|after:departure_date',
        ];
    }
}
