<x-app-layout>
    <!-- Hero Banner -->
    <div class="relative bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 text-white overflow-hidden py-16 sm:py-24">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#f97316_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/10 text-orange-300 border border-white/20 mb-6 backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-accent-500 animate-pulse"></span>
                LKPD XI Rekayasa Perangkat Lunak • SMK PESAT IT XPRO
            </span>
            <h1 class="text-4xl sm:text-6xl font-black tracking-tight leading-tight">
                Tingkatkan Skill IT Kamu Bersama <br class="hidden sm:inline" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-300">WorkshopTIX</span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-slate-300">
                Registrasi online praktis, pembayaran instan via Midtrans Sandbox, dan tiket digital ber-QR Code langsung di genggaman Anda.
            </p>

            <!-- Search & Filters Bar -->
            <div class="mt-8 max-w-4xl mx-auto bg-white p-3 sm:p-4 rounded-2xl shadow-2xl text-slate-800">
                <form action="{{ route('home') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                    <div class="sm:col-span-5 relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari judul workshop atau pemateri..."
                            class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-primary-500"
                        />
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <div class="sm:col-span-3">
                        <select name="max_price" class="w-full py-2.5 text-sm rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-primary-500 text-slate-600">
                            <option value="">Semua Harga</option>
                            <option value="50000" {{ request('max_price') == '50000' ? 'selected' : '' }}>≤ Rp 50.000</option>
                            <option value="100000" {{ request('max_price') == '100000' ? 'selected' : '' }}>≤ Rp 100.000</option>
                            <option value="150000" {{ request('max_price') == '150000' ? 'selected' : '' }}>≤ Rp 150.000</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <input
                            type="date"
                            name="date"
                            value="{{ request('date') }}"
                            class="w-full py-2.5 px-3 text-sm rounded-xl border border-slate-200 focus:border-primary-500 focus:ring-primary-500 text-slate-600"
                        />
                    </div>

                    <div class="sm:col-span-2 flex gap-1">
                        <button type="submit" class="w-full py-2.5 bg-primary-900 hover:bg-primary-800 text-white font-semibold text-sm rounded-xl shadow-xs transition">
                            Cari
                        </button>
                        @if(request()->anyFilled(['search', 'max_price', 'date']))
                            <a href="{{ route('home') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl flex items-center justify-center transition" title="Reset filter">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Workshop Catalog Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Katalog Workshop Terkini</h2>
                <p class="text-sm text-slate-500 mt-1">Pilih workshop yang sesuai minat dan tingkatkan portofolio kejuruan Anda</p>
            </div>
            <span class="text-sm font-semibold text-slate-500 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-xs">
                {{ $workshops->count() }} Workshop Tersedia
            </span>
        </div>

        @if($workshops->isEmpty())
            <x-empty-state
                title="Workshop Tidak Ditemukan"
                description="Tidak ada workshop yang cocok dengan kata kunci atau filter pencarian Anda."
                action="Lihat Semua Workshop"
                :actionUrl="route('home')"
            />
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($workshops as $workshop)
                    <x-workshop-card :workshop="$workshop" />
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>