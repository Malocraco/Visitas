@extends('layouts.app')

@section('title', 'Calendario de Visitas - Sistema de Visitas')

@section('content')
<div class="flex items-center justify-between py-3 mb-6 border-b border-gray-200">
    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
        <i class="fas fa-calendar-check text-green-600 mr-2"></i>
        Calendario de Visitas
    </h1>
    <div></div>
</div>



<!-- Leyenda -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-info-circle mr-2 text-gray-500"></i>Leyenda
        </h5>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center">
                <div class="calendar-legend-item bg-red-600 mr-2"></div>
                <span class="text-gray-700">Días No Disponibles</span>
            </div>
            <div class="flex items-center">
                <div class="calendar-legend-item bg-yellow-500 mr-2"></div>
                <span class="text-gray-700">Días Agendados</span>
            </div>
            <div class="flex items-center">
                <div class="calendar-legend-item bg-green-600 mr-2"></div>
                <span class="text-gray-700">Días Disponibles</span>
            </div>
        </div>
    </div>
</div>

<!-- Calendario -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm">
    <div class="p-4">
        <div id="calendar"></div>
    </div>
    </div>

<!-- Modal de Detalles de Visita -->
<div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" id="visitModal">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto">
            <div class="flex items-center justify-between p-3 border-b border-gray-200">
                <h5 class="text-sm font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-eye mr-2 text-gray-600 text-xs"></i>Detalles de la Visita
                </h5>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="document.getElementById('visitModal').classList.add('hidden')">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="p-6" id="visitModalBody"></div>
            <div class="flex items-center justify-end gap-2 p-3 border-t border-gray-200">
                <button type="button" class="px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none" onclick="document.getElementById('visitModal').classList.add('hidden')">Cerrar</button>
                <a href="#" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none" id="viewDetailsBtn">
                    <i class="fas fa-external-link-alt mr-1.5 text-xs"></i>Ver Detalles
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<style>
.calendar-legend-item {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    display: inline-block;
}

 .fc-event {
     cursor: pointer;
     border: none;
     font-size: 0.9rem;
     font-weight: 600;
     border-radius: 4px;
     box-shadow: 0 2px 4px rgba(0,0,0,0.1);
 }

 /* Estilos para días con visitas agendadas */
 .fc-daygrid-day.has-visit {
     background-color: #ffc107 !important;
     cursor: pointer;
 }
 
 .fc-daygrid-day.has-visit .fc-daygrid-day-number {
     color: #856404 !important;
     font-weight: 700;
 }
 
.fc-event:hover {
    opacity: 0.8;
    transform: scale(1.02);
    transition: all 0.2s ease;
}

.fc-day-disabled {
    background-color: #f8f9fa !important;
    color: #6c757d !important;
}

.fc-day-disabled .fc-day-number {
    color: #6c757d !important;
}

.fc-toolbar-title {
    font-size: 1.5rem !important;
    font-weight: 600;
}

.fc-button-primary {
    background-color: #007bff !important;
    border-color: #007bff !important;
}

.fc-button-primary:hover {
    background-color: #0056b3 !important;
    border-color: #0056b3 !important;
}

 .fc-daygrid-day.fc-day-today {
     background-color: rgba(0, 123, 255, 0.15) !important;
     border: 2px solid #007bff !important;
 }
 
 .fc-daygrid-day {
     border: 1px solid #e9ecef !important;
 }
 
 .fc-daygrid-day:hover {
     background-color: rgba(0, 123, 255, 0.05) !important;
 }

 .fc-daygrid-day-number {
     font-weight: 600;
     font-size: 1.1rem;
     color: #333 !important;
 }
 
 .fc-day-disabled .fc-daygrid-day-number {
     color: #721c24 !important;
     font-weight: 700;
 }
 
 /* Forzar estilos para fines de semana */
 .fc-daygrid-day.fc-day-sun,
 .fc-daygrid-day.fc-day-sat {
     background-color: #f8d7da !important;
 }
 
 .fc-daygrid-day.fc-day-sun .fc-daygrid-day-number,
 .fc-daygrid-day.fc-day-sat .fc-daygrid-day-number {
     color: #721c24 !important;
     font-weight: 700;
 }
 
 .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
     color: #007bff !important;
     font-weight: 700;
 }

 .fc-event-title {
     font-weight: 600;
     color: #fff !important;
     text-shadow: 0 1px 2px rgba(0,0,0,0.3);
 }
 
 /* Estilos para días disponibles */
 .fc-daygrid-day.available-day .fc-daygrid-day-number {
     color: #155724 !important;
     font-weight: 700;
 }
 
 /* Mejorar contraste general */
 .fc-daygrid-day {
     min-height: 80px;
 }
 
 .fc-daygrid-day-number {
     padding: 4px 6px;
     border-radius: 3px;
     background-color: rgba(255, 255, 255, 0.8);
 }

/* Responsive */
@media (max-width: 768px) {
    .fc-toolbar {
        flex-direction: column;
        gap: 10px;
    }
    
    .fc-toolbar-chunk {
        display: flex;
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos de eventos
    const events = @json($events);
    const unavailableDays = @json($unavailableDays);
    
    // Crear array de días no disponibles para FullCalendar
    const disabledDates = unavailableDays.map(day => {
        return {
            start: day.date,
            end: day.date,
            display: 'background',
            color: '#dc3545', // Rojo para todos los días no disponibles
            title: day.reason,
            type: day.type
        };
    });
    
    // Combinar eventos y días no disponibles
    const allEvents = [...events, ...disabledDates];
    
    // Inicializar FullCalendar
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek'
        },
        events: allEvents,
        dayCellDidMount: function(arg) {
            // Primero verificar si hay visitas en este día
            const dateStr = arg.date.toISOString().split('T')[0];
            const visitEvent = events.find(event => {
                const eventDate = new Date(event.start);
                const eventDateStr = eventDate.toISOString().split('T')[0];
                return eventDateStr === dateStr && 
                       (event.extendedProps?.isVisit === true || event.isVisit === true || event.visit);
            });
            
            if (visitEvent) {
                // Colorear todo el día de amarillo si hay una visita
                arg.el.style.backgroundColor = '#ffc107';
                arg.el.style.color = '#856404';
                arg.el.classList.add('has-visit');
                
                // Asegurar que el número del día tenga buen contraste
                const dayNumber = arg.el.querySelector('.fc-daygrid-day-number');
                if (dayNumber) {
                    dayNumber.style.color = '#856404';
                    dayNumber.style.fontWeight = '700';
                }
                
                // Agregar evento de click para mostrar detalles de la visita
                arg.el.addEventListener('click', function() {
                    const visitData = visitEvent.visit || visitEvent.extendedProps?.visit;
                    if (visitData) {
                        showVisitModal(visitData);
                    }
                });
                
                // Tooltip con información de la visita
                const visitData = visitEvent.visit || visitEvent.extendedProps?.visit;
                if (visitData && visitData.user) {
                    arg.el.title = `Visita #${visitData.id} - ${visitData.user.name}`;
                } else {
                    arg.el.title = visitEvent.title || 'Visita agendada';
                }
                return; // No aplicar otras reglas si hay visita
            }
            
            // Resto del código para días sin visitas
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const currentDate = new Date(arg.date);
            currentDate.setHours(0, 0, 0, 0);
            const dayOfWeek = currentDate.getDay();
            const isWeekend = dayOfWeek === 0 || dayOfWeek === 6; // 0 = domingo, 6 = sábado
            
            // Verificar si es un día pasado (incluyendo días de meses anteriores)
            if (currentDate < today) {
                arg.el.classList.add('fc-day-disabled');
                arg.el.title = 'Día pasado';
                arg.el.style.backgroundColor = '#f8d7da';
                arg.el.style.color = '#721c24';
                
                // Agregar evento de click para días pasados
                arg.el.addEventListener('click', function() {
                    showUnavailableDayModal('Día pasado', arg.date);
                });
            }
            // Verificar si es un fin de semana (SIN IMPORTAR EL MES - siempre en rojo)
            else if (isWeekend) {
                arg.el.classList.add('fc-day-disabled');
                arg.el.title = 'Fin de semana';
                arg.el.style.backgroundColor = '#f8d7da';
                arg.el.style.color = '#721c24';
                
                // Agregar evento de click para fines de semana
                arg.el.addEventListener('click', function() {
                    showUnavailableDayModal('Fin de semana', arg.date);
                });
            }
            // Verificar si es un día no disponible del mes actual (festivos)
            else {
                const unavailableDay = unavailableDays.find(day => day.date === dateStr);
                
                if (unavailableDay) {
                    arg.el.classList.add('fc-day-disabled');
                    arg.el.title = unavailableDay.reason;
                    arg.el.style.backgroundColor = '#f8d7da';
                    arg.el.style.color = '#721c24';
                    
                    // Agregar evento de click para días festivos
                    arg.el.addEventListener('click', function() {
                        showUnavailableDayModal(unavailableDay.reason, arg.date);
                    });
                }
                // Marcar días disponibles (días futuros que no son fines de semana ni festivos)
                else if (currentDate >= today) {
                    // Verificar si no hay eventos en este día (solo visitas, no días no disponibles)
                    const hasEvents = events.some(event => {
                        const eventDate = new Date(event.start);
                        eventDate.setHours(0, 0, 0, 0);
                        return eventDate.getTime() === currentDate.getTime();
                    });
                    
                    if (!hasEvents) {
                        arg.el.style.backgroundColor = '#d4edda';
                        arg.el.style.color = '#155724';
                        arg.el.title = 'Día disponible para agendar';
                        arg.el.classList.add('available-day');
                    }
                }
            }
        },
        eventDidMount: function(info) {
            // Ocultar eventos de fondo de visitas ya que el día completo se colorea en dayCellDidMount
            if (info.event.display === 'background') {
                if (info.event.extendedProps && info.event.extendedProps.isVisit) {
                    // Ocultar completamente el evento ya que el día se colorea de fondo
                    info.el.style.display = 'none';
                } else {
                    // Días no disponibles se mantienen visibles pero con opacidad
                    info.el.style.opacity = '0.3';
                }
            }
        },

        height: 'auto',
        aspectRatio: 1.35,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        weekends: true,
        firstDay: 0, // Domingo
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana'
        }
    });
    
         calendar.render();
     
     // Forzar aplicación de estilos a fines de semana después del renderizado
     setTimeout(function() {
         const weekendCells = document.querySelectorAll('.fc-day-sun, .fc-day-sat');
         weekendCells.forEach(function(cell) {
             const dayNumber = cell.querySelector('.fc-daygrid-day-number');
             if (dayNumber) {
                 const currentDate = new Date(cell.getAttribute('data-date'));
                 const today = new Date();
                 today.setHours(0, 0, 0, 0);
                 currentDate.setHours(0, 0, 0, 0);
                 
                 // Solo aplicar si no es un día pasado
                 if (currentDate >= today) {
                     cell.style.backgroundColor = '#f8d7da';
                     cell.style.color = '#721c24';
                     dayNumber.style.color = '#721c24';
                     dayNumber.style.fontWeight = '700';
                     cell.title = 'Fin de semana';
                     
                     // Agregar evento de click
                     cell.addEventListener('click', function() {
                         showUnavailableDayModal('Fin de semana', currentDate);
                     });
                 }
             }
         });
     }, 100);
    
    // Función para mostrar modal de visita
    function showVisitModal(visit) {
        const modalBody = document.getElementById('visitModalBody');
        const viewDetailsBtn = document.getElementById('viewDetailsBtn');
        
        modalBody.innerHTML = `
            <div class="space-y-6">
                <!-- Información de la Visita -->
                <div>
                    <h6 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-calendar-check text-sky-600 mr-2"></i>
                        Información de la Visita
                    </h6>
                    <div class="space-y-3.5 pl-6">
                        <div class="flex items-start">
                            <span class="text-xs font-semibold text-gray-500 w-20 flex-shrink-0 uppercase tracking-wide">ID:</span>
                            <span class="text-sm text-gray-900 font-bold">#${visit.id}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-xs font-semibold text-gray-500 w-20 flex-shrink-0 uppercase tracking-wide">Estado:</span>
                            <span class="flex-1">${getStatusBadge(visit.status)}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-xs font-semibold text-gray-500 w-20 flex-shrink-0 uppercase tracking-wide">Fecha:</span>
                            <span class="text-sm text-gray-900">${formatDate(visit.preferred_date)}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-xs font-semibold text-gray-500 w-20 flex-shrink-0 uppercase tracking-wide">Hora:</span>
                            <span class="text-sm text-gray-900">${visit.preferred_start_time} - ${visit.preferred_end_time}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Separador -->
                <div class="border-t border-gray-200"></div>
                
                <!-- Información del Visitante -->
                <div>
                    <h6 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user text-sky-600 mr-2"></i>
                        Información del Visitante
                    </h6>
                    <div class="space-y-3.5 pl-6">
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Nombre:</span>
                            <p class="text-sm text-gray-900 font-medium">${visit.user.name}</p>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Email:</span>
                            <p class="text-sm text-gray-900 break-all">${visit.contact_email || 'N/A'}</p>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Teléfono:</span>
                            <p class="text-sm text-gray-900">${visit.contact_phone || 'N/A'}</p>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Institución:</span>
                            <p class="text-sm text-gray-900">${visit.institution_name || 'N/A'}</p>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Propósito:</span>
                            <p class="text-sm text-gray-900">${visit.visit_purpose || 'N/A'}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        viewDetailsBtn.href = `/admin/visits/${visit.id}/details`;
        viewDetailsBtn.style.display = 'inline-flex';
        
        document.getElementById('visitModal').classList.remove('hidden');
    }
    
    // Función para mostrar modal de día no disponible
    function showUnavailableDayModal(reason, date) {
        const modalBody = document.getElementById('visitModalBody');
        const viewDetailsBtn = document.getElementById('viewDetailsBtn');
        
        const dateObj = date instanceof Date ? date : new Date(date);
        const formattedDate = formatDate(dateObj);
        
        modalBody.innerHTML = `
            <div class="text-center py-2">
                <div class="flex justify-center mb-2">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-ban text-red-600"></i>
                    </div>
                </div>
                <h5 class="text-sm font-bold text-red-600 mb-1">Día No Disponible</h5>
                <p class="text-xs font-medium text-gray-700 mb-1">${reason}</p>
                <p class="text-xs text-gray-500 mb-2">${formattedDate}</p>
                <div class="mt-3 p-2 bg-blue-50 border border-blue-200 rounded-md">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-info-circle text-blue-600 mr-1.5 text-xs"></i>
                        <p class="text-xs text-blue-800">No se pueden programar visitas en este día.</p>
                    </div>
                </div>
            </div>
        `;
        
        viewDetailsBtn.style.display = 'none';
        
        document.getElementById('visitModal').classList.remove('hidden');
    }
    
    // Función para obtener badge de estado
    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i>Pendiente</span>',
            'approved': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check mr-1"></i>Aprobada</span>',
            'completed': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><i class="fas fa-flag-checkered mr-1"></i>Completada</span>',
            'rejected': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times mr-1"></i>Rechazada</span>',
            'postponed': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><i class="fas fa-calendar-times mr-1"></i>Pospuesta</span>'
        };
        return badges[status] || '';
    }
    
    // Función para formatear fecha
    function formatDate(dateInput) {
        const date = dateInput instanceof Date ? dateInput : new Date(dateInput);
        return date.toLocaleDateString('es-ES', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
});
</script>
@endpush
