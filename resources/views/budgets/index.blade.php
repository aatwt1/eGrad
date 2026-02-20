<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Budžet općine – {{ $year }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Pregled ukupnog budžeta i raspodjele sredstava po kategorijama.
                </p>
            </div>

            {{-- GODINE --}}
            <div class="flex gap-2">
                <a href="{{ route('budgets.index', $previousYear) }}"
                   class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 text-sm font-medium">
                    ← {{ $previousYear }}
                </a>

                <a href="{{ route('budgets.index', $nextYear) }}"
                   class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 text-sm font-medium">
                    {{ $nextYear }} →
                </a>
            </div>
        </div>
    </x-slot>

    {{-- CONTENT --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(!$budget)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600">
                        Budžet za {{ $year }} još nije objavljen.
                    </p>
                </div>
            @else
                @php
                    // Izračunaj zbir svih kategorija
                    $totalAllocated = $budget->categories->sum('allocated_amount');
                    // Izračunaj razliku za 'Ostalo'
                    $otherAmount = $budget->total_amount - $totalAllocated;
                    // Dodaj 'Ostalo' kao posebnu kategoriju za prikaz
                    $allCategories = $budget->categories->toArray();
                    if ($otherAmount > 0) {
                        $allCategories[] = [
                            'category_name' => 'Ostalo',
                            'allocated_amount' => $otherAmount
                        ];
                    }
                @endphp

                {{-- SUMMARY STATS --}}
                <div class="grid md:grid-cols-3 gap-6 mb-10">
                    <div class="bg-white rounded-xl shadow p-6">
                        <p class="text-sm text-gray-500">Ukupni budžet</p>
                        <p class="text-2xl font-bold text-blue-700 mt-2">
                            {{ number_format($budget->total_amount, 2, ',', '.') }} KM
                        </p>
                    </div>

                    <div class="bg-white rounded-xl shadow p-6">
                        <p class="text-sm text-gray-500">Raspoređeno po kategorijama</p>
                        <p class="text-2xl font-bold text-green-700 mt-2">
                            {{ number_format($totalAllocated, 2, ',', '.') }} KM
                        </p>
                    </div>

                     <div class="bg-white rounded-xl shadow p-6">
                        <p class="text-sm text-gray-500">Napomena</p>
                        <p class="text-gray-700 mt-2">
                            {{ $budget->notes ?? 'Nema dodatnih napomena.' }}
                        </p>
                    </div>
                </div>

                {{-- CHART + TABLE --}}
                <div class="grid md:grid-cols-2 gap-8">
                    {{-- PIE CHART --}}
                    <div class="bg-white p-6 rounded-xl shadow">
                        <h3 class="text-lg font-semibold mb-4">
                            Raspodjela budžeta po kategorijama
                        </h3>
                        <div class="h-64">
                            <canvas id="budgetChart"></canvas>
                        </div>
                    </div>

                    {{-- TABLE --}}
                    <div class="bg-white p-6 rounded-xl shadow">
                        <h3 class="text-lg font-semibold mb-4">
                            Detalji po kategorijama
                        </h3>

                        <div class="overflow-x-auto max-h-96">
                            <table class="w-full text-sm">
                                <thead class="sticky top-0 bg-white">
                                    <tr class="border-b">
                                        <th class="text-left py-2 font-medium text-gray-500">Kategorija</th>
                                        <th class="text-right py-2 font-medium text-gray-500">Iznos (KM)</th>
                                        <th class="text-right py-2 font-medium text-gray-500">Udio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($budget->categories as $category)
                                    <tr class="border-b hover:bg-gray-50" 
                                        onmouseover="showTooltip(this, '{{ number_format($category->allocated_amount, 2, ',', '.') }} KM ({{ number_format(($category->allocated_amount / $budget->total_amount) * 100, 1) }}%)')"
                                        onmouseout="hideTooltip()">
                                        <td class="py-3 relative">
                                            <span class="inline-block w-3 h-3 rounded-full mr-2" 
                                                  style="background-color: {{ $chartColors[$loop->index] ?? '#3B82F6' }}"></span>
                                            {{ $category->category_name }}
                                        </td>
                                        <td class="py-3 text-right font-medium">
                                            {{ number_format($category->allocated_amount, 2, ',', '.') }}
                                        </td>
                                        <td class="py-3 text-right text-gray-500">
                                            @php
                                                $percentage = $budget->total_amount > 0 
                                                    ? ($category->allocated_amount / $budget->total_amount) * 100 
                                                    : 0;
                                            @endphp
                                            {{ number_format($percentage, 1) }}%
                                        </td>
                                    </tr>
                                    @endforeach

                                    {{-- OSTALO RED --}}
                                    @if($otherAmount > 0)
                                    <tr class="border-b bg-gray-50 font-medium" 
                                        onmouseover="showTooltip(this, '{{ number_format($otherAmount, 2, ',', '.') }} KM ({{ number_format(($otherAmount / $budget->total_amount) * 100, 1) }}%)')"
                                        onmouseout="hideTooltip()">
                                        <td class="py-3">
                                            <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: #9CA3AF"></span>
                                            <span class="text-gray-600">Ostalo (neraspoređeno)</span>
                                        </td>
                                        <td class="py-3 text-right font-medium text-orange-600">
                                            {{ number_format($otherAmount, 2, ',', '.') }}
                                        </td>
                                        <td class="py-3 text-right text-gray-500">
                                            {{ number_format(($otherAmount / $budget->total_amount) * 100, 1) }}%
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                                <tfoot class="sticky bottom-0 bg-white border-t-2 border-gray-300">
                                    <tr class="font-bold">
                                        <td class="py-3">UKUPNO</td>
                                        <td class="py-3 text-right text-blue-700">
                                            {{ number_format($budget->total_amount, 2, ',', '.') }}
                                        </td>
                                        <td class="py-3 text-right text-blue-700">100%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        {{-- LEGENDA --}}
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Legenda:</h4>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                @foreach($budget->categories as $index => $category)
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full mr-2" 
                                          style="background-color: {{ $chartColors[$index] ?? '#3B82F6' }}"></span>
                                    <span class="truncate">{{ $category->category_name }}</span>
                                </div>
                                @endforeach
                                @if($otherAmount > 0)
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-gray-400"></span>
                                    <span class="truncate">Ostalo (neraspoređeno)</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TOOLTIP ZA PRIKAZ --}}
                <div id="tooltip" class="fixed hidden bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none z-50">
                </div>

                {{-- DODATNE INFORMACIJE --}}
                <div class="mt-10 grid md:grid-cols-2 gap-8">
                    {{-- INFO BOX --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-6">
                        <h4 class="font-medium text-blue-800 mb-2">📊 Kako se koristi budžet?</h4>
                        <p class="text-blue-700 text-sm">
                            Budžet općine se raspoređuje kroz participativne procese u kojima učestvuju vlast te građani. 
                            Svaki gradanin može direktno predlažiti projekte kroz inicijative i glasati za projekte koji 
                            će se finansirati iz ove kategorije.
                        </p>
                    </div>

                    {{-- DOWNLOAD/EXPORT --}}
                    <div class="bg-white rounded-xl shadow p-6">
                        <h4 class="font-medium text-gray-800 mb-4">📥 Preuzmite podatke</h4>
                        <div class="flex flex-wrap gap-3">
                            <a href="#" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                PDF izvještaj
                            </a>
                            
                            <a href="#" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Excel podaci
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- TOOLTIP JAVASCRIPT --}}
    <script>
        function showTooltip(element, text) {
            const tooltip = document.getElementById('tooltip');
            tooltip.textContent = text;
            tooltip.classList.remove('hidden');
            
            const rect = element.getBoundingClientRect();
            tooltip.style.left = rect.left + window.scrollX + 'px';
            tooltip.style.top = (rect.top + window.scrollY - 30) + 'px';
        }

        function hideTooltip() {
            document.getElementById('tooltip').classList.add('hidden');
        }
    </script>

    {{-- CHART.JS --}}
    @if($budget)
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('budgetChart');
                
                // Pripremi podatke za chart 
                const chartLabels = {!! json_encode($budget->categories->pluck('category_name')) !!};
                const chartData = {!! json_encode($budget->categories->pluck('allocated_amount')) !!};
                
                @if($otherAmount > 0)
                    chartLabels.push('Ostalo');
                    chartData.push({{ $otherAmount }});
                @endif
                
                const chartColors = [
                    '#3B82F6', '#10B981', '#F59E0B', '#EF4444',
                    '#8B5CF6', '#EC4899', '#14B8A6', '#F97316',
                    '#6366F1', '#8B5CF6', '#EC4899', '#F97316',
                    '#3B82F6', '#10B981', '#F59E0B', '#EF4444',
                    '#9CA3AF' 
                ];
                
                const chart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            data: chartData,
                            backgroundColor: chartColors.slice(0, chartLabels.length),
                            borderWidth: 2,
                            borderColor: '#FFFFFF'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false 
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = {{ $budget->total_amount }};
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${label}: ${value.toLocaleString('bs-BA')} KM (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
                
                window.chartColors = chartColors;
            });
        </script>
        @endpush
        
        @push('styles')
        <style>
            canvas {
                max-width: 100%;
            }
            .sticky {
                position: sticky;
            }
        </style>
        @endpush
    @endif
</x-app-layout>