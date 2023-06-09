<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'dni' => 'required|string|max:45',
            'id_reg' => 'required|integer|exists:regions,id',
            'id_com' => 'required|integer|exists:communes,id',
            'email' => 'required|string|max:120|email|'. isset($this->user->id) ? 'unique:users,email,' . $this->user->id : 'unique:users',
            'name' => 'required|string|max:45',
            'last_name' => 'required|string|max:45',
            'address' => 'nullable|string|max:255',
            'date_reg' => 'required|date_format:Y-m-d H:i:s',
            'status' => 'required|in:A,I,trash',
        ];
    }
}
