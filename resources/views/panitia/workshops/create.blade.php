<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Tambah Workshop Baru</h1>
                <p class="text-sm text-slate-500 mt-1">Publikasikan agenda pelatihan IT baru ke katalog peserta</p>
            </div>
            <a href="{{ route('panitia.workshops.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-xs">
            <form action="{{ route('panitia.workshops.store') }}" method="POST" class="space-y-6">
                @csrf

                <x-form-input
                    label="Judul Workshop"
                    name="title"
                    placeholder="Contoh: Mastering Laravel 11 & Clean Architecture"
                    :required="true"
                />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-form-input
                        label="Slug (Opsional, otomatis dari judul)"
                        name="slug"
                        placeholder="contoh-slug-workshop"
                    />

                    <x-form-input
                        label="Nama Pemateri & Gelar"
                        name="speaker"
                        placeholder="Contoh: Ir. Budi Santoso, M.Kom."
                        :required="true"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-form-input
                        label="Lokasi / Ruangan"
                        name="location"
                        placeholder="Contoh: Lab RPL SMK PESAT"
                        :required="true"
                    />

                    <x-form-input
                        label="Jadwal Pelaksanaan"
                        name="starts_at"
                        type="datetime-local"
                        :required="true"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <x-form-input
                        label="Biaya Investasi (IDR)"
                        name="price"
                        type="number"
                        placeholder="Contoh: 75000"
                        :required="true"
                    />

                    <x-form-input
                        label="Kuota Maksimal Peserta"
                        name="quota"
                        type="number"
                        placeholder="Contoh: 30"
                        :required="true"
                    />
                </div>

                <x-form-input
                    label="Deskripsi & Silabus Materi"
                    name="description"
                    type="textarea"
                    placeholder="Jelaskan gambaran umum, materi yang akan dipelajari, dan prasyarat peserta..."
                    :required="true"
                />

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('panitia.workshops.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-primary-900 hover:bg-primary-800 text-white font-bold text-sm rounded-xl shadow-xs hover:shadow-md transition">
                        Simpan & Publikasikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>