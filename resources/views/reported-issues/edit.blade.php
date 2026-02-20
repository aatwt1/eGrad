<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Uredi problem
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Izmijenite podatke za prijavljeni problem.
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
            <div class="bg-white shadow rounded-xl p-6">
                @php
                    $statuses = [
                        'pending' => 'Na čekanju',
                        'in_progress' => 'U toku',
                        'resolved' => 'Riješeno',
                        'closed' => 'Zatvoreno',
                        'rejected' => 'Odbijeno',
                    ];
                @endphp

                <form method="POST" action="{{ route('reported-issues.update', $reportedIssue) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Naziv -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            Naslov problema <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               value="{{ old('title', $reportedIssue->title) }}" 
                               required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Opis -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Opis problema <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="5"
                                  required
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('description', $reportedIssue->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" 
                                    id="status" 
                                    required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $reportedIssue->status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lokacija -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                                Lokacija
                            </label>
                            <input type="text" 
                                   name="location" 
                                   id="location" 
                                   value="{{ old('location', $reportedIssue->location) }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mjesna zajednica -->
                        <div>
                            <label for="local_community_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Mjesna zajednica
                            </label>
                            <select name="local_community_id" 
                                    id="local_community_id" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Nije odabrano</option>
                                @foreach($communities as $community)
                                    <option value="{{ $community->id }}" {{ old('local_community_id', $reportedIssue->local_community_id) == $community->id ? 'selected' : '' }}>
                                        {{ $community->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('local_community_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prilozi -->
                        <div>
                            <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1">
                                Dodaj priloge
                            </label>
                            <input type="file" 
                                   name="attachments[]" 
                                   id="attachments" 
                                   multiple
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('attachments.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Trenutni prilozi -->
                    @php
                        $attachmentsCount = 0;
                        if (is_array($reportedIssue->attachments)) {
                            $attachmentsCount = count($reportedIssue->attachments);
                        } elseif (isset($reportedIssue->attachments_count)) {
                            $attachmentsCount = $reportedIssue->attachments_count;
                        }
                    @endphp
                    
                    @if($attachmentsCount > 0)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Trenutni prilozi</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                @if(is_array($reportedIssue->attachments))
                                    @foreach($reportedIssue->attachments as $index => $attachment)
                                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                                            <span class="text-sm text-gray-600">
                                                {{ is_array($attachment) ? ($attachment['filename'] ?? 'Prilog') : 'Prilog' }}
                                            </span>
                                            <label class="flex items-center">
                                                <input type="checkbox" 
                                                       name="remove_attachments[]" 
                                                       value="{{ $index }}"
                                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                <span class="ml-2 text-xs text-red-600">Ukloni</span>
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Submit dugmad -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('reported-issues.show', $reportedIssue) }}" 
                           class="px-5 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            Odustani
                        </a>
                        <button type="submit"
                                class="px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Sačuvaj izmjene
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>