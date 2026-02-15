<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left side - Logo and main navigation -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') ?? url('/') }}" class="flex items-center">
                        <span class="text-xl font-bold text-blue-700">Digitalna Općina Atl</span>
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