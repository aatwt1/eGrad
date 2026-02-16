{{-- resources/views/admin/initiatives/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pregled svih inicijativa') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                &larr; Nazad na dashboard
            </a>
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

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm font-medium text-gray-500">Ukupno</div>
                    <div class="mt-2 text-3xl font-bold">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-yellow-50 rounded-lg shadow p-6 border-l-4 border-yellow-400">
                    <div class="text-sm font-medium text-yellow-800">Na čekanju</div>
                    <div class="mt-2 text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-green-50 rounded-lg shadow p-6 border-l-4 border-green-400">
                    <div class="text-sm font-medium text-green-800">Odobreno</div>
                    <div class="mt-2 text-3xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                </div>
                <div class="bg-red-50 rounded-lg shadow p-6 border-l-4 border-red-400">
                    <div class="text-sm font-medium text-red-800">Odbijeno</div>
                    <div class="mt-2 text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                </div>
                <div class="bg-purple-50 rounded-lg shadow p-6 border-l-4 border-purple-400">
                    <div class="text-sm font-medium text-purple-800">Implementirano</div>
                    <div class="mt-2 text-3xl font-bold text-purple-600">{{ $stats['implemented'] }}</div>
                </div>
            </div>

            <!-- Filteri -->
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <form method="GET" action="{{ route('admin.initiatives.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Pretraga -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pretraga</label>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Pretraži po naslovu, opisu ili kategoriji..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Filter po statusu -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Svi statusi</option>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sortiranje -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sortiraj po</label>
                            <select name="sort" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Datumu</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Naslovu</option>
                                <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Statusu</option>
                                <option value="comments_count" {{ request('sort') == 'comments_count' ? 'selected' : '' }}>Broju komentara</option>
                                <option value="supporters_count" {{ request('sort') == 'supporters_count' ? 'selected' : '' }}>Broju podržavalaca</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('admin.initiatives.index') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            Poništi filtere
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Primijeni filtere
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabela inicijativa -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Naslov</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Autor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategorija</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Podrška</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Komentari</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akcije</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($initiatives as $initiative)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    #{{ $initiative->id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('admin.initiatives.show', $initiative) }}" class="hover:text-blue-600">
                                            {{ Str::limit($initiative->title, 50) }}
                                        </a>
                                    </div>
                                    @if($initiative->description)
                                        <div class="text-sm text-gray-500">
                                            {{ Str::limit($initiative->description, 80) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $initiative->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $initiative->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        {{ $initiative->category ?? 'Opće' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'implemented' => 'bg-purple-100 text-purple-800',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Na čekanju',
                                            'approved' => 'Odobreno',
                                            'rejected' => 'Odbijeno',
                                            'implemented' => 'Implementirano',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $statusClasses[$initiative->status] ?? 'bg-gray-100' }}">
                                        {{ $statusLabels[$initiative->status] ?? $initiative->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ $initiative->supporters_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ $initiative->comments_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $initiative->created_at->format('d.m.Y.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <!-- Pregled -->
                                        <a href="{{ route('admin.initiatives.show', $initiative) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Pregled">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <!-- Akcije zavisno od statusa -->
                                        @if($initiative->status === 'pending')
                                            <form action="{{ route('admin.initiatives.approve', $initiative) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Odobri"
                                                        onclick="return confirm('Da li ste sigurni da želite odobriti ovu inicijativu?')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        @if($initiative->status === 'approved')
                                            <form action="{{ route('admin.initiatives.implemented', $initiative) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-purple-600 hover:text-purple-900" title="Označi kao implementirano"
                                                        onclick="return confirm('Da li ste sigurni da je ova inicijativa implementirana?')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-lg">Nema pronađenih inicijativa</p>
                                    <p class="text-sm">Trenutno nema inicijativa koje odgovaraju kriterijima pretrage.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Paginacija -->
                @if($initiatives->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $initiatives->links() }}
                    </div>
                @endif
            </div>

            <!-- Legenda -->
            <div class="mt-6 bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                <div class="flex items-center space-x-6">
                    <span class="font-medium">Legenda:</span>
                    <span class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-yellow-400 mr-2"></span>
                        Na čekanju - potrebna akcija
                    </span>
                    <span class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-green-400 mr-2"></span>
                        Odobreno - može se implementirati
                    </span>
                    <span class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-purple-400 mr-2"></span>
                        Implementirano - završeno
                    </span>
                </div>
            </div>

        </div>
    </div>

    <!-- Simple JavaScript only for reject modal -->
    @push('scripts')
    <script>
        // Ovo je minimalni JavaScript samo za modal za odbijanje
        function openRejectModal(initiativeId, title) {
            document.getElementById('rejectInitiativeId').value = initiativeId;
            document.getElementById('rejectInitiativeTitle').textContent = title;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectReason').value = '';
        }
    </script>
    @endpush
</x-app-layout>

{{-- Modal za odbijanje --}}
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
                <input type="hidden" id="rejectInitiativeId" name="initiative_id">
                
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">
                        Odbijate inicijativu: <span id="rejectInitiativeTitle" class="font-semibold"></span>
                    </p>
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-1">
                        Razlog odbijanja <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="rejection_reason"
                        name="rejection_reason" 
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