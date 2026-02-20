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
                <!-- Edit dugme -->
                <a href="{{ route('reported-issues.edit', $reportedIssue) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Uredi
                </a>
                
                <!-- Delete dugme -->
                <button onclick="openDeleteModal()"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Obriši
                </button>
                
                <!-- Nazad dugme -->
                <a href="{{ route('reported-issues.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                    ← Nazad
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
                                                @if($comment->user->isAdmin())
                                                    <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                        Administrator
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

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Potvrdi brisanje</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Da li ste sigurni da želite obrisati problem <span class="font-semibold">{{ $reportedIssue->title }}</span>?
                    </p>
                    @if($reportedIssue->comments->count() > 0)
                        <p class="text-xs text-red-500 mt-2">
                            ⚠️ Ovaj problem ima {{ $reportedIssue->comments->count() }} komentara koji će također biti obrisani.
                        </p>
                    @endif
                </div>
                <div class="flex justify-center space-x-4 px-4 py-3">
                    <button onclick="closeDeleteModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                        Odustani
                    </button>
                    <form id="deleteForm" method="POST" action="{{ route('reported-issues.destroy', $reportedIssue) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                            Obriši
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Zatvori modal ako se klikne izvan njega
    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target == modal) {
            closeDeleteModal();
        }
    }
    </script>

</x-app-layout>