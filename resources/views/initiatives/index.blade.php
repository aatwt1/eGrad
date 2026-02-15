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

            {{-- DUGME ZA KREIRANJE NOVE INICIJATIVE --}}
            <a href="{{ route('initiatives.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova inicijativa
            </a>
        </div>
    </x-slot>

    {{-- CONTENT --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filteri i pretraga -->
            <div class="mb-6 bg-white rounded-lg shadow p-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('initiatives.index') }}" 
                           class="px-4 py-2 rounded-full text-sm {{ request('status') === null ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Sve ({{ $initiatives->count() }})
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
                    
                    <!-- Pretraga (opciono) -->
                    <div class="relative">
                        <input type="text" 
                               placeholder="Pretraži inicijative..." 
                               class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b text-gray-600">
                                <th class="text-left py-3">Naziv</th>
                                <th class="text-left py-3">Mjesna zajednica</th>
                                <th class="text-center py-3">Glasovi</th>
                                <th class="text-center py-3">Status</th>
                                <th class="text-right py-3">Akcije</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @forelse($initiatives as $initiative)
                                <tr class="hover:bg-gray-50">
                                    {{-- TITLE --}}
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

                                    {{-- LOCAL COMMUNITY --}}
                                    <td class="py-4">
                                        {{ $initiative->localCommunity->name ?? 'Sva mjesne zajednice' }}
                                    </td>

                                    {{-- VOTES --}}
                                    <td class="py-4 text-center">
                                        <div class="font-semibold text-lg {{ $initiative->votes->count() > 0 ? 'text-blue-600' : 'text-gray-400' }}">
                                            {{ $initiative->votes->count() }}
                                        </div>
                                    </td>

                                    {{-- STATUS --}}
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

                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                            {{ $statusClasses[$initiative->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $statusTexts[$initiative->status] ?? ucfirst($initiative->status) }}
                                        </span>
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="py-4 text-right space-x-3">
                                        <a href="{{ route('initiatives.show', $initiative) }}"
                                           class="text-blue-600 hover:underline text-sm">
                                            Detalji
                                        </a>

                                        @can('vote', $initiative)
                                            <form method="POST" action="{{ route('initiatives.vote', $initiative) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-green-600 hover:underline text-sm">
                                                    Glasaj
                                                </button>
                                            </form>
                                        @endcan

                                        @can('approve_initiative')
                                            <a href="{{ route('initiatives.edit', $initiative) }}"
                                               class="text-indigo-600 hover:underline text-sm">
                                                Uredi
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('initiatives.destroy', $initiative) }}"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    onclick="return confirm('Jeste li sigurni da želite obrisati ovu inicijativu?')"
                                                    class="text-red-600 hover:underline text-sm">
                                                    Obriši
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-gray-500 mb-4">Trenutno nema inicijativa.</p>
                                            <a href="{{ route('initiatives.create') }}" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Pokreni prvu inicijativu
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Paginacija -->
                    @if(method_exists($initiatives, 'links'))
                        <div class="mt-6">
                            {{ $initiatives->links() }}
                        </div>
                    @endif

                </div>
            </div>

            <!-- Info card -->
            <div class="mt-6 bg-blue-50 border border-blue-100 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800">Kako funkcionišu inicijative?</h4>
                        <p class="text-sm text-blue-600 mt-1">
                            Svaki građanin može pokrenuti novu inicijativu. Inicijativa prvo ide na odobrenje, 
                            a nakon toga građani mogu glasati. Inicijative sa najviše glasova ulaze u proces realizacije.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>