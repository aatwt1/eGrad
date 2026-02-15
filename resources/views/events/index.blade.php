<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Zahtjevi za dozvole za događaje') }}
            </h2>
            <a href="{{ route('events.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Novi zahtjev
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <!-- Total Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-md bg-blue-500 p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 truncate">
                                    {{ __('Ukupno zahtjeva') }}
                                </p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ $stats['total'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-md bg-yellow-500 p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 truncate">
                                    {{ __('Na čekanju') }}
                                </p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ $stats['pending'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Approved Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-md bg-green-500 p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 truncate">
                                    {{ __('Odobreno') }}
                                </p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ $stats['approved'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rejected Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-md bg-red-500 p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 truncate">
                                    {{ __('Odbijeno') }}
                                </p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ $stats['rejected'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                        <!-- Search -->
                        <form method="GET" action="{{ route('events.index') }}" class="flex-1 max-w-md">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="{{ __('Pretraži događaje...') }}"
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </form>

                        <!-- Status Filter -->
                        <div class="flex space-x-2">
                            <a href="{{ route('events.index', ['search' => request('search')]) }}" 
                               class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ __('Svi') }}
                            </a>
                            <a href="{{ route('events.index', ['status' => 'pending', 'search' => request('search')]) }}" 
                               class="px-4 py-2 rounded-lg {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ __('Na čekanju') }}
                            </a>
                            <a href="{{ route('events.index', ['status' => 'approved', 'search' => request('search')]) }}" 
                               class="px-4 py-2 rounded-lg {{ request('status') == 'approved' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ __('Odobreno') }}
                            </a>
                            <a href="{{ route('events.index', ['status' => 'rejected', 'search' => request('search')]) }}" 
                               class="px-4 py-2 rounded-lg {{ request('status') == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ __('Odbijeno') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events List -->
            @if($events->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <ul class="divide-y divide-gray-200">
                        @foreach($events as $event)
                            <li class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                <div class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center mb-2">
                                                <h3 class="text-lg font-medium text-gray-900 truncate">
                                                    {{ $event->name }}
                                                </h3>
                                                <span class="ml-3 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $event->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($event->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $event->status == 'pending' ? 'Na čekanju' : ($event->status == 'approved' ? 'Odobreno' : 'Odbijeno') }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-3">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    {{ $event->location }}
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($event->date)->format('d.m.Y. H:i') }}
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                                    </svg>
                                                    {{ $event->comments_count }} komentara
                                                </div>
                                            </div>

                                            @if($event->description)
                                                <p class="text-gray-600 mb-4 line-clamp-2">
                                                    {{ Str::limit($event->description, 200) }}
                                                </p>
                                            @endif

                                            <!-- Comments Section -->
                                            <div class="mt-4">
                                                <!-- Show recent comments -->
                                                @if($event->comments_count > 0)
                                                    <div class="mb-4">
                                                        <h4 class="text-sm font-medium text-gray-700 mb-2">
                                                            {{ __('Zadnji komentari') }}
                                                        </h4>
                                                        @foreach($event->comments()->latest()->take(2)->get() as $comment)
                                                            <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                                                                <div class="flex justify-between items-start">
                                                                    <div>
                                                                        <div class="flex items-center">
                                                                            <span class="text-sm font-medium text-gray-900">
                                                                                {{ $comment->user->name }}
                                                                            </span>
                                                                            @if($comment->user->isAdmin())
                                                                                <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                                                    Admin
                                                                                </span>
                                                                            @endif
                                                                            <span class="ml-2 text-xs text-gray-500">
                                                                                {{ $comment->created_at->diffForHumans() }}
                                                                            </span>
                                                                        </div>
                                                                        <p class="mt-1 text-sm text-gray-600">
                                                                            {{ $comment->content }}
                                                                        </p>
                                                                    </div>
                                                                    @if(Auth::id() === $comment->user_id || Auth::user()->isAdmin())
                                                                        <form action="{{ route('events.comments.destroy', $comment) }}" 
                                                                              method="POST" 
                                                                              onsubmit="return confirm('Jeste li sigurni?')">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" 
                                                                                    class="text-red-500 hover:text-red-700">
                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                                </svg>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <!-- Add Comment Form -->
                                                @if($event->user_id === Auth::id() || Auth::user()->isAdmin())
                                                    <form action="{{ route('events.comments.store', $event) }}" method="POST" class="mt-4">
                                                        @csrf
                                                        <div>
                                                            <label for="content-{{ $event->id }}" class="sr-only">
                                                                {{ __('Dodaj komentar') }}
                                                            </label>
                                                            <textarea 
                                                                id="content-{{ $event->id }}"
                                                                name="content"
                                                                rows="2"
                                                                class="w-full border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                                                placeholder="{{ __('Dodaj komentar...') }}"
                                                                required></textarea>
                                                        </div>
                                                        <div class="mt-2 flex justify-end">
                                                            <button type="submit" 
                                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                                {{ __('Pošalji komentar') }}
                                                            </button>
                                                        </div>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="ml-4 flex-shrink-0">
                                            <a href="{{ route('events.show', $event) }}" 
                                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                {{ __('Detalji') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Pagination -->
                @if($events->hasPages())
                    <div class="mt-6">
                        {{ $events->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">
                            {{ __('Nema zahtjeva za događaje') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('Započnite s podnošenjem prvog zahtjeva za dozvolu za događaj.') }}
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('events.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ __('Novi zahtjev') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>