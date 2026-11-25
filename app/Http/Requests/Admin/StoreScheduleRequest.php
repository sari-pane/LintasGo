<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'route_id' => ['required', 'integer', 'exists:routes,id'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'driver_id' => ['required', 'integer', 'exists:drivers,id'],
            'departure_time' => ['required', 'date'],
            'arrival_time' => ['required', 'date', 'after:departure_time'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'route_id.required' => 'Rute wajib dipilih.',
            'route_id.exists' => 'Rute tidak ditemukan.',
            'vehicle_id.required' => 'Armada kendaraan wajib dipilih.',
            'vehicle_id.exists' => 'Armada kendaraan tidak ditemukan.',
            'driver_id.required' => 'Sopir wajib dipilih.',
            'driver_id.exists' => 'Sopir tidak ditemukan.',
            'departure_time.required' => 'Waktu keberangkatan wajib diisi.',
            'arrival_time.required' => 'Waktu kedatangan wajib diisi.',
            'arrival_time.after' => 'Waktu kedatangan harus setelah waktu keberangkatan.',
            'price.required' => 'Harga tiket wajib diisi.',
            'price.min' => 'Harga tiket tidak boleh bernilai negatif.',
        ];
    }
}
