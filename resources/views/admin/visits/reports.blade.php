@extends('layouts.app')

@section('title', 'Reportes - Sistema de Visitas')

@section('content')
<div class="flex items-center justify-between py-3 mb-6 border-b border-gray-200">
    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
        <i class="fas fa-chart-bar text-sky-600 mr-2"></i>
        Reportes y Estadísticas
    </h1>
    <div></div>
</div>

<!-- Filtros de Reporte -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-filter mr-2 text-gray-500"></i>Filtros de Reporte
        </h5>
    </div>
    <div class="px-6 py-5">
        <form method="GET" action="{{ route('admin.visits.reports') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label for="report_type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Reporte</label>
                <select class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-gray-900" id="report_type" name="report_type">
                    <option value="general" {{ request('report_type') == 'general' ? 'selected' : '' }}>General</option>
                    <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Mensual</option>
                    <option value="institution" {{ request('report_type') == 'institution' ? 'selected' : '' }}>Por Institución</option>
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                <input type="date" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-gray-900" id="date_from" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                <input type="date" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-gray-900" id="date_to" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div>
                <label for="institution_type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Institución</label>
                <select class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white text-gray-900" id="institution_type" name="institution_type">
                    <option value="">Todos</option>
                    <option value="empresa" {{ request('institution_type') == 'empresa' ? 'selected' : '' }}>Empresa</option>
                    <option value="universidad" {{ request('institution_type') == 'universidad' ? 'selected' : '' }}>Universidad</option>
                    <option value="colegio" {{ request('institution_type') == 'colegio' ? 'selected' : '' }}>Colegio</option>
                    <option value="otro" {{ request('institution_type') == 'otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>
            <div class="sm:col-span-2 lg:col-span-4 flex items-center space-x-3 mt-1">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition">
                    <i class="fas fa-search mr-2"></i>Generar Reporte
                </button>
                <a href="{{ route('admin.visits.reports') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition">
                    <i class="fas fa-times mr-2"></i>Limpiar
                </a>
            </div>
        </form>
    </div>
</div>



<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-pie-chart mr-2 text-gray-500"></i>Visitas por Estado
            </h5>
        </div>
        <div class="p-6">
            <div class="h-64"><canvas id="statusChart"></canvas></div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-chart-line mr-2 text-gray-500"></i>Visitas por Mes (Últimos 12 meses)
            </h5>
        </div>
        <div class="p-6">
            <div class="h-64"><canvas id="monthlyChart"></canvas></div>
        </div>
    </div>
</div>

<!-- Top Instituciones Visitantes -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-trophy mr-2 text-amber-500"></i>Top 10 Instituciones Visitantes
            </h5>
        </div>
        <div class="p-6">
                @if($topInstitutions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Institución</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Total Visitas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($topInstitutions as $index => $institution)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($index == 0)
                                            <i class="fas fa-medal text-yellow-500"></i>
                                        @elseif($index == 1)
                                            <i class="fas fa-medal text-gray-400"></i>
                                        @elseif($index == 2)
                                            <i class="fas fa-medal text-amber-700"></i>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="font-semibold">{{ $institution->user->institution_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $institution->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($institution->user->institution_type) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="font-semibold">{{ $institution->total }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="w-full bg-gray-100 rounded-full h-5 overflow-hidden">
                                            @php
                                                $percentage = ($institution->total / $totalVisits) * 100;
                                            @endphp
                                            <div class="h-5 bg-sky-500 text-white text-xs font-semibold flex items-center justify-center" style="width: {{ $percentage }}%">
                                                {{ number_format($percentage, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar fa-3x text-gray-400 mb-3"></i>
                        <p class="text-gray-500">No hay datos suficientes para mostrar estadísticas.</p>
                    </div>
                @endif
        </div>
    </div>

    <!-- Resumen de Estados -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-list mr-2 text-gray-500"></i>Resumen por Estado
            </h5>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($visitsByStatus as $status)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center">
                        @switch($status->status)
                            @case('pending')
                                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-clock text-yellow-600"></i>
                                </div>
                                <span class="font-medium text-gray-700">Pendientes</span>
                                @break
                            @case('approved')
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                                <span class="font-medium text-gray-700">Aprobadas</span>
                                @break
                            @case('completed')
                                <div class="w-10 h-10 rounded-full bg-sky-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-flag-checkered text-sky-600"></i>
                                </div>
                                <span class="font-medium text-gray-700">Completadas</span>
                                @break
                            @case('rejected')
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-times text-red-600"></i>
                                </div>
                                <span class="font-medium text-gray-700">Rechazadas</span>
                                @break
                        @endswitch
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-gray-900">{{ $status->total }}</div>
                        <div class="text-xs text-gray-500">({{ number_format(($status->total / $totalVisits) * 100, 1) }}%)</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para el gráfico de estados
    const statusData = @json($visitsByStatus);
    const statusLabels = [];
    const statusValues = [];
    const statusColors = ['#ffc107', '#28a745', '#17a2b8', '#dc3545'];

    statusData.forEach((item, index) => {
        let label = '';
        switch(item.status) {
            case 'pending': label = 'Pendientes'; break;
            case 'approved': label = 'Aprobadas'; break;
            case 'completed': label = 'Completadas'; break;
            case 'rejected': label = 'Rechazadas'; break;
        }
        statusLabels.push(label);
        statusValues.push(item.total);
    });

    // Gráfico de estados
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusValues,
                backgroundColor: statusColors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Datos para el gráfico mensual
    const monthlyData = @json($visitsByMonth);
    const monthlyLabels = [];
    const monthlyValues = [];

    monthlyData.forEach(item => {
        const monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 
                           'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        monthlyLabels.push(`${monthNames[item.month - 1]} ${item.year}`);
        monthlyValues.push(item.total);
    });

    // Gráfico mensual
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Visitas',
                data: monthlyValues,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
