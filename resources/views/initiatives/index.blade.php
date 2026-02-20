<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Građanske inicijative
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Pregled inicijativa koje su pokrenuli građani. Podržite inicijative koje smatrate važnim za vašu zajednicu.
                </p>
            </div>

            <a href="{{ route('initiatives.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova inicijativa
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filteri i pretraga -->
            <div class="mb-6 bg-white rounded-lg shadow p-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('initiatives.index') }}" 
                           class="px-4 py-2 rounded-full text-sm {{ request('status') === null ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Sve ({{ $initiatives->total() }})
                        </a>
                        <a href="{{ route('initiatives.index', ['status' => 'pending']) }}" 
                           class="px-4 py-2 rounded-full text-sm {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Na čekanju
                        </a>
                        <a href="{{ route('initiatives.index', ['status' => 'approved']) }}" 
                           class="px-4 py-2 rounded-full text-sm {{ request('status') == 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Odobrene
                        </a>
                        <a href="{{ route('initiatives.index', ['status' => 'rejected']) }}" 
                           class="px-4 py-2 rounded-full text-sm {{ request('status') == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Odbijene
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b text-gray-600">
                                <th class="text-left py-3">Naziv</th>
                                <th class="text-left py-3">Mjesna zajednica</th>
                                <th class="text-center py-3">Glasovi</th>
                                <th class="text-center py-3">Status</th>
                                <th class="text-center py-3">Glasaj</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($initiatives as $initiative)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4">
                                        <div class="font-medium">
                                            <a href="{{ route('initiatives.show', $initiative) }}" 
                                               class="hover:text-blue-600 hover:underline">
                                                {{ $initiative->title }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Autor: {{ $initiative->user->name }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ $initiative->created_at->diffForHumans() }}
                                        </div>
                                    </td>

                                    <td class="py-4">
                                        {{ $initiative->localCommunity->name ?? 'Sve mjesne zajednice' }}
                                    </td>

                                    <td class="py-4 text-center">
                                        <div class="font-semibold text-lg {{ $initiative->votes_count > 0 ? 'text-blue-600' : 'text-gray-400' }}">
                                            {{ $initiative->votes_count }}
                                        </div>
                                    </td>

                                    <td class="py-4 text-center">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                            ];
                                            $statusTexts = [
                                                'pending' => 'Na čekanju',
                                                'approved' => 'Odobreno',
                                                'rejected' => 'Odbijeno',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClasses[$initiative->status] ?? 'bg-gray-100' }}">
                                            {{ $statusTexts[$initiative->status] ?? ucfirst($initiative->status) }}
                                        </span>
                                    </td>

                                    <td class="py-4 text-center">
                                        @auth
                                            @if($initiative->status === 'approved' && !$initiative->votes->contains('user_id', auth()->id()))
                                                <form method="POST" action="{{ route('initiatives.vote', $initiative) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="vote-btn-active inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                                                        </svg>
                                                        Glasaj
                                                    </button>
                                                </form>
                                            @elseif($initiative->status === 'approved' && $initiative->votes->contains('user_id', auth()->id()))
                                                <span class="text-green-600 text-xs font-medium inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    Glasali ste
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs font-medium inline-flex items-center cursor-not-allowed">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                    </svg>
                                                    Nije dostupno
                                                </span>
                                            @endif
                                        @endauth
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-500">
                                        <p>Trenutno nema inicijativa.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if(method_exists($initiatives, 'links'))
                        <div class="mt-6">{{ $initiatives->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .vote-btn-active {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .vote-btn-active:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
        }
    </style>
    @endpush
</x-app-layout>