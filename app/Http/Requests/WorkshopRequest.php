<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkshopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isPanitia();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $workshopId = $this->route('workshop')?->id ?? $this->route('workshop');

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('workshops', 'slug')->ignore($workshopId)],
            'description' => ['required', 'string'],
            'speaker' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'price' => ['required', 'integer', 'min:0'],
            'quota' => ['required', 'integer', 'min:1'],
        ];

        // Quota cannot be lowered below existing registration count
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $workshop = $this->route('workshop');
            if ($workshop) {
                $currentCount = $workshop->registrations()->count();
                $rules['quota'][] = "min:{$currentCount}";
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul workshop wajib diisi.',
            'slug.unique' => 'Slug workshop sudah digunakan, pilih slug lain.',
            'description.required' => 'Deskripsi workshop wajib diisi.',
            'speaker.required' => 'Nama pemateri wajib diisi.',
            'location.required' => 'Lokasi atau ruangan workshop wajib diisi.',
            'starts_at.required' => 'Jadwal pelaksanaan workshop wajib diisi.',
            'price.required' => 'Biaya workshop wajib diisi.',
            'price.integer' => 'Biaya harus berupa angka.',
            'price.min' => 'Biaya tidak boleh bernilai negatif.',
            'quota.required' => 'Kuota peserta wajib diisi.',
            'quota.min' => 'Kuota tidak boleh lebih kecil dari jumlah peserta yang telah terdaftar (:min peserta).',
        ];
    }
}