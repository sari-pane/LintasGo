<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'route_id' => ['sometimes', 'required', 'integer', 'exists:routes,id'],
            'vehicle_id' => ['sometimes', 'required', 'integer', 'exists:vehicles,id'],
            'driver_id' => ['sometimes', 'required', 'integer', 'exists:drivers,id'],
            'departure_time' => ['sometimes', 'required', 'date'],
            'arrival_time' => ['sometimes', 'required', 'date', 'after:departure_time'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'required', 'string', 'in:scheduled,boarding,departed,completed,cancelled'],
        ];
    }

    public function messages(): array
    {
        return [
            'arrival_time.after' => 'Waktu kedatangan harus setelah waktu keberangkatan.',
            'price.min' => 'Harga tiket tidak boleh bernilai negatif.',
            'status.in' => 'Status jadwal tidak valid.',
        ];
    }
}
