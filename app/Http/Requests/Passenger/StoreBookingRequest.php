<?php

namespace App\Http\Requests\Passenger;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'passengers' => ['required', 'array', 'min:1', 'max:3'],
            'passengers.*.seat_id' => ['required', 'integer', 'exists:seats,id', 'distinct'],
            'passengers.*.name' => ['required', 'string', 'max:255'],
            'passengers.*.phone' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'schedule_id.required' => 'Jadwal perjalanan wajib dipilih.',
            'schedule_id.exists' => 'Jadwal perjalanan tidak valid.',
            'passengers.required' => 'Informasi penumpang wajib diisi.',
            'passengers.min' => 'Pemesanan minimal memilih 1 kursi.',
            'passengers.max' => 'Pemesanan maksimal 3 kursi dalam satu transaksi.',
            'passengers.*.seat_id.required' => 'Nomor kursi wajib dipilih untuk setiap penumpang.',
            'passengers.*.seat_id.exists' => 'Kursi yang dipilih tidak valid.',
            'passengers.*.seat_id.distinct' => 'Setiap penumpang harus memilih kursi yang berbeda.',
            'passengers.*.name.required' => 'Nama penumpang wajib diisi.',
            'passengers.*.phone.required' => 'Nomor telepon penumpang wajib diisi.',
        ];
    }
}
