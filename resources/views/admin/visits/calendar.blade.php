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

<!-- Modal para Editar Día del Calendario (Solo SuperAdmin) -->
@if($isSuperAdmin)
<div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" id="editDayModal">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-lg mx-auto">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-edit mr-2 text-blue-600"></i>Editar Día del Calendario
                </h5>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeEditDayModal()">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Fecha seleccionada:</p>
                    <p class="text-lg font-semibold text-gray-900" id="selectedDateDisplay"></p>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Selecciona una opción:</label>
                    <div class="space-y-3">
                        <button type="button" onclick="selectDayOption('available')" class="w-full px-4 py-3 border-2 border-green-300 rounded-lg text-left hover:bg-green-50 transition-colors" id="btnAvailable">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-green-600 mr-3"></div>
                                <div>
                                    <div class="font-semibold text-gray-900">Hacer Disponible</div>
                                    <div class="text-sm text-gray-500">El día estará disponible para agendar visitas</div>
                                </div>
                            </div>
                        </button>
                        <button type="button" onclick="selectDayOption('unavailable')" class="w-full px-4 py-3 border-2 border-red-300 rounded-lg text-left hover:bg-red-50 transition-colors" id="btnUnavailable">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-red-600 mr-3"></div>
                                <div>
                                    <div class="font-semibold text-gray-900">Marcar como No Disponible</div>
                                    <div class="text-sm text-gray-500">El día aparecerá en rojo y no se podrán agendar visitas</div>
                                </div>
                            </div>
                        </button>
                        <button type="button" onclick="selectDayOption('scheduled')" class="w-full px-4 py-3 border-2 border-yellow-300 rounded-lg text-left hover:bg-yellow-50 transition-colors" id="btnScheduled">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full bg-yellow-500 mr-3"></div>
                                <div>
                                    <div class="font-semibold text-gray-900">Agendar Día</div>
                                    <div class="text-sm text-gray-500">Completa el formulario para agendar una visita</div>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
                
                <!-- Botones para Hacer Disponible -->
                <div id="availableActions" class="hidden">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-green-800">¿Estás seguro de hacer este día disponible?</p>
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" onclick="closeEditDayModal()" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                            Cancelar
                        </button>
                        <button type="button" onclick="makeDayAvailable()" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                            Hacer Disponible
                        </button>
                    </div>
                </div>
                
                <!-- Formulario para Agendar -->
                <div id="scheduleForm" class="hidden">
                    <form id="scheduleDayForm" onsubmit="saveCalendarDay(event)">
                        <input type="hidden" id="selectedDate" name="date">
                        <input type="hidden" id="selectedStatus" name="status" value="scheduled">
                        
                        <div class="space-y-4">
                            <div>
                                <label for="institution_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Institución *</label>
                                <input type="text" id="institution_name" name="institution_name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ej: Universidad Nacional, Empresa XYZ, Colegio ABC">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="entry_time" class="block text-sm font-medium text-gray-700 mb-1">Hora de Entrada *</label>
                                    <input type="time" id="entry_time" name="entry_time" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="exit_time" class="block text-sm font-medium text-gray-700 mb-1">Hora de Salida *</label>
                                    <input type="time" id="exit_time" name="exit_time" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas (opcional)</label>
                                <textarea id="notes" name="notes" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Información adicional sobre la visita..."></textarea>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end gap-3 mt-6">
                            <button type="button" onclick="closeEditDayModal()" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                Cancelar
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Botones para No Disponible -->
                <div id="unavailableActions" class="hidden">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-red-800" id="unavailableMessage">¿Estás seguro de marcar este día como no disponible?</p>
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" onclick="closeEditDayModal()" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                            Cancelar
                        </button>
                        <button type="button" id="btnMakeAvailable" onclick="makeDayAvailable()" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none hidden">
                            Hacer Disponible
                        </button>
                        <button type="button" onclick="markDayAsUnavailable()" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                            Marcar como No Disponible
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if($isSuperAdmin ?? false)
<script>
// Funciones para editar días del calendario (solo superadmin) - Definidas antes del DOMContentLoaded
let selectedDateForEdit = null;
let calendarDaysGlobal = {}; // Variable global para calendarDays

// Configurar SweetAlert2 con estilo consistente
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// Función para formatear fecha (disponible globalmente)
function formatDate(dateInput) {
    const date = dateInput instanceof Date ? dateInput : new Date(dateInput);
    return date.toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function openEditDayModal(dateStr, dateObj, existingData = null) {
    selectedDateForEdit = dateStr;
    const modal = document.getElementById('editDayModal');
    if (!modal) {
        console.error('Modal editDayModal no encontrado');
        return;
    }
    
    const dateDisplay = document.getElementById('selectedDateDisplay');
    const selectedDateInput = document.getElementById('selectedDate');
    const scheduleForm = document.getElementById('scheduleForm');
    const unavailableActions = document.getElementById('unavailableActions');
    
    // Resetear formulario
    if (scheduleForm) scheduleForm.classList.add('hidden');
    if (unavailableActions) unavailableActions.classList.add('hidden');
    const btnUnavailable = document.getElementById('btnUnavailable');
    const btnScheduled = document.getElementById('btnScheduled');
    if (btnUnavailable) btnUnavailable.classList.remove('border-red-600', 'bg-red-50');
    if (btnScheduled) btnScheduled.classList.remove('border-yellow-600', 'bg-yellow-50');
    
    // Mostrar fecha seleccionada
    const formattedDate = formatDate(dateObj);
    if (dateDisplay) dateDisplay.textContent = formattedDate;
    if (selectedDateInput) selectedDateInput.value = dateStr;
    
    // Si hay datos existentes, cargarlos
    if (existingData) {
        if (existingData.status === 'scheduled') {
            selectDayOption('scheduled');
            const instName = document.getElementById('institution_name');
            const entryTime = document.getElementById('entry_time');
            const exitTime = document.getElementById('exit_time');
            const notes = document.getElementById('notes');
            if (instName) instName.value = existingData.institution_name || '';
            if (entryTime) entryTime.value = existingData.entry_time || '';
            if (exitTime) exitTime.value = existingData.exit_time || '';
            if (notes) notes.value = existingData.notes || '';
        } else if (existingData.status === 'unavailable') {
            selectDayOption('unavailable');
            // Mostrar opción para hacer disponible
            const btnMakeAvailable = document.getElementById('btnMakeAvailable');
            const unavailableMessage = document.getElementById('unavailableMessage');
            if (btnMakeAvailable) btnMakeAvailable.classList.remove('hidden');
            if (unavailableMessage) {
                unavailableMessage.textContent = 'Este día está marcado como no disponible. Puedes cambiarlo a disponible o agendarlo.';
            }
        }
    }
    
    modal.classList.remove('hidden');
}

function closeEditDayModal() {
    const modal = document.getElementById('editDayModal');
    if (modal) modal.classList.add('hidden');
    selectedDateForEdit = null;
    
    // Resetear formulario
    const form = document.getElementById('scheduleDayForm');
    if (form) form.reset();
    const scheduleForm = document.getElementById('scheduleForm');
    if (scheduleForm) scheduleForm.classList.add('hidden');
    const unavailableActions = document.getElementById('unavailableActions');
    if (unavailableActions) unavailableActions.classList.add('hidden');
    const btnUnavailable = document.getElementById('btnUnavailable');
    const btnScheduled = document.getElementById('btnScheduled');
    if (btnUnavailable) btnUnavailable.classList.remove('border-red-600', 'bg-red-50');
    if (btnScheduled) btnScheduled.classList.remove('border-yellow-600', 'bg-yellow-50');
}

function selectDayOption(option) {
    const scheduleForm = document.getElementById('scheduleForm');
    const unavailableActions = document.getElementById('unavailableActions');
    const availableActions = document.getElementById('availableActions');
    const btnAvailable = document.getElementById('btnAvailable');
    const btnUnavailable = document.getElementById('btnUnavailable');
    const btnScheduled = document.getElementById('btnScheduled');
    const statusInput = document.getElementById('selectedStatus');
    const btnMakeAvailable = document.getElementById('btnMakeAvailable');
    const unavailableMessage = document.getElementById('unavailableMessage');
    
    if (!scheduleForm || !unavailableActions || !btnUnavailable || !btnScheduled || !statusInput) {
        console.error('Elementos del modal no encontrados');
        return;
    }
    
    // Resetear estilos de botones
    if (btnAvailable) btnAvailable.classList.remove('border-green-600', 'bg-green-50');
    btnUnavailable.classList.remove('border-red-600', 'bg-red-50');
    btnScheduled.classList.remove('border-yellow-600', 'bg-yellow-50');
    
    if (option === 'available') {
        if (btnAvailable) btnAvailable.classList.add('border-green-600', 'bg-green-50');
        scheduleForm.classList.add('hidden');
        unavailableActions.classList.add('hidden');
        if (availableActions) availableActions.classList.remove('hidden');
        statusInput.value = 'available';
        if (btnMakeAvailable) btnMakeAvailable.classList.add('hidden');
    } else if (option === 'unavailable') {
        btnUnavailable.classList.add('border-red-600', 'bg-red-50');
        scheduleForm.classList.add('hidden');
        unavailableActions.classList.remove('hidden');
        if (availableActions) availableActions.classList.add('hidden');
        statusInput.value = 'unavailable';
        
        // Mostrar/ocultar botón de hacer disponible según si ya existe el día
        if (btnMakeAvailable && typeof calendarDaysGlobal !== 'undefined') {
            // Verificar si el día ya está marcado como no disponible
            const existingData = calendarDaysGlobal[selectedDateForEdit];
            if (existingData && existingData.status === 'unavailable') {
                btnMakeAvailable.classList.remove('hidden');
                if (unavailableMessage) {
                    unavailableMessage.textContent = 'Este día está marcado como no disponible. Puedes cambiarlo a disponible o agendarlo.';
                }
            } else {
                btnMakeAvailable.classList.add('hidden');
                if (unavailableMessage) {
                    unavailableMessage.textContent = '¿Estás seguro de marcar este día como no disponible?';
                }
            }
        }
    } else if (option === 'scheduled') {
        btnScheduled.classList.add('border-yellow-600', 'bg-yellow-50');
        scheduleForm.classList.remove('hidden');
        unavailableActions.classList.add('hidden');
        if (availableActions) availableActions.classList.add('hidden');
        statusInput.value = 'scheduled';
        if (btnMakeAvailable) btnMakeAvailable.classList.add('hidden');
    }
}

function markDayAsUnavailable() {
    if (!selectedDateForEdit) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'No hay fecha seleccionada.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        });
        return;
    }
    
    const formData = {
        date: selectedDateForEdit,
        status: 'unavailable',
        _token: '{{ csrf_token() }}'
    };
    
    // Mostrar loading
    Swal.fire({
        title: 'Guardando...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('{{ route("admin.visits.calendar.update-day") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Día marcado como no disponible exitosamente.',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                location.reload(); // Recargar para ver los cambios
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.error || 'No se pudo actualizar el día.',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Aceptar'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        let errorMsg = 'Error al guardar. Por favor, intenta de nuevo.';
        if (error.error) {
            errorMsg = error.error;
        } else if (error.message) {
            errorMsg = error.message;
        }
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMsg,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Aceptar'
        });
    });
}

function saveCalendarDay(event) {
    event.preventDefault();
    
    if (!selectedDateForEdit) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'No hay fecha seleccionada.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        });
        return;
    }
    
    const statusInput = document.getElementById('selectedStatus');
    const institutionName = document.getElementById('institution_name');
    const entryTime = document.getElementById('entry_time');
    const exitTime = document.getElementById('exit_time');
    const notes = document.getElementById('notes');
    
    if (!statusInput || !institutionName || !entryTime || !exitTime) {
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            text: 'Faltan campos requeridos. Por favor, completa todos los campos obligatorios.',
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Aceptar'
        });
        return;
    }
    
    const formData = {
        date: selectedDateForEdit,
        status: statusInput.value,
        institution_name: institutionName.value,
        entry_time: entryTime.value,
        exit_time: exitTime.value,
        notes: notes ? notes.value : '',
        _token: '{{ csrf_token() }}'
    };
    
    // Mostrar loading
    Swal.fire({
        title: 'Guardando...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('{{ route("admin.visits.calendar.update-day") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Día del calendario guardado exitosamente.',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                location.reload(); // Recargar para ver los cambios
            });
        } else {
            let errorMsg = 'Error al guardar.';
            if (data.errors) {
                errorMsg = Object.values(data.errors).flat().join('<br>');
            } else if (data.error) {
                errorMsg = data.error;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: errorMsg,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Aceptar'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        let errorMsg = 'Error al guardar. Por favor, intenta de nuevo.';
        if (error.error) {
            errorMsg = error.error;
        } else if (error.message) {
            errorMsg = error.message;
        } else if (error.errors) {
            errorMsg = Object.values(error.errors).flat().join('<br>');
        }
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: errorMsg,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Aceptar'
        });
    });
}

function makeDayAvailable() {
    if (!selectedDateForEdit) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'No hay fecha seleccionada.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        });
        return;
    }
    
    // Mostrar loading
    Swal.fire({
        title: 'Guardando...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    const formData = {
        date: selectedDateForEdit,
        status: 'available',
        _token: '{{ csrf_token() }}'
    };
    
    fetch('{{ route("admin.visits.calendar.update-day") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'El día ahora está disponible.',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                location.reload(); // Recargar para ver los cambios
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.error || 'No se pudo actualizar el día.',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Aceptar'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        let errorMsg = 'Error al guardar. Por favor, intenta de nuevo.';
        if (error.error) {
            errorMsg = error.error;
        } else if (error.message) {
            errorMsg = error.message;
        }
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMsg,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Aceptar'
        });
    });
}
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos de eventos
    const events = @json($events);
    const unavailableDays = @json($unavailableDays);
    const calendarDays = @json($calendarDays ?? []);
    const isSuperAdmin = @json($isSuperAdmin ?? false);
    
    // Hacer calendarDays disponible globalmente para las funciones de edición
    calendarDaysGlobal = calendarDays;
    
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
            const dateStr = arg.date.toISOString().split('T')[0];
            
            // Verificar si hay un día agendado por superadmin
            const calendarDay = calendarDays[dateStr];
            const calendarDayEvent = events.find(event => {
                const eventDate = new Date(event.start);
                const eventDateStr = eventDate.toISOString().split('T')[0];
                return eventDateStr === dateStr && 
                       (event.extendedProps?.isCalendarDay === true || event.isCalendarDay === true);
            });
            
            // Verificar si hay visitas en este día
            const visitEvent = events.find(event => {
                const eventDate = new Date(event.start);
                const eventDateStr = eventDate.toISOString().split('T')[0];
                return eventDateStr === dateStr && 
                       (event.extendedProps?.isVisit === true || event.isVisit === true || event.visit);
            });
            
            // Si hay visita, mostrar la visita (prioridad)
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
            
            // Si hay día agendado por superadmin (sin visita)
            if (calendarDayEvent && calendarDay && calendarDay.status === 'scheduled') {
                // Colorear todo el día de amarillo
                arg.el.style.backgroundColor = '#ffc107';
                arg.el.style.color = '#856404';
                arg.el.classList.add('has-calendar-day');
                
                // Asegurar que el número del día tenga buen contraste
                const dayNumber = arg.el.querySelector('.fc-daygrid-day-number');
                if (dayNumber) {
                    dayNumber.style.color = '#856404';
                    dayNumber.style.fontWeight = '700';
                }
                
                // Agregar evento de click para editar (solo superadmin)
                if (isSuperAdmin) {
                    arg.el.style.cursor = 'pointer';
                    arg.el.addEventListener('click', function() {
                        openEditDayModal(dateStr, arg.date, calendarDay);
                    });
                }
                
                // Tooltip con información del día agendado
                const calendarData = calendarDayEvent.calendarDay || calendarDayEvent.extendedProps?.calendarDay;
                if (calendarData) {
                    arg.el.title = `${calendarData.institution_name} (${calendarData.entry_time} - ${calendarData.exit_time})`;
                } else {
                    arg.el.title = calendarDayEvent.title || 'Día agendado';
                }
                return; // No aplicar otras reglas si hay día agendado
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
                // Si es superadmin y el día es futuro, permitir editar
                if (isSuperAdmin && currentDate >= today) {
                    arg.el.style.cursor = 'pointer';
                    arg.el.addEventListener('click', function() {
                        openEditDayModal(dateStr, arg.date);
                    });
                } else {
                    arg.el.addEventListener('click', function() {
                        showUnavailableDayModal('Fin de semana', arg.date);
                    });
                }
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
                    // Si es superadmin y el día es futuro, permitir editar
                    if (isSuperAdmin && currentDate >= today) {
                        arg.el.style.cursor = 'pointer';
                        arg.el.addEventListener('click', function() {
                            openEditDayModal(dateStr, arg.date);
                        });
                    } else {
                        arg.el.addEventListener('click', function() {
                            showUnavailableDayModal(unavailableDay.reason, arg.date);
                        });
                    }
                }
                // Marcar días disponibles (días futuros que no son fines de semana ni festivos)
                else if (currentDate >= today) {
                    // Verificar si hay un día editado por superadmin
                    const calendarDay = calendarDays[dateStr];
                    
                    if (calendarDay) {
                        if (calendarDay.status === 'unavailable') {
                            // Día marcado como no disponible por superadmin
                            arg.el.style.backgroundColor = '#dc3545';
                            arg.el.style.color = '#fff';
                            arg.el.title = 'Día no disponible (editado por administrador) - Click para editar';
                            arg.el.classList.add('admin-unavailable-day');
                            
                            // Agregar evento de click para editar (solo si es día futuro y superadmin)
                            if (isSuperAdmin && currentDate >= today) {
                                arg.el.style.cursor = 'pointer';
                                arg.el.addEventListener('click', function() {
                                    openEditDayModal(dateStr, arg.date, calendarDay);
                                });
                            }
                        } else if (calendarDay.status === 'scheduled') {
                            // Día agendado por superadmin (ya se muestra como evento amarillo)
                            arg.el.style.cursor = 'pointer';
                            if (isSuperAdmin) {
                                arg.el.addEventListener('click', function() {
                                    openEditDayModal(dateStr, arg.date, calendarDay);
                                });
                            }
                        }
                    } else {
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
                            
                            // Si es superadmin, permitir editar días disponibles
                            if (isSuperAdmin) {
                                arg.el.style.cursor = 'pointer';
                                arg.el.addEventListener('click', function() {
                                    openEditDayModal(dateStr, arg.date);
                                });
                            }
                        }
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
                     // Si es superadmin y el día es futuro, permitir editar
                     if (isSuperAdmin) {
                         cell.style.cursor = 'pointer';
                         cell.addEventListener('click', function() {
                             const dateStr = currentDate.toISOString().split('T')[0];
                             openEditDayModal(dateStr, currentDate);
                         });
                     } else {
                         cell.addEventListener('click', function() {
                             showUnavailableDayModal('Fin de semana', currentDate);
                         });
                     }
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
    
    // Función para formatear fecha (global)
    window.formatDate = function(dateInput) {
        const date = dateInput instanceof Date ? dateInput : new Date(dateInput);
        return date.toLocaleDateString('es-ES', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };
});
</script>
@endpush
