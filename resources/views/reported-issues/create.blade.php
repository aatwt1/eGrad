<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Prijavi novi problem
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Prijavite komunalni problem, kvar ili predložite poboljšanje u vašem naselju.
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
                    <form method="POST" action="{{ route('reported-issues.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-6">
                            <x-label for="title" value="Naziv problema *" />
                            <x-input 
                                id="title" 
                                class="block mt-1 w-full" 
                                type="text" 
                                name="title" 
                                :value="old('title')" 
                                required 
                                autofocus 
                                placeholder="Npr. Pokidana ograda na igralištu, neispravna ulična rasvjeta..."
                            />
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <x-label for="description" value="Opis problema *" />
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="6"
                                required
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="Detaljno opišite problem: šta se događa, kada ste primijetili, koji je uticaj na zajednicu..."
                            >{{ old('description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">
                                Molimo detaljno opišite problem. Što više informacija olakšava brže rješavanje.
                            </p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="mb-6">
                            <x-label for="location" value="Lokacija problema *" />
                            <x-input 
                                id="location" 
                                class="block mt-1 w-full" 
                                type="text" 
                                name="location" 
                                :value="old('location')" 
                                required 
                                placeholder="Npr. Ulica i broj, park, škola, specifična tačka..."
                            />
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Local Community (optional) -->
                        @if(isset($localCommunities) && $localCommunities->count() > 0)
                        <div class="mb-6">
                            <x-label for="local_community_id" value="Lokalna zajednica" />
                            <select 
                                id="local_community_id" 
                                name="local_community_id"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                                <option value="">Odaberite lokalnu zajednicu (opciono)</option>
                                @foreach($localCommunities as $community)
                                    <option value="{{ $community->id }}" {{ old('local_community_id') == $community->id ? 'selected' : '' }}>
                                        {{ $community->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('local_community_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif

                        <!-- Attachments -->
                        <div class="mb-8">
                            <x-label for="attachments" value="Prilozi (opciono)" />
                            <div class="mt-2">
                                <input 
                                    type="file" 
                                    id="attachments" 
                                    name="attachments[]" 
                                    multiple
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                >
                                <div class="mt-2 space-y-1">
                                    <p class="text-xs text-gray-500">
                                        Dozvoljeni formati: JPG, PNG, PDF, DOC, DOCX (max 5MB po fajlu)
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Možete dodati više fajlova (slike problema, dokumentaciju)
                                    </p>
                                </div>
                            </div>
                            @error('attachments.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between pt-6 border-t">
                            <div class="flex gap-3">
                                <a href="{{ route('reported-issues.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition">
                                    Otkaži
                                </a>
                                
                                <button type="submit" 
                                        class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                    </svg>
                                    Pošalji prijavu
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="mt-8 bg-blue-50 border border-blue-100 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-blue-800">Savjeti za kvalitetnu prijavu</h3>
                        <ul class="mt-2 space-y-2 text-sm text-blue-700">
                            <li class="flex items-start">
                                <svg class="h-4 w-4 mt-0.5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Budite što precizniji u opisu lokacije</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 mt-0.5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Dodajte fotografije ako je moguće - pomogne u identifikaciji</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 mt-0.5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Opisite uticaj problema na zajednicu</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 mt-0.5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Problemi se rješavaju u roku od 5-15 radnih dana</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- File Upload Preview Script -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('attachments');
            const fileList = document.createElement('div');
            fileList.className = 'mt-3 space-y-2';
            
            fileInput.parentNode.insertBefore(fileList, fileInput.nextSibling);
            
            fileInput.addEventListener('change', function() {
                fileList.innerHTML = '';
                
                if (this.files.length > 0) {
                    const heading = document.createElement('p');
                    heading.className = 'text-sm font-medium text-gray-700 mb-2';
                    heading.textContent = 'Odabrani fajlovi:';
                    fileList.appendChild(heading);
                    
                    for (let i = 0; i < this.files.length; i++) {
                        const file = this.files[i];
                        const fileItem = document.createElement('div');
                        fileItem.className = 'flex items-center justify-between bg-gray-50 p-2 rounded text-sm';
                        
                        const fileInfo = document.createElement('div');
                        fileInfo.className = 'flex items-center';
                        
                        const icon = document.createElement('span');
                        icon.className = 'mr-2 text-gray-500';
                        icon.innerHTML = getFileIcon(file.type);
                        
                        const fileName = document.createElement('span');
                        fileName.textContent = file.name;
                        
                        const fileSize = document.createElement('span');
                        fileSize.className = 'text-gray-500 ml-2';
                        fileSize.textContent = formatFileSize(file.size);
                        
                        fileInfo.appendChild(icon);
                        fileInfo.appendChild(fileName);
                        fileInfo.appendChild(fileSize);
                        
                        fileItem.appendChild(fileInfo);
                        fileList.appendChild(fileItem);
                    }
                }
            });
            
            function getFileIcon(mimeType) {
                if (mimeType.includes('image')) return '🖼️';
                if (mimeType.includes('pdf')) return '📄';
                if (mimeType.includes('word') || mimeType.includes('document')) return '📝';
                return '📎';
            }
            
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
    @endpush
</x-app-layout>