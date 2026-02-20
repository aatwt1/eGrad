<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Brisanje problema
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Potvrdite brisanje prijavljenog problema.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('reported-issues.show', $reportedIssue) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                    ← Nazad na detalje
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Warning Card -->
            <div class="bg-white shadow rounded-xl overflow-hidden">
                <div class="bg-red-50 px-6 py-4 border-b border-red-100">
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h2 class="ml-3 text-lg font-medium text-red-800">Upozorenje: Ova akcija je nepovratna!</h2>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <p class="text-gray-700 mb-4">
                        Da li ste sigurni da želite obrisati problem <strong>"{{ $reportedIssue->title }}"</strong>?
                    </p>

                    <!-- Problem Details -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Naslov</dt>
                                <dd class="text-sm text-gray-900">{{ $reportedIssue->title }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="text-sm">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'text-yellow-800 bg-yellow-100',
                                            'in_progress' => 'text-blue-800 bg-blue-100',
                                            'resolved' => 'text-green-800 bg-green-100',
                                            'closed' => 'text-gray-800 bg-gray-100',
                                            'rejected' => 'text-red-800 bg-red-100',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Na čekanju',
                                            'in_progress' => 'U toku',
                                            'resolved' => 'Riješeno',
                                            'closed' => 'Zatvoreno',
                                            'rejected' => 'Odbijeno',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs {{ $statusClasses[$reportedIssue->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$reportedIssue->status] ?? $reportedIssue->status }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Datum prijave</dt>
                                <dd class="text-sm text-gray-900">{{ $reportedIssue->created_at->format('d.m.Y. H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Prijavio</dt>
                                <dd class="text-sm text-gray-900">{{ $reportedIssue->user->name }}</dd>
                            </div>
                            @if($reportedIssue->location)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Lokacija</dt>
                                <dd class="text-sm text-gray-900">{{ $reportedIssue->location }}</dd>
                            </div>
                            @endif
                            @if($reportedIssue->localCommunity)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Mjesna zajednica</dt>
                                <dd class="text-sm text-gray-900">{{ $reportedIssue->localCommunity->name }}</dd>
                            </div>
                            @endif
                            @if($reportedIssue->description)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Opis</dt>
                                <dd class="text-sm text-gray-900 mt-1 bg-white p-3 rounded border border-gray-200">
                                    {{ $reportedIssue->description }}
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-white border rounded-lg p-4 text-center">
                            <span class="block text-2xl font-bold text-gray-900">{{ $reportedIssue->comments->count() }}</span>
                            <span class="text-sm text-gray-500">Komentara</span>
                        </div>
                        
                        @php
                            $attachmentsCount = 0;
                            if (is_array($reportedIssue->attachments)) {
                                $attachmentsCount = count($reportedIssue->attachments);
                            } elseif (isset($reportedIssue->attachments_count)) {
                                $attachmentsCount = $reportedIssue->attachments_count;
                            }
                        @endphp
                        
                        <div class="bg-white border rounded-lg p-4 text-center">
                            <span class="block text-2xl font-bold text-gray-900">{{ $attachmentsCount }}</span>
                            <span class="text-sm text-gray-500">Priloga</span>
                        </div>
                        
                        <div class="bg-white border rounded-lg p-4 text-center">
                            <span class="block text-2xl font-bold text-gray-900">
                                {{ $reportedIssue->updated_at->diffForHumans() }}
                            </span>
                            <span class="text-sm text-gray-500">Zadnja izmjena</span>
                        </div>
                    </div>

                    <!-- Related Items Warning -->
                    @if($reportedIssue->comments->count() > 0 || $attachmentsCount > 0)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Napomena:</strong> Brisanjem ovog problema također ćete obrisati:
                                    </p>
                                    <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside">
                                        @if($reportedIssue->comments->count() > 0)
                                            <li>{{ $reportedIssue->comments->count() }} komentara</li>
                                        @endif
                                        @if($attachmentsCount > 0)
                                            <li>{{ $attachmentsCount }} priloga (fotografije, dokumenti...)</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Delete Confirmation Form -->
                    <form method="POST" action="{{ route('reported-issues.destroy', $reportedIssue) }}" class="flex justify-end space-x-3">
                        @csrf
                        @method('DELETE')
                        
                        <a href="{{ route('reported-issues.show', $reportedIssue) }}" 
                           class="px-5 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            Odustani
                        </a>
                        
                        <div class="relative group">
                            <button type="submit"
                                    class="px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Potvrdi brisanje
                            </button>
                            
                            <!-- Tooltip za dodatno upozorenje -->
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
                                ⚠️ Ova akcija se ne može poništiti
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Komentari (Preview) -->
            @if($reportedIssue->comments->count() > 0)
                <div class="mt-6 bg-white shadow rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Pregled komentara koji će biti obrisani
                        <span class="text-sm font-normal text-gray-500 ml-2">
                            ({{ $reportedIssue->comments->count() }})
                        </span>
                    </h3>
                    
                    <div class="space-y-3 max-h-60 overflow-y-auto">
                        @foreach($reportedIssue->comments->take(5) as $comment)
                            <div class="border border-gray-100 rounded-lg p-3">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-medium text-sm">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at->format('d.m.Y. H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $comment->content }}</p>
                            </div>
                        @endforeach
                        
                        @if($reportedIssue->comments->count() > 5)
                            <p class="text-xs text-gray-500 text-center pt-2">
                                i još {{ $reportedIssue->comments->count() - 5 }} komentara...
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Prilozi (Preview) -->
            @if($attachmentsCount > 0 && is_array($reportedIssue->attachments))
                <div class="mt-6 bg-white shadow rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Pregled priloga koji će biti obrisani
                        <span class="text-sm font-normal text-gray-500 ml-2">
                            ({{ $attachmentsCount }})
                        </span>
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(array_slice($reportedIssue->attachments, 0, 4) as $attachment)
                            <div class="border border-gray-200 rounded-lg p-2 text-center">
                                <div class="text-2xl mb-1">
                                    @php
                                        $mime = $attachment['mime_type'] ?? '';
                                        $icon = '📎';
                                        if (strpos($mime, 'image/') === 0) $icon = '🖼️';
                                        elseif (strpos($mime, 'pdf') !== false) $icon = '📄';
                                        elseif (strpos($mime, 'word') !== false) $icon = '📝';
                                    @endphp
                                    {{ $icon }}
                                </div>
                                <p class="text-xs text-gray-600 truncate" title="{{ $attachment['filename'] ?? 'Prilog' }}">
                                    {{ $attachment['filename'] ?? 'Prilog' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($attachmentsCount > 4)
                        <p class="text-xs text-gray-500 text-center mt-3">
                            i još {{ $attachmentsCount - 4 }} priloga...
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>