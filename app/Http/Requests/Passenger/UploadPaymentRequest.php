<?php

namespace App\Http\Requests\Passenger;

use Illuminate\Foundation\Http\FormRequest;

class UploadPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'string', 'in:qris_manual,bank_transfer_manual'],
            'amount' => ['required', 'numeric', 'min:1'],
            'proof_file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran harus QRIS Manual atau Transfer Bank Manual.',
            'amount.required' => 'Nominal pembayaran wajib diisi.',
            'amount.min' => 'Nominal pembayaran tidak valid.',
            'proof_file.required' => 'Bukti pembayaran wajib diunggah.',
            'proof_file.mimes' => 'Bukti pembayaran harus berformat JPG, JPEG, PNG, atau PDF.',
            'proof_file.max' => 'Ukuran berkas bukti pembayaran maksimal 2 MB.',
        ];
    }
}
