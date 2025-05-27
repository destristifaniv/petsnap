<?php

namespace App\Filament\Resources\DokterResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDokterRequest extends FormRequest
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
			'akun_id' => 'required',
			'nama' => 'required|string',
			'alamat' => 'required|string',
			'no_hp' => 'required|string',
			'foto' => 'required|string'
		];
    }
}
