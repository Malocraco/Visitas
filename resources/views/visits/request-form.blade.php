@extends('layouts.app')

@section('title', 'Solicitar Visita - Sistema de Visitas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Solicitud de Visita
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Completa la información para solicitar una visita a nuestra institución
            </p>
                </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="{{ route('dashboard.visitor') }}" class="btn-outline">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Dashboard
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('visits.submit') }}" class="space-y-8">
                        @csrf
                        
        <!-- Información General -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="mr-2 h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                                    Información General de la Visita
                </h3>
                            </div>
            <div class="card-body space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                                <label for="visit_date" class="form-label">
                                    Fecha de Visita *
                                </label>
                        <input type="date" 
                                           id="visit_date" 
                                           name="visit_date" 
                                           value="{{ old('visit_date') }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="form-input @error('visit_date') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror"
                                           required>
                                @error('visit_date')
                            <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                    <div>
                        <label for="expected_participants" class="form-label">
                            Número de Personas *
                                </label>
                        <input type="number" 
                               id="expected_participants" 
                               name="expected_participants" 
                               value="{{ old('expected_participants') }}"
                               min="1" 
                               max="100" 
                               class="form-input @error('expected_participants') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror"
                               required>
                        @error('expected_participants')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                                        </div>
                                    </div>
                                    
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="arrival_time" class="form-label">
                            Hora de Llegada *
                        </label>
                                                <input type="time" 
                               id="arrival_time" 
                               name="arrival_time" 
                               value="{{ old('arrival_time', '08:00') }}"
                               class="form-input @error('arrival_time') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror"
                                                       required>
                                @error('arrival_time')
                            <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                    <div>
                        <label for="departure_time" class="form-label">
                            Hora de Salida *
                                </label>
                        <input type="time" 
                               id="departure_time" 
                               name="departure_time" 
                               value="{{ old('departure_time', '09:00') }}"
                               class="form-input @error('departure_time') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror"
                                       required>
                        @error('departure_time')
                            <p class="form-error">{{ $message }}</p>
                                @enderror
                    </div>
                            </div>

                <div>
                                <label for="visit_purpose" class="form-label">
                                    Propósito de la Visita *
                                </label>
                    <textarea id="visit_purpose" 
                                          name="visit_purpose" 
                              rows="4"
                              class="form-input @error('visit_purpose') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror"
                                          placeholder="Describe el propósito de la visita..."
                                          required>{{ old('visit_purpose') }}</textarea>
                                @error('visit_purpose')
                        <p class="form-error">{{ $message }}</p>
                                @enderror
                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="mr-2 h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                                    Información de Contacto
                </h3>
            </div>
            <div class="card-body">
                <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-primary-700">
                                <strong>Información de tu cuenta:</strong> Los siguientes campos muestran la información de tu perfil. Si necesitas actualizar algún dato, ve a <a href="{{ route('admin.settings.index') }}" class="font-medium text-primary-600 hover:text-primary-500">Configuración</a>.
                            </p>
                        </div>
                                </div>
                            </div>
                            
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                                <label for="contact_person" class="form-label">
                                    Persona de Contacto *
                                </label>
                                <input type="text" 
                                       id="contact_person" 
                                       name="contact_person" 
                                       value="{{ auth()->user()->name }}" 
                               class="form-input bg-gray-50"
                                       readonly
                                       required>
                            </div>

                    <div>
                                <label for="contact_phone" class="form-label">
                                    Teléfono de Contacto *
                                </label>
                                <input type="tel" 
                                       id="contact_phone" 
                                       name="contact_phone" 
                                       value="{{ auth()->user()->phone ?? 'No especificado' }}" 
                               class="form-input bg-gray-50"
                                       readonly
                                       required>
                            </div>

                    <div>
                                <label for="contact_email" class="form-label">
                                    Email de Contacto *
                                </label>
                                <input type="email" 
                                       id="contact_email" 
                                       name="contact_email" 
                                       value="{{ auth()->user()->email }}" 
                               class="form-input bg-gray-50"
                                       readonly
                                       required>
                            </div>

                    <div>
                                <label for="institution_name" class="form-label">
                            Nombre de la Institución *
                                </label>
                                <input type="text" 
                                       id="institution_name" 
                                       name="institution_name" 
                                       value="{{ auth()->user()->institution_name ?? 'No especificado' }}" 
                               class="form-input bg-gray-50"
                                       readonly
                                       required>
                            </div>
                    <div>
                        <label for="institution_type" class="form-label">
                            Tipo de Institución *
                        </label>
                        @php($userInstitutionType = auth()->user()->institution_type)
                        <select id="institution_type" name="institution_type" 
                                class="form-input @error('institution_type') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror" 
                                {{ $userInstitutionType ? '' : '' }} required>
                            <option value="" {{ old('institution_type', $userInstitutionType) === null ? 'selected' : '' }}>Seleccione...</option>
                            <option value="empresa" {{ old('institution_type', $userInstitutionType) === 'empresa' ? 'selected' : '' }}>Empresa</option>
                            <option value="universidad" {{ old('institution_type', $userInstitutionType) === 'universidad' ? 'selected' : '' }}>Universidad</option>
                            <option value="colegio" {{ old('institution_type', $userInstitutionType) === 'colegio' ? 'selected' : '' }}>Colegio</option>
                            <option value="otro" {{ old('institution_type', $userInstitutionType) === 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('institution_type')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                            </div>
                        </div>

                        <!-- Actividades de Interés -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="mr-2 h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                                    Actividades de Interés
                </h3>
                            </div>
            <div class="card-body">
                <p class="text-sm text-gray-500 mb-6">Selecciona las actividades que te interesan para la visita (opcional):</p>
                            
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach($activities as $activity)
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="activity_{{ $activity->id }}" 
                                                       name="activities[]" 
                                       type="checkbox" 
                                                       value="{{ $activity->id }}" 
                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                                       {{ in_array($activity->id, old('activities', [])) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="activity_{{ $activity->id }}" class="font-medium text-gray-900">
                                    {{ $activity->name }}
                                                </label>
                                <p class="text-gray-500">{{ $activity->description }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                </div>
                
                @error('activities')
                    <p class="form-error mt-2">{{ $message }}</p>
                @enderror
                                    
                                    <!-- Campo "Otro" para actividades personalizadas -->
                <div class="mt-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="other_activities_check" 
                                                   name="other_activities_check" 
                                   type="checkbox" 
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                                   {{ old('other_activities_check') ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="other_activities_check" class="font-medium text-gray-900">
                                Otras actividades de interés
                                            </label>
                            <p class="text-gray-500">Especifica actividades adicionales que te gustaría realizar</p>
                        </div>
                                        </div>
                                        
                    <div class="mt-3" id="other_activities_container" style="display: none;">
                        <textarea id="other_activities" 
                                                      name="other_activities" 
                                                      rows="3" 
                                  class="form-input @error('other_activities') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror"
                                                      placeholder="Describe las actividades específicas que te gustaría realizar durante la visita...">{{ old('other_activities') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">
                                                Ejemplos: Charlas específicas, talleres particulares, visitas a áreas específicas, etc.
                        </p>
                                            @error('other_activities')
                            <p class="form-error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                            </div>
                        </div>

                        <!-- Servicios Adicionales -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="mr-2 h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
                    </svg>
                                    Servicios Adicionales
                </h3>
                            </div>
            <div class="card-body">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="restaurant_service" 
                                           name="restaurant_service" 
                               type="checkbox" 
                                           value="1"
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                           {{ old('restaurant_service') ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="restaurant_service" class="font-medium text-gray-900">
                            Requiere servicio de restaurante
                                    </label>
                    </div>
                                </div>
                                
                <div class="mt-4 bg-warning-50 border border-warning-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-warning-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-warning-700">
                                    <strong>Importante:</strong> Es necesario indicar con antelación el tipo de servicio y la cantidad de personas.
                            </p>
                        </div>
                    </div>
                                </div>
                                
                                <!-- Información del restaurante -->
                <div class="mt-4 bg-success-50 border border-success-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-success-800 mb-3">Información del Restaurante</h4>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-sm text-success-700">
                                                    <strong>Costo del almuerzo:</strong><br>
                                <span class="font-bold">$12.000 por persona</span>
                                                </p>
                                            </div>
                        <div>
                            <p class="text-sm text-success-700">
                                                    <strong>Contacto del restaurante:</strong><br>
                                <span class="font-bold">Señor Jhon Bedoya</span><br>
                                <span class="text-primary-600">313 403 1807 / 313 403 1809</span>
                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                <!-- Campos adicionales para el restaurante -->
                <div id="restaurant-fields" class="mt-6 space-y-4" style="display: none;">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                                            <label for="restaurant_participants" class="form-label">
                                                Cantidad de personas para el almuerzo *
                                            </label>
                                            <input type="number" 
                                                   id="restaurant_participants" 
                                                   name="restaurant_participants" 
                                                   value="{{ old('restaurant_participants') }}" 
                                                   min="1" 
                                   max="100"
                                   class="form-input @error('restaurant_participants') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror">
                                            @error('restaurant_participants')
                                <p class="form-error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                        <div>
                                            <label for="restaurant_notes" class="form-label">
                                                Notas adicionales para el restaurante
                                            </label>
                            <textarea id="restaurant_notes" 
                                                      name="restaurant_notes" 
                                                      rows="3" 
                                      class="form-input @error('restaurant_notes') border-danger-300 focus:border-danger-500 focus:ring-danger-500 @enderror"
                                                      placeholder="Especificaciones especiales, restricciones alimentarias, etc...">{{ old('restaurant_notes') }}</textarea>
                                            @error('restaurant_notes')
                                <p class="form-error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
        <div class="flex justify-between">
            <a href="{{ route('dashboard.visitor') }}" class="btn-outline">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                                        Cancelar
                                    </a>
            <button type="submit" class="btn-primary">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                                        Enviar Solicitud
                                    </button>
                        </div>
                    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de hora de salida después de llegada
    const arrivalTime = document.getElementById('arrival_time');
    const departureTime = document.getElementById('departure_time');
    
    if (arrivalTime && departureTime) {
        arrivalTime.addEventListener('change', function() {
            departureTime.min = this.value;
        });
    }
    
    // Toggle para otras actividades
     const otherActivitiesCheckbox = document.getElementById('other_activities_check');
    const otherActivitiesContainer = document.getElementById('other_activities_container');
     const otherActivitiesField = document.getElementById('other_activities');
     
     if (otherActivitiesCheckbox) {
         otherActivitiesCheckbox.addEventListener('change', function() {
             if (this.checked) {
                otherActivitiesContainer.style.display = 'block';
                 otherActivitiesField.required = true;
             } else {
                otherActivitiesContainer.style.display = 'none';
                 otherActivitiesField.required = false;
                 otherActivitiesField.value = '';
             }
         });
     }

    // Toggle para servicio de restaurante
     const restaurantServiceCheckbox = document.getElementById('restaurant_service');
     const restaurantFieldsContainer = document.getElementById('restaurant-fields');
    const restaurantParticipantsField = document.getElementById('restaurant_participants');

     if (restaurantServiceCheckbox) {
         restaurantServiceCheckbox.addEventListener('change', function() {
             if (this.checked) {
                 restaurantFieldsContainer.style.display = 'block';
                restaurantParticipantsField.required = true;
             } else {
                 restaurantFieldsContainer.style.display = 'none';
                restaurantParticipantsField.required = false;
                restaurantParticipantsField.value = '';
                 document.getElementById('restaurant_notes').value = '';
             }
         });
     }
});
</script>
@endpush