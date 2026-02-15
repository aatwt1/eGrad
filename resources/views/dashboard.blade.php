<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Digitalna Općina - Početna stranica</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <!-- Navbar -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Left side - Logo and main navigation -->
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') ?? url('/') }}" class="flex items-center">
                                <span class="text-2xl font-bold text-blue-700">Digitalna Općina</span>
                            </a>
                        </div>

                        <!-- Desktop Navigation Links -->
                        <div class="hidden sm:ml-10 sm:flex sm:space-x-8">
                            <a href="{{ route('dashboard') ?? url('/') }}" 
                               class="{{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Početna
                            </a>
                            
                            <a href="{{ route('budgets.index') ?? '#' }}" 
                               class="{{ request()->routeIs('budgets.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Budžet
                            </a>
                            
                            <a href="{{ route('initiatives.index') ?? '#' }}" 
                               class="{{ request()->routeIs('initiatives.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Inicijative
                            </a>
                            
                            <a href="{{ route('reported-issues.index') ?? '#' }}" 
                               class="{{ request()->routeIs('reported-issues.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Prijava problema
                            </a>
                        </div>
                    </div>

                    <!-- Right side - User dropdown -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        @auth
                        <div class="ml-3 relative">
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-700">
                                    Dobrodošli, {{ Auth::user()->name }}
                                </span>
                                
                                <!-- Dropdown -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <div x-show="open" 
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                        
                                        <a href="{{ route('profile.show') ?? '#' }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Profil
                                        </a>
                                        
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Odjava
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" 
                               class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                Prijava
                            </a>
                            <a href="{{ route('register') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                Registracija
                            </a>
                        </div>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center sm:hidden">
                        <button type="button" 
                                @click="mobileMenuOpen = !mobileMenuOpen"
                                x-data="{ mobileMenuOpen: false }"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                            <span class="sr-only">Otvori meni</span>
                            <svg :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen}" 
                                 class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg :class="{'hidden': !mobileMenuOpen, 'block': mobileMenuOpen}" 
                                 class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div class="sm:hidden" x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false">
                    <div class="pt-2 pb-3 space-y-1">
                        <a href="{{ route('dashboard') ?? url('/') }}" 
                           class="{{ request()->routeIs('dashboard') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            Početna
                        </a>
                        
                        <a href="{{ route('budgets.index') ?? '#' }}" 
                           class="{{ request()->routeIs('budgets.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            Budžet
                        </a>
                        
                        <a href="{{ route('initiatives.index') ?? '#' }}" 
                           class="{{ request()->routeIs('initiatives.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            Inicijative
                        </a>
                        
                        <a href="{{ route('reported-issues.index') ?? '#' }}" 
                           class="{{ request()->routeIs('reported-issues.*') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                            Prijava problema
                        </a>
                    </div>
                    
                    @auth
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <a href="{{ route('profile.show') ?? '#' }}" 
                               class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                    Odjava
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        <div class="space-y-1">
                            <a href="{{ route('login') }}" 
                               class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                Prijava
                            </a>
                            <a href="{{ route('register') }}" 
                               class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                Registracija
                            </a>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </nav>

        {{-- HERO --}}
        <section class="bg-gradient-to-r from-blue-700 to-blue-500 text-white">
            <!-- PRAZAN PROSTOR IZNAD SADRŽAJA UNUTAR PLAVE POZADINE -->
            <div class="h-24"></div>
            
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold mb-8">
                    Digitalna općina – aktivni građani
                </h1>
                <p class="text-lg md:text-xl text-blue-100 max-w-3xl mx-auto mb-12">
                    Platforma za transparentno upravljanje, participativni budžet i direktno učešće građana u razvoju zajednice.
                </p>

                <a href="{{ route('budgets.index') ?? '#' }}"
                   class="inline-block bg-yellow-400 hover:bg-yellow-500 text-blue-900 font-semibold px-8 py-3 rounded-lg shadow-lg transition transform hover:-translate-y-1">
                    Započni
                </a>
            </div>
            
            <!-- PRAZAN PROSTOR ISPOD SADRŽAJA UNUTAR PLAVE POZADINE -->
            <div class="h-24"></div>
        </section>

        {{-- FEATURES --}}
        <section class="py-16 bg-gray-100">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid gap-8 md:grid-cols-3">

                    {{-- Budžet --}}
                    <div class="bg-white rounded-xl shadow p-6 flex flex-col hover:shadow-lg transition">
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">
                            Budžet općine
                        </h3>
                        <p class="text-gray-600 flex-grow">
                            Pregled godišnjeg budžeta, raspodjela sredstava i participativne inicijative građana.
                        </p>
                        <a href="{{ route('budgets.index') ?? '#' }}"
                           class="mt-4 text-blue-600 font-medium hover:underline inline-flex items-center">
                            Pogledaj budžet 
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Prijava kvara --}}
                    <div class="bg-white rounded-xl shadow p-6 flex flex-col hover:shadow-lg transition">
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">
                            Prijava problema
                        </h3>
                        <p class="text-gray-600 flex-grow">
                            Prijavite komunalne kvarove i pratite status rješavanja u realnom vremenu.
                        </p>
                        <a href="{{ route('reported-issues.index') ?? '#' }}"
                           class="mt-4 text-blue-600 font-medium hover:underline inline-flex items-center">
                            Prijavi problem
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Događaji --}}
                    <div class="bg-white rounded-xl shadow p-6 flex flex-col hover:shadow-lg transition">
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">
                            Događaji i dozvole
                        </h3>
                        <p class="text-gray-600 flex-grow">
                            Podnesite zahtjev za organizaciju javnih događaja ili potrebne dozvole.
                        </p>
                        <a href="{{ route('events.index') ?? '#' }}"
                           class="mt-4 text-blue-600 font-medium hover:underline inline-flex items-center">
                            Pogledaj događaje
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>

                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="bg-white py-16 border-t">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">
                    Vaše mišljenje je važno
                </h2>
                <p class="text-gray-600 mb-8 text-lg">
                    Imate prijedlog, pitanje ili inicijativu? Javite nam se i učestvujte u razvoju vaše zajednice.
                </p>

                <a href="mailto:info@opcina.ba"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition transform hover:-translate-y-1 shadow-lg">
                    Kontaktirajte općinu
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <span class="text-xl font-bold text-white">Digitalna Općina</span>
                        <p class="text-gray-300 mt-2">Transparentno upravljanje za bolju zajednicu</p>
                    </div>
                    <div class="text-center md:text-right">
                        <p>&copy; {{ date('Y') }} Digitalna Općina. Sva prava zadržana.</p>
                        <div class="mt-2 space-x-4">
                            <a href="#" class="text-gray-300 hover:text-white">Uslovi korištenja</a>
                            <a href="#" class="text-gray-300 hover:text-white">Politika privatnosti</a>
                            <a href="mailto:info@opcina.ba" class="text-gray-300 hover:text-white">Kontakt</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Alpine.js for mobile menu -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    </body>
</html>