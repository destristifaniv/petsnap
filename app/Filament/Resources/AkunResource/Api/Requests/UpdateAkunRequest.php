<?php

namespace App\Filament\Resources\AkunResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAkunRequest extends FormRequest
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
			'nama' => 'required|string',
			'email' => 'required|string',
			'password' => 'required|string',
			'foto' => 'required|string',
			'role' => 'required|string'
		];
    }
}
