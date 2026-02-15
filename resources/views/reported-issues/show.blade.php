<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detalji problema
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Pregled i komunikacija za prijavljeni problem.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('reported-issues.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                    ← Nazad na listu
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @php
                // Definišite statuse ovdje
                $statuses = [
                    'pending' => 'Na čekanju',
                    'in_progress' => 'U toku',
                    'resolved' => 'Riješeno',
                    'closed' => 'Zatvoreno',
                    'rejected' => 'Odbijeno',
                ];
                
                $statusClasses = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'in_progress' => 'bg-blue-100 text-blue-800',
                    'resolved' => 'bg-green-100 text-green-800',
                    'closed' => 'bg-gray-100 text-gray-800',
                    'rejected' => 'bg-red-100 text-red-800',
                ];
            @endphp

            <!-- Glavni problem -->
            <div class="bg-white shadow rounded-xl mb-8">
                <!-- Header -->
                <div class="border-b border-gray-100 p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                                {{ $reportedIssue->title }}
                            </h1>
                            
                            <!-- Status i ID -->
                            <div class="flex flex-wrap items-center gap-4">
                                <span class="px-4 py-1.5 rounded-full text-sm font-semibold {{ $statusClasses[$reportedIssue->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statuses[$reportedIssue->status] ?? ucfirst($reportedIssue->status) }}
                                </span>
                                
                                <span class="text-sm text-gray-500">
                                    ID: #{{ $reportedIssue->id }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Detalji problema -->
                <div class="p-6">
                    <!-- Opis -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Opis problema</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $reportedIssue->description }}</p>
                        </div>
                    </div>
                    
                    <!-- Informacije grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Lokacija -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Lokacija</h4>
                            <div class="flex items-center text-gray-800">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $reportedIssue->location ?? 'Lokacija nije navedena' }}</span>
                            </div>
                        </div>
                        
                        <!-- Mjesna zajednica -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Mjesna zajednica</h4>
                            <div class="flex items-center text-gray-800">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span>{{ $reportedIssue->localCommunity->name ?? 'Nepoznata mjesna zajednica' }}</span>
                            </div>
                        </div>
                        
                        <!-- Datum prijave -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Datum prijave</h4>
                            <div class="flex items-center text-gray-800">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $reportedIssue->created_at->format('d.m.Y. H:i') }}</span>
                            </div>
                        </div>
                        
                        <!-- Korisnik -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Prijavio</h4>
                            <div class="flex items-center text-gray-800">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>{{ $reportedIssue->user->name }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Prilozi -->
                    @php
                        // Provjerite broj attachmenta na siguran način
                        $attachmentsCount = 0;
                        if (is_array($reportedIssue->attachments)) {
                            $attachmentsCount = count($reportedIssue->attachments);
                        } elseif (isset($reportedIssue->attachments_count)) {
                            $attachmentsCount = $reportedIssue->attachments_count;
                        }
                    @endphp
                    
                    @if($attachmentsCount > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Prilozi</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @if(is_array($reportedIssue->attachments))
                                    @foreach($reportedIssue->attachments as $attachment)
                                        <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition">
                                            <div class="flex items-center">
                                                @php
                                                    $icon = '📎';
                                                    $filename = is_array($attachment) ? ($attachment['filename'] ?? 'Prilog') : 'Prilog';
                                                @endphp
                                                
                                                <span class="text-lg mr-2">{{ $icon }}</span>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate" title="{{ $filename }}">
                                                        {{ $filename }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Komentari -->
            <div class="bg-white shadow rounded-xl">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Komentari
                        <span class="text-sm font-normal text-gray-500 ml-2">
                            ({{ $reportedIssue->comments->count() }})
                        </span>
                    </h3>
                </div>
                
                <div class="p-6">
                    <!-- Lista komentara -->
                    <div class="space-y-6 mb-8">
                        @forelse($reportedIssue->comments as $comment)
                            <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold mr-3">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center">
                                                <span class="font-medium text-gray-900">
                                                    {{ $comment->user->name }}
                                                </span>
                                                @if($comment->user->hasRole('admin'))
                                                    <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                        administrator
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-xs text-gray-500">
                                                {{ $comment->created_at->format('d.m.Y. H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-gray-700 whitespace-pre-line">
                                    {{ $comment->content }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <p class="mt-2">Nema komentara za ovaj problem.</p>
                                <p class="text-sm">Budite prvi koji će ostaviti komentar!</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Forma za novi komentar -->
                    <div class="border-t border-gray-100 pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Dodaj komentar</h4>
                        
                        <form method="POST" action="{{ route('reported-issues.comments.store', $reportedIssue) }}">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                                    Vaš komentar
                                </label>
                                <textarea 
                                    id="content"
                                    name="content"
                                    rows="4"
                                    required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    placeholder="Napišite svoj komentar ovdje..."
                                ></textarea>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit"
                                        class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    Pošalji komentar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>