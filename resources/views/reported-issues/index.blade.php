<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Prijavljeni problemi
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Pregled vaših prijavljenih problema i komunikacija s administracijom.
                </p>
            </div>

            <a href="{{ route('reported-issues.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                ➕ Prijavi novi problem
            </a>
        </div>
    </x-slot>

    {{-- CONTENT --}}
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filteri/Statusi -->
            <div class="mb-6 bg-white rounded-lg shadow p-4">
                <div class="flex flex-wrap gap-2">
                    <!-- Svi problemi -->
                    <a href="{{ route('reported-issues.index') }}" 
                       class="px-4 py-2 rounded-full text-sm {{ request('status') === null ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Svi ({{ $reportedIssues->count() }})
                    </a>
                    
                    <!-- Pojedinačni statusi - definisano direktno u view-u -->
                    @php
                        // Definišite statuse direktno ovdje (bez poziva iz modela)
                        $statuses = [
                            'pending' => 'Na čekanju',
                            'in_progress' => 'U toku',
                            'resolved' => 'Riješeno',
                            'closed' => 'Zatvoreno',
                            'rejected' => 'Odbijeno',
                        ];
                        
                        // Boje za statuse
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'in_progress' => 'bg-blue-100 text-blue-800',
                            'resolved' => 'bg-green-100 text-green-800',
                            'closed' => 'bg-gray-100 text-gray-800',
                            'rejected' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    
                    @foreach($statuses as $statusValue => $statusLabel)
                        @php
                            // Prebroj probleme za svaki status
                            $count = $reportedIssues->where('status', $statusValue)->count();
                        @endphp
                        <a href="{{ route('reported-issues.index', ['status' => $statusValue]) }}" 
                           class="px-4 py-2 rounded-full text-sm {{ request('status') == $statusValue ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $statusLabel }} ({{ $count }})
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Lista problema -->
            <div class="space-y-4">
                @forelse($reportedIssues as $issue)
                    <div class="bg-white shadow rounded-xl hover:shadow-md transition">
                        <div class="p-6">
                            <!-- Header row -->
                            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-4">
                                <div class="flex-1">
                                    <!-- Naslov sa linkom za detalje -->
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                        <a href="{{ route('reported-issues.show', $issue) }}" 
                                           class="hover:text-blue-600 hover:underline">
                                            {{ $issue->title }}
                                        </a>
                                    </h3>
                                    
                                    <!-- Kratki opis (prvih 150 karaktera) -->
                                    <p class="text-gray-600 text-sm line-clamp-2">
                                        {{ Str::limit($issue->description, 150) }}
                                    </p>
                                </div>
                                
                                <!-- Status badge -->
                                <div>
                                    @php
                                        // Odredite boju i tekst statusa
                                        $statusText = $statuses[$issue->status] ?? ucfirst($issue->status);
                                        $statusColor = $statusColors[$issue->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                                        {{ $statusText }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Meta informacije -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-100">
                                <!-- Lokacija i mjesna zajednica -->
                                <div class="space-y-1">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>{{ $issue->location ?? 'Lokacija nije navedena' }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span>{{ $issue->localCommunity->name ?? 'Nepoznata mjesna zajednica' }}</span>
                                    </div>
                                </div>
                                
                                <!-- Datum i vrijeme -->
                                <div class="space-y-1">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Prijavljeno: {{ $issue->created_at->format('d.m.Y.') }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ $issue->created_at->format('H:i') }}</span>
                                    </div>
                                </div>
                                
                                <!-- Akcije i broj komentara -->
                                <div class="flex items-center justify-between md:justify-end gap-4">
                                    <!-- Broj komentara -->
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        <span>{{ $issue->comments->count() }} {{ trans_choice('komentar|komentara|komentara', $issue->comments->count()) }}</span>
                                    </div>
                                    
                                    <!-- Dugme za detalje -->
                                    <a href="{{ route('reported-issues.show', $issue) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-100 transition">
                                        Detalji
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
    
                        </div>
                    </div>
                @empty
                    <!-- Prazna stanja -->
                    <div class="bg-white shadow rounded-xl p-8 text-center">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            @if(request('status'))
                                Nema problema sa statusom "{{ $statuses[request('status')] ?? request('status') }}"
                            @else
                                Nema prijavljenih problema
                            @endif
                        </h3>
                        <p class="text-gray-500 mb-6">
                            @if(request('status'))
                                <a href="{{ route('reported-issues.index') }}" class="text-blue-600 hover:underline">Pogledaj sve probleme</a>
                            @else
                                Prijavite svoj prvi problem!
                            @endif
                        </p>
                        @if(!request('status'))
                        <a href="{{ route('reported-issues.create') }}"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                            ➕ Prijavi prvi problem
                        </a>
                        @endif
                    </div>
                @endforelse
                
                <!-- Paginacija (ako koristite) -->
                @if(method_exists($reportedIssues, 'links'))
                    <div class="mt-6">
                        {{ $reportedIssues->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>