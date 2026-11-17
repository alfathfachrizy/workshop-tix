<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-extrabold text-primary-900 tracking-tight">Masuk ke WorkshopTIX</h2>
        <p class="text-sm text-slate-500 mt-1">Gunakan akun Anda untuk mendaftar workshop dan melihat tiket</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@pesat.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="block mt-1 w-full rounded-xl"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-primary-900 shadow-xs focus:ring-primary-900" name="remember">
                <span class="ms-2 text-sm text-slate-600">Ingat Saya</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm font-medium text-slate-600 hover:text-primary-900 transition-colors" href="{{ route('register') }}">
                Belum punya akun? <span class="font-bold text-primary-900 underline">Daftar</span>
            </a>

            <button type="submit" class="px-5 py-2.5 bg-primary-900 hover:bg-primary-800 text-white font-semibold text-sm rounded-xl shadow-xs hover:shadow-md transition">
                Masuk
            </button>
        </div>
    </form>
</x-guest-layout>