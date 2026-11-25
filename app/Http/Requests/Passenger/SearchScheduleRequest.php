<?php

namespace App\Http\Requests\Passenger;

use Illuminate\Foundation\Http\FormRequest;

class SearchScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'origin' => ['required', 'string', 'max:100'],
            'destination' => ['required', 'string', 'max:100', 'different:origin'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'origin.required' => 'Kota asal wajib dipilih.',
            'destination.required' => 'Kota tujuan wajib dipilih.',
            'destination.different' => 'Kota tujuan tidak boleh sama dengan kota asal.',
            'date.required' => 'Tanggal keberangkatan wajib diisi.',
            'date.date_format' => 'Format tanggal harus YYYY-MM-DD.',
            'date.after_or_equal' => 'Tanggal keberangkatan tidak boleh tanggal lampau.',
        ];
    }
}
