<?php

namespace App\Filament\Resources\DiagnosaResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDiagnosaRequest extends FormRequest
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
			'hewan_id' => 'required',
			'dokter_id' => 'required',
			'tanggal_diagnosa' => 'required|date',
			'catatan' => 'required|string'
		];
    }
}
