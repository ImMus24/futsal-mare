<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'lapangan_id'  => ['required', 'integer', 'exists:lapangans,id'],
            'tanggal_main' => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'    => ['required', 'integer', 'between:8,21'],
            'durasi'       => ['required', 'integer', 'between:1,3'],
        ];
    }

    public function messages(): array
    {
        return [
            'lapangan_id.required' => 'Lapangan tidak ditemukan, silakan ulangi dari halaman lapangan.',
            'lapangan_id.exists'   => 'Lapangan ini sudah tidak tersedia atau telah dihapus.',

            'tanggal_main.required'       => 'Tanggal main wajib dipilih.',
            'tanggal_main.date'           => 'Format tanggal tidak valid.',
            'tanggal_main.after_or_equal' => 'Tanggal main tidak boleh sebelum hari ini.',

            'jam_mulai.required' => 'Silakan pilih jam main terlebih dahulu.',
            'jam_mulai.between'  => 'Jam main hanya tersedia antara pukul 08.00–21.00.',

            'durasi.required' => 'Durasi sewa wajib dipilih.',
            'durasi.between'  => 'Durasi sewa hanya bisa 1, 2, atau 3 jam.',
        ];
    }
}