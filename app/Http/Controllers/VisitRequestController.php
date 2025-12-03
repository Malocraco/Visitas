<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\VisitActivity;
use App\Models\VisitSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VisitRequestController extends Controller
{
    /**
     * Mostrar el formulario de solicitud de visita
     */
    public function showForm()
    {
        // Obtener actividades disponibles
        $activities = VisitActivity::where('is_active', true)->get();
        
        // Obtener horarios disponibles
        $schedules = VisitSchedule::where('is_active', true)->get();
        
        return view('visits.request-form', compact('activities', 'schedules'));
    }

    /**
     * Procesar la solicitud de visita
     */
    public function submitRequest(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'visit_date' => 'required|date|after:today',
            'arrival_time' => 'required|date_format:H:i',
            'departure_time' => 'required|date_format:H:i|after:arrival_time',
            'expected_participants' => 'required|integer|min:1|max:100',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email',
            'institution_name' => 'required|string|max:255',
            'institution_type' => 'required|string|in:empresa,universidad,colegio,otro',
            'visit_purpose' => 'required|string|max:1000',
            'special_requirements' => 'nullable|string|max:1000',
            'activities' => 'nullable|array',
            'activities.*' => 'exists:visit_activities,id',
            'other_activities_check' => 'nullable|boolean',
            'other_activities' => 'nullable|string|max:1000|required_if:other_activities_check,1',
            'restaurant_service' => 'nullable|boolean',
            'restaurant_participants' => 'nullable|integer|min:1|max:100|required_if:restaurant_service,1',
            'restaurant_notes' => 'nullable|string|max:1000'
        ], [
            'visit_date.required' => 'La fecha de visita es obligatoria.',
            'visit_date.after' => 'La fecha de visita debe ser posterior a hoy.',
            'arrival_time.required' => 'La hora de llegada es obligatoria.',
            'arrival_time.date_format' => 'El formato de hora de llegada no es válido.',
            'departure_time.required' => 'La hora de salida es obligatoria.',
            'departure_time.date_format' => 'El formato de hora de salida no es válido.',
            'departure_time.after' => 'La hora de salida debe ser posterior a la hora de llegada.',
            'expected_participants.required' => 'El número de personas es obligatorio.',
            'expected_participants.integer' => 'El número de personas debe ser un número entero.',
            'expected_participants.min' => 'El número de personas debe ser al menos 1.',
            'expected_participants.max' => 'El número de personas no puede exceder 100.',
            'contact_person.required' => 'La persona de contacto es obligatoria.',
            'contact_phone.required' => 'El teléfono de contacto es obligatorio.',
            'contact_email.required' => 'El email de contacto es obligatorio.',
            'contact_email.email' => 'El formato del email no es válido.',
            'institution_name.required' => 'El nombre de la institución es obligatorio.',
            'institution_type.required' => 'El tipo de institución es obligatorio.',
            'visit_purpose.required' => 'El propósito de la visita es obligatorio.',
            'other_activities.required_if' => 'Debes describir las actividades personalizadas.',
            'restaurant_participants.required_if' => 'Debes especificar la cantidad de personas para el almuerzo.',
            'restaurant_participants.integer' => 'La cantidad de personas debe ser un número entero.',
            'restaurant_participants.min' => 'La cantidad de personas debe ser al menos 1.',
            'restaurant_participants.max' => 'La cantidad de personas no puede exceder 100.'
        ]);

        try {
            DB::beginTransaction();

            // Crear la visita
            $visit = Visit::create([
                'user_id' => Auth::id(),
                'preferred_date' => $request->visit_date,
                'preferred_start_time' => $request->arrival_time,
                'preferred_end_time' => $request->departure_time,
                'expected_participants' => $request->expected_participants,
                'contact_person' => $request->contact_person,
                'contact_phone' => $request->contact_phone,
                'contact_email' => $request->contact_email,
                'institution_name' => $request->institution_name,
                'institution_type' => $request->institution_type,
                'visit_purpose' => $request->visit_purpose,
                'special_requirements' => $request->special_requirements,
                'other_activities' => $request->other_activities_check ? $request->other_activities : null,
                'restaurant_service' => $request->restaurant_service ? true : false,
                'restaurant_participants' => $request->restaurant_service ? $request->restaurant_participants : null,
                'restaurant_notes' => $request->restaurant_service ? $request->restaurant_notes : null,
                'status' => 'pending'
            ]);

            // Asociar actividades seleccionadas (si las hay)
            if (!empty($request->activities)) {
                $visit->activities()->attach($request->activities);
            }

            // Crear log de la solicitud
            $visit->logs()->create([
                'action' => 'request_created',
                'description' => 'Solicitud de visita creada',
                'user_id' => Auth::id(),
                'details' => json_encode([
                    'visit_date' => $request->visit_date,
                    'expected_participants' => $request->expected_participants,
                    'activities_count' => count($request->activities ?? []),
                    'has_other_activities' => $request->other_activities_check ? true : false,
                    'other_activities' => $request->other_activities_check ? $request->other_activities : null,
                    'restaurant_service' => $request->restaurant_service ? true : false,
                    'restaurant_participants' => $request->restaurant_service ? $request->restaurant_participants : null
                ])
            ]);

            DB::commit();

            return redirect()->route('dashboard.visitor')
                ->with('success', '¡Solicitud de visita enviada exitosamente! Será revisada por nuestros administradores.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log del error para debugging
            \Log::error('Error al crear solicitud de visita: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['_token']),
                'exception' => $e
            ]);
            
            return back()->withInput()
                ->with('error', 'Error al enviar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar las solicitudes del usuario
     */
    public function myRequests()
    {
        $visits = Auth::user()->visits()
            ->with(['activities', 'logs'])
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('visits.my-requests', compact('visits'));
    }

    /**
     * Mostrar detalles de una solicitud específica
     */
    public function showRequest($id)
    {
        $visit = Auth::user()->visits()
            ->with(['activities', 'logs', 'messages'])
            ->findOrFail($id);

        return view('visits.request-details', compact('visit'));
    }

    /**
     * Obtener fechas disponibles para el calendario
     */
    public function getAvailableDates(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        // Obtener todas las fechas del mes
        $startDate = now()->startOfMonth();
        $endDate = now()->addMonths(3)->endOfMonth(); // Mostrar 3 meses hacia adelante
        
        // Obtener fechas ocupadas (visitas aprobadas)
        $occupiedDates = Visit::where('status', 'approved')
            ->whereBetween('preferred_date', [$startDate, $endDate])
            ->pluck('preferred_date')
            ->map(function($date) {
                return $date->format('Y-m-d');
            })
            ->toArray();
        
        // Obtener horarios disponibles
        $availableSchedules = VisitSchedule::where('is_active', true)->get();
        
        // Generar fechas disponibles (excluyendo fines de semana si es necesario)
        $availableDates = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            
            // Solo incluir fechas futuras
            if ($currentDate->isAfter(now())) {
                $availableDates[] = [
                    'date' => $dateString,
                    'available' => !in_array($dateString, $occupiedDates),
                    'day_of_week' => $currentDate->format('l'),
                    'formatted_date' => $currentDate->format('d/m/Y')
                ];
            }
            
            $currentDate->addDay();
        }
        
        return response()->json([
            'available_dates' => $availableDates,
            'occupied_dates' => $occupiedDates,
            'schedules' => $availableSchedules
        ]);
    }
}
