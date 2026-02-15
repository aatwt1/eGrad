<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Pokreni novu inicijativu
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Iskoristite svoje pravo da pokrenete promjene u svojoj zajednici.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('initiatives.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                    ← Nazad na listu
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('initiatives.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-6">
                            <x-label for="title" value="Naziv inicijative *" />
                            <x-input 
                                id="title" 
                                class="block mt-1 w-full" 
                                type="text" 
                                name="title" 
                                :value="old('title')" 
                                required 
                                autofocus 
                                placeholder="Npr. Izgradnja dječijeg igrališta u naselju Centar"
                            />
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <x-label for="description" value="Opis inicijative *" />
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="6"
                                required
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="Detaljno opišite svoju inicijativu: šta želite postići, zašto je to važno, kako će koristiti zajednici..."
                            >{{ old('description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">
                                Minimum 30 karaktera. Što detaljniji opis, veće šanse za podršku.
                            </p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Goal -->
                        <div class="mb-6">
                            <x-label for="goal" value="Cilj i očekivani rezultati - opciono" />
                            <textarea 
                                id="goal" 
                                name="goal" 
                                rows="4"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="Koji je konkretan cilj? Šta će se postići ako inicijativa uspije?"
                            >{{ old('goal') }}</textarea>
                            @error('goal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Local Community -->
                        <div class="mb-6">
                            <x-label for="local_community_id" value="Mjesna zajednica *" />
                            <select 
                                id="local_community_id" 
                                name="local_community_id"
                                required
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                                <option value="">Odaberite mjesnu zajednicu</option>
                                @foreach($localCommunities as $community)
                                    <option value="{{ $community->id }}" {{ old('local_community_id') == $community->id ? 'selected' : '' }}>
                                        {{ $community->name }}
                                    </option>
                                @endforeach
                                <option value="all">Sve mjesne zajednice (opća inicijativa)</option>
                            </select>
                            @error('local_community_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-6">
                            <x-label for="category" value="Kategorija *" />
                            <select 
                                id="category" 
                                name="category"
                                required
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                                <option value="">Odaberite kategoriju</option>
                                <option value="infrastructure" {{ old('category') == 'infrastructure' ? 'selected' : '' }}>Infrastruktura</option>
                                <option value="environment" {{ old('category') == 'environment' ? 'selected' : '' }}>Životna sredina</option>
                                <option value="sports" {{ old('category') == 'sports' ? 'selected' : '' }}>Sport i rekreacija</option>
                                <option value="culture" {{ old('category') == 'culture' ? 'selected' : '' }}>Kultura i umjetnost</option>
                                <option value="education" {{ old('category') == 'education' ? 'selected' : '' }}>Obrazovanje</option>
                                <option value="social" {{ old('category') == 'social' ? 'selected' : '' }}>Socijalna pitanja</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Ostalo</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estimated Budget -->
                        <div class="mb-6">
                            <x-label for="estimated_budget" value="Okvirni budžet (KM) - opciono" />
                            <x-input 
                                id="estimated_budget" 
                                class="block mt-1 w-full" 
                                type="number" 
                                name="estimated_budget" 
                                :value="old('estimated_budget')" 
                                placeholder="Npr. 5000"
                            />
                            @error('estimated_budget')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-3 pt-6 border-t">
                            <a href="{{ route('initiatives.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                                Otkaži
                            </a>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Pokreni inicijativu
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="mt-8 bg-blue-50 border border-blue-100 rounded-lg p-6">
                <h3 class="text-lg font-medium text-blue-800 mb-3">Prije nego što pokrenete inicijativu</h3>
                <ul class="space-y-3 text-sm text-blue-700">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Provjerite da li slična inicijativa već postoji</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Budite konkretni i realni u svojim zahtjevima</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Inicijativa mora biti u javnom interesu i u skladu sa zakonom</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Nakon pokretanja, inicijativa će biti pregledana od strane administratora</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- File Upload Script -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('attachments');
            const fileList = document.getElementById('file-list');
            const fileCount = document.getElementById('file-count');
            
            fileInput.addEventListener('change', function() {
                fileList.innerHTML = '';
                
                if (this.files.length > 0) {
                    fileCount.textContent = `Odabrano ${this.files.length} fajlova`;
                    
                    for (let i = 0; i < this.files.length; i++) {
                        const file = this.files[i];
                        const fileItem = document.createElement('div');
                        fileItem.className = 'flex items-center justify-between bg-gray-50 p-3 rounded-lg border border-gray-200';
                        
                        const fileInfo = document.createElement('div');
                        fileInfo.className = 'flex items-center flex-1';
                        
                        let icon = '📎';
                        if (file.type.includes('pdf')) icon = '📄';
                        else if (file.type.includes('word') || file.type.includes('document')) icon = '📝';
                        else if (file.type.includes('image')) icon = '🖼️';
                        
                        fileInfo.innerHTML = `
                            <span class="mr-3 text-lg">${icon}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                                <p class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</p>
                            </div>
                        `;
                        
                        fileItem.appendChild(fileInfo);
                        fileList.appendChild(fileItem);
                    }
                } else {
                    fileCount.textContent = 'Nije odabran nijedan fajl';
                }
            });
        });
    </script>
    @endpush
</x-app-layout>