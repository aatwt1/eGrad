<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard - Digitalna Općina</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">

    <!-- Top Navigation -->
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-blue-700">Digitalna Općina</span>
                    <span class="ml-4 px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                        Admin Panel
                    </span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                            Odjava
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Welcome Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Dobrodošli, {{ auth()->user()->name }}</h1>
                <p class="text-gray-600 mt-2">Pregled stanja u aplikaciji Digitalna Općina</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Korisnici -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Ukupno korisnika</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                            <p class="text-xs text-green-600">+{{ $stats['new_users_today'] }} danas</p>
                        </div>
                    </div>
                </div>

                <!-- Inicijative -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Inicijative</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_initiatives'] }}</p>
                            <div class="flex space-x-2 text-xs">
                                <span class="text-yellow-600">{{ $stats['pending_initiatives'] }} na čekanju</span>
                                <span class="text-green-600">{{ $stats['approved_initiatives'] }} odobreno</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prijavljeni problemi -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Prijavljeni problemi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_issues'] }}</p>
                            <div class="flex space-x-2 text-xs">
                                <span class="text-yellow-600">{{ $stats['pending_issues'] }} na čekanju</span>
                                <span class="text-green-600">{{ $stats['resolved_issues'] }} riješeno</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Budžet -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Budžet {{ date('Y') }}</p>
                            @if($stats['current_year_budget'])
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ number_format($stats['current_year_budget']->total_amount, 2, ',', '.') }} KM
                                </p>
                            @else
                                <p class="text-sm text-gray-500">Nije definisan</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Inicijative po mjesecima -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Inicijative po mjesecima</h3>
                    <div class="h-64">
                        <canvas id="initiativesChart"></canvas>
                    </div>
                </div>

                <!-- Problemi po statusu -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Prijavljeni problemi po statusu</h3>
                    <div class="h-64">
                        <canvas id="issuesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Items Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Najnovije inicijative -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Najnovije inicijative</h3>
                        <a href="#" class="text-sm text-blue-600 hover:underline">Pregled svih →</a>
                    </div>
                    <div class="space-y-3">
                        @foreach($latest_initiatives as $initiative)
                            <div class="border-b pb-2">
                                <p class="font-medium">{{ $initiative->title }}</p>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>{{ $initiative->user->name }}</span>
                                    <span class="px-2 py-1 rounded-full 
                                        @if($initiative->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($initiative->status == 'approved') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $initiative->status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Najnoviji problemi -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Najnoviji problemi</h3>
                        <a href="#" class="text-sm text-blue-600 hover:underline">Pregled svih →</a>
                    </div>
                    <div class="space-y-3">
                        @foreach($latest_issues as $issue)
                            <div class="border-b pb-2">
                                <p class="font-medium">{{ $issue->title }}</p>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>{{ $issue->user->name }}</span>
                                    <span class="px-2 py-1 rounded-full 
                                        @if($issue->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($issue->status == 'resolved') bg-green-100 text-green-800
                                        @elseif($issue->status == 'in_progress') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $issue->status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Nedavne aktivnosti -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Nedavne aktivnosti</h3>
                    <div class="space-y-3">
                        @foreach($recent_activities as $activity)
                            <div class="border-b pb-2">
                                <div class="flex items-center">
                                    @if($activity['type'] == 'initiative')
                                        <span class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center text-green-600 mr-2">📋</span>
                                    @elseif($activity['type'] == 'issue')
                                        <span class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 mr-2">⚠️</span>
                                    @else
                                        <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mr-2">👤</span>
                                    @endif
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">
                                            @if($activity['type'] == 'user')
                                                Novi korisnik: {{ $activity['title'] }}
                                            @else
                                                {{ $activity['title'] }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $activity['created_at']->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Mjesne zajednice -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Mjesne zajednice - Statistika</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Naziv</th>
                                <th class="text-center py-2">Broj inicijativa</th>
                                <th class="text-center py-2">Broj prijavljenih problema</th>
                                <th class="text-right py-2">Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($communities as $community)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3">{{ $community->name }}</td>
                                    <td class="text-center py-3">{{ $community->initiatives_count }}</td>
                                    <td class="text-center py-3">{{ $community->reported_issues_count }}</td>
                                    <td class="text-right py-3">
                                        <a href="#" class="text-blue-600 hover:underline text-sm">Pregled</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicijative po mjesecima
            const ctx1 = document.getElementById('initiativesChart');
            if (ctx1) {
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($initiativesByMonth->map(function($item) { 
                            return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT); 
                        })) !!},
                        datasets: [{
                            label: 'Broj inicijativa',
                            data: {!! json_encode($initiativesByMonth->pluck('total')) !!},
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            // Problemi po statusu
            const ctx2 = document.getElementById('issuesChart');
            if (ctx2) {
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: ['Na čekanju', 'U toku', 'Riješeno', 'Odbijeno'],
                        datasets: [{
                            data: [
                                {{ $issuesByStatus['pending'] }},
                                {{ $issuesByStatus['in_progress'] }},
                                {{ $issuesByStatus['resolved'] }},
                                {{ $issuesByStatus['rejected'] }}
                            ],
                            backgroundColor: ['#F59E0B', '#3B82F6', '#10B981', '#EF4444']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        });
    </script>
</body>
</html>