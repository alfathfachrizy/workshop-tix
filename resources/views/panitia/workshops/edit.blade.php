<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit Workshop</h1>
                <p class="text-sm text-slate-500 mt-1">Perbarui rincian informasi workshop</p>
            </div>
            <a href="{{ route('panitia.workshops.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-xs">
            <form action="{{ route('panitia.workshops.update', $workshop->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <x-form-input
                    label="Judul Workshop"
                    name="title"
                    :value="$workshop->title"
                    :required="true"
                />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-form-input
                        label="Slug"
                        name="slug"
                        :value="$workshop->slug"
                    />

                    <x-form-input
                        label="Nama Pemateri & Gelar"
                        name="speaker"
                        :value="$workshop->speaker"
                        :required="true"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-form-input
                        label="Lokasi / Ruangan"
                        name="location"
                        :value="$workshop->location"
                        :required="true"
                    />

                    <x-form-input
                        label="Jadwal Pelaksanaan"
                        name="starts_at"
                        type="datetime-local"
                        :value="\Carbon\Carbon::parse($workshop->starts_at)->format('Y-m-d\TH:i')"
                        :required="true"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-form-input
                        label="Biaya Investasi (IDR)"
                        name="price"
                        type="number"
                        :value="$workshop->price"
                        :required="true"
                    />

                    <div>
                        <x-form-input
                            label="Kuota Maksimal Peserta"
                            name="quota"
                            type="number"
                            :value="$workshop->quota"
                            :required="true"
                        />
                        <p class="text-[11px] text-slate-400 mt-1">
                            Saat ini terdapat {{ $workshop->registrations()->count() }} pendaftar. Kuota tidak boleh lebih rendah dari angka ini.
                        </p>
                    </div>
                </div>

                <x-form-input
                    label="Deskripsi & Silabus Materi"
                    name="description"
                    type="textarea"
                    :value="$workshop->description"
                    :required="true"
                />

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('panitia.workshops.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-primary-900 hover:bg-primary-800 text-white font-bold text-sm rounded-xl shadow-xs hover:shadow-md transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>