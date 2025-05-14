<?php

namespace App\Filament\Resources\ObatResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateObatRequest extends FormRequest
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
			'diagnosa_id' => 'required',
			'nama_obat' => 'required|string',
			'dosis' => 'required|string',
			'catatan' => 'required|string'
		];
    }
}
