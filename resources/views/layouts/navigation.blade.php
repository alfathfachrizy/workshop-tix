<nav x-data="{ open: false }" class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-xs">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-900 to-primary-700 flex items-center justify-center text-white shadow-md shadow-primary-900/20 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold tracking-tight text-primary-900">Workshop<span class="text-accent-500">TIX</span></span>
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500 -mt-1">SMK PESAT RPL</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden sm:-my-px sm:ms-8 sm:flex sm:space-x-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('home') ? 'text-primary-900 font-semibold border-b-2 border-primary-900' : 'text-slate-600 hover:text-primary-900' }}">
                        Katalog Workshop
                    </a>

                    @auth
                        @if(auth()->user()->isPanitia())
                            <a href="{{ route('panitia.dashboard') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('panitia.dashboard') ? 'text-primary-900 font-semibold border-b-2 border-primary-900' : 'text-slate-600 hover:text-primary-900' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('panitia.workshops.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('panitia.workshops.*') ? 'text-primary-900 font-semibold border-b-2 border-primary-900' : 'text-slate-600 hover:text-primary-900' }}">
                                Kelola Workshop
                            </a>
                            <a href="{{ route('panitia.registrations.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('panitia.registrations.*') ? 'text-primary-900 font-semibold border-b-2 border-primary-900' : 'text-slate-600 hover:text-primary-900' }}">
                                Semua Pendaftaran
                            </a>
                        @else
                            <a href="{{ route('registrations.my') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('registrations.my') ? 'text-primary-900 font-semibold border-b-2 border-primary-900' : 'text-slate-600 hover:text-primary-900' }}">
                                Tiket Saya
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings / User Dropdown (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ auth()->user()->isPanitia() ? 'bg-purple-100 text-purple-800' : 'bg-primary-100 text-primary-800' }}">
                            {{ auth()->user()->isPanitia() ? 'Panitia' : 'Peserta' }}
                        </span>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-slate-200 text-sm leading-4 font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-hidden transition ease-in-out duration-150">
                                    <div class="font-semibold">{{ Auth::user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-slate-100 text-xs text-slate-500">
                                    Masuk sebagai <span class="font-semibold text-slate-700">{{ Auth::user()->email }}</span>
                                </div>
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profil Saya') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Keluar') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-primary-900 hover:text-primary-700 rounded-lg transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold text-white bg-primary-900 hover:bg-primary-800 rounded-lg shadow-xs hover:shadow-md transition-all">
                            Daftar Sekarang
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-hidden transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-slate-200 bg-white">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                Katalog Workshop
            </x-responsive-nav-link>

            @auth
                @if(auth()->user()->isPanitia())
                    <x-responsive-nav-link :href="route('panitia.dashboard')" :active="request()->routeIs('panitia.dashboard')">
                        Dashboard
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('panitia.workshops.index')" :active="request()->routeIs('panitia.workshops.*')">
                        Kelola Workshop
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('panitia.registrations.index')" :active="request()->routeIs('panitia.registrations.*')">
                        Semua Pendaftaran
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('registrations.my')" :active="request()->routeIs('registrations.my')">
                        Tiket Saya
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-slate-200">
                <div class="px-4">
                    <div class="font-medium text-base text-slate-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-semibold {{ auth()->user()->isPanitia() ? 'bg-purple-100 text-purple-800' : 'bg-primary-100 text-primary-800' }}">
                        {{ auth()->user()->isPanitia() ? 'Panitia' : 'Peserta' }}
                    </span>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profil Saya') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Keluar') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-slate-200 px-4 space-y-2">
                <a href="{{ route('login') }}" class="block w-full text-center py-2 text-sm font-semibold text-primary-900 border border-primary-900 rounded-lg">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="block w-full text-center py-2 text-sm font-semibold text-white bg-primary-900 rounded-lg">
                    Daftar Sekarang
                </a>
            </div>
        @endauth
    </div>
</nav>