<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', 'string', 'in:approve,reject'],
            'rejection_reason' => ['nullable', 'required_if:action,reject', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi verifikasi wajib dipilih.',
            'action.in' => 'Aksi harus berupa approve atau reject.',
            'rejection_reason.required_if' => 'Alasan penolakan wajib diisi apabila pembayaran ditolak.',
            'rejection_reason.max' => 'Alasan penolakan maksimal 255 karakter.',
        ];
    }
}
