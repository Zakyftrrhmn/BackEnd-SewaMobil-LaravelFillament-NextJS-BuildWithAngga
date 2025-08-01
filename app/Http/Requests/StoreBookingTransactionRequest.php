<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingTransactionRequest extends FormRequest
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
            'phone' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'started_at' => 'required|date',
            'vehicle_id' => 'required|integer',
            'alpina_store_id' => 'required|integer',
            'total_amount' => 'required|integer',
            'proof' => 'required|file|mimes:png,jpg,jpeg|max:2048',
        ];
    }
}
