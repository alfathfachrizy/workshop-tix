<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-extrabold text-primary-900 tracking-tight">Daftar Akun Baru</h2>
        <p class="text-sm text-slate-500 mt-1">Daftarkan akun peserta untuk mengikuti workshop IT SMK PESAT</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" class="block mt-1 w-full rounded-xl" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama lengkap Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@pesat.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="block mt-1 w-full rounded-xl"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-xl"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Ulangi kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm font-medium text-slate-600 hover:text-primary-900 transition-colors" href="{{ route('login') }}">
                Sudah punya akun? <span class="font-bold text-primary-900 underline">Masuk</span>
            </a>

            <button type="submit" class="px-5 py-2.5 bg-primary-900 hover:bg-primary-800 text-white font-semibold text-sm rounded-xl shadow-xs hover:shadow-md transition">
                Daftar Sekarang
            </button>
        </div>
    </form>
</x-guest-layout>