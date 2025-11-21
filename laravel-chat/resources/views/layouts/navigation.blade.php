<nav x-data="{ open: false }" class="fixed inset-x-0 top-0 z-50 bg-black/90 border-b border-white/10 shadow-lg backdrop-blur">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('chat') }}">
                        <span class="text-2xl font-semibold text-white tracking-tight flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400">
                                ✦
                            </span>
                            {{ config('app.name', 'LivePulse') }}
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:ms-10 sm:flex sm:items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium rounded-md text-white bg-white/10 hover:bg-white/20 transition">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="px-3 py-2 text-sm font-medium rounded-md text-white border border-emerald-400 bg-emerald-500/30 hover:bg-emerald-500/70 transition">
                            Inscription
                        </a>
                    @endguest
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-2 text-sm font-medium rounded-md text-white bg-red-500/80 hover:bg-red-500 transition">
                        Déconnexion
                    </button>
                </form>
                <span class="px-3 py-2 text-sm font-medium rounded-md text-white bg-white/10">
                    {{ Auth::user()->first_name ?? Auth::user()->name }}
                </span>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-white hover:bg-white/10 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-black border-t border-white/5">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('chat')" :active="request()->routeIs('chat')">
                Chat
            </x-responsive-nav-link>
            @guest
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    Connexion
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                    Inscription
                </x-responsive-nav-link>
            @endguest
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/10">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->first_name ?? Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div>
            </div>

        </div>
    </div>
</nav>



