<?php

namespace App\Filament\Resources\PetResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePetRequest extends FormRequest
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
			'jenis' => 'required|string',
			'warna' => 'required|string',
			'usia' => 'required|integer',
			'kondisi' => 'required|string',
			'pemilik_id' => 'required',
			'foto' => 'required|string'
		];
    }
}
