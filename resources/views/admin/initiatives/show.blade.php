<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pregled inicijative: ') }} {{ $initiative->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.initiatives.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    &larr; Nazad na listu
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Glavni dio - Informacije o inicijativi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6 pb-4 border-b">
                        <div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusInfo['color'] }}">
                                {{ $statusInfo['text'] }}
                            </span>
                            <span class="ml-2 text-sm text-gray-500">
                                Kreirano: {{ $initiative->created_at->format('d.m.Y. H:i') }}
                            </span>
                        </div>
                        
                        <div class="flex space-x-2">
                            @if($initiative->status === 'pending')
                                <form action="{{ route('admin.initiatives.approve', $initiative) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            onclick="return confirm('Da li ste sigurni da želite odobriti ovu inicijativu?')"
                                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                        Odobri inicijativu
                    </button>
                                </form>

                                <button onclick="openRejectModal({{ $initiative->id }}, '{{ addslashes($initiative->title) }}')" 
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Odbij inicijativu
                                </button>
                            @endif

                            @if($initiative->status === 'approved')
                                <form action="{{ route('admin.initiatives.implemented', $initiative) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            onclick="return confirm('Da li ste sigurni da je ova inicijativa implementirana?')"
                                            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Označi kao implementirano
                                    </button>
                                </form>
                            @endif

                            @if($initiative->status === 'rejected' && $initiative->rejection_reason)
                                <button onclick="showRejectionReason('{{ addslashes($initiative->rejection_reason) }}')"
                                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Prikaži razlog odbijanja
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Lijeva kolona - Osnovni podaci -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Osnovni podaci</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Naslov</label>
                                    <p class="mt-1 text-lg text-gray-900">{{ $initiative->title }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Kategorija</label>
                                    <p class="mt-1 text-gray-900">{{ $initiative->category ?? 'Nije kategorizovano' }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Cilj</label>
                                    <p class="mt-1 text-gray-900">{{ $initiative->goal }}</p>
                                </div>

                                @if($initiative->estimated_budget)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Procijenjeni budžet</label>
                                    <p class="mt-1 text-gray-900">{{ number_format($initiative->estimated_budget, 2) }} KM</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Desna kolona - Autor i statistika -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Autor i statistika</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Autor</label>
                                    <p class="mt-1 text-gray-900">{{ $initiative->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $initiative->user->email }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Lokalna zajednica</label>
                                    <p class="mt-1 text-gray-900">{{ $initiative->localCommunity->name ?? 'Nije definisano' }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                        <span class="text-2xl font-bold text-blue-600">{{ $initiative->votes_count ?? 0 }}</span>
                                        <p class="text-sm text-gray-500">Glasova</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                        <span class="text-2xl font-bold text-green-600">{{ $initiative->comments_count ?? 0 }}</span>
                                        <p class="text-sm text-gray-500">Komentara</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Opis inicijative</h3>
                        <div class="prose max-w-none">
                            {{ nl2br(e($initiative->description)) }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Odbijanje inicijative</h3>
                    <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form id="rejectForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">
                            Odbijate inicijativu: <span id="rejectInitiativeTitle" class="font-semibold"></span>
                        </p>
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-1">
                            Razlog odbijanja <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="rejection_reason" 
                            id="rejection_reason"
                            rows="4" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Unesite razlog odbijanja (minimalno 5 karaktera)"
                            required
                        ></textarea>
                        <p class="mt-1 text-xs text-gray-500">
                            Ovaj razlog će biti vidljiv korisniku koji je podnio inicijativu.
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeRejectModal()"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            Odustani
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Odbij inicijativu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal za prikaz razloga odbijanja -->
    <div id="reasonModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Razlog odbijanja</h3>
                    <button onclick="closeReasonModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-4">
                    <p id="rejectionReasonText" class="text-gray-700"></p>
                </div>

                <div class="flex justify-end">
                    <button type="button" 
                            onclick="closeReasonModal()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Zatvori
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @vite(['resources/js/admin.js'])
    @endpush
</x-app-layout>