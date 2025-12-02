<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\User;
use App\Models\VisitActivity;
use App\Models\VisitSchedule;
use App\Models\CalendarDay;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VisitManagementController extends Controller
{
    /**
     * Mostrar solicitudes pendientes
     */
    public function pendingRequests()
    {
        $pendingVisits = Visit::with(['user', 'activities'])
            ->where('status', 'pending')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('admin.visits.pending', compact('pendingVisits'));
    }

    /**
     * Mostrar todas las visitas
     */
    public function allVisits()
    {
        $visits = Visit::with(['user', 'activities'])
            ->orderBy('id', 'asc')
            ->paginate(15);

        $stats = [
            'total' => Visit::count(),
            'pending' => Visit::where('status', 'pending')->count(),
            'approved' => Visit::where('status', 'approved')->count(),
            'completed' => Visit::where('status', 'completed')->count(),
            'rejected' => Visit::where('status', 'rejected')->count(),
        ];

        return view('admin.visits.all', compact('visits', 'stats'));
    }

    /**
     * Mostrar visitas aprobadas (para Administrador)
     */
    public function approvedVisits()
    {
        $approvedVisits = Visit::with(['user', 'activities'])
            ->where('status', 'approved')
            ->orderBy('confirmed_date', 'asc')
            ->paginate(15);

        $stats = [
            'total_approved' => Visit::where('status', 'approved')->count(),
            'today_visits' => Visit::where('status', 'approved')
                ->whereDate('confirmed_date', today())
                ->count(),
            'this_week_visits' => Visit::where('status', 'approved')
                ->whereBetween('confirmed_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->count(),
            'completed_visits' => Visit::where('status', 'completed')->count(),
        ];

        return view('admin.visits.approved', compact('approvedVisits', 'stats'));
    }

    /**
     * Mostrar reportes
     */
    public function reports()
    {
        // Estadísticas generales
        $totalVisits = Visit::count();
        $thisMonthVisits = Visit::whereMonth('created_at', Carbon::now()->month)->count();
        $thisYearVisits = Visit::whereYear('created_at', Carbon::now()->year)->count();

        // Visitas por estado
        $visitsByStatus = Visit::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Visitas por mes (últimos 12 meses)
        $visitsByMonth = Visit::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month', 'year')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Top instituciones visitantes
        $topInstitutions = Visit::with('user')
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return view('admin.visits.reports', compact(
            'totalVisits',
            'thisMonthVisits',
            'thisYearVisits',
            'visitsByStatus',
            'visitsByMonth',
            'topInstitutions'
        ));
    }

    /**
     * Mostrar calendario de visitas
     */
    public function calendar(Request $request)
    {
        $year = (int) $request->get('year', Carbon::now()->year);
        $month = (int) $request->get('month', Carbon::now()->month);
        
        // Validar y ajustar mes/año si es necesario
        if ($month < 1) {
            $month = 12;
            $year--;
        } elseif ($month > 12) {
            $month = 1;
            $year++;
        }
        
        // Obtener todas las visitas del mes seleccionado
        $visits = Visit::where(function($query) use ($year, $month) {
                $query->whereYear('preferred_date', $year)
                      ->whereMonth('preferred_date', $month);
            })
            ->orWhere(function($query) use ($year, $month) {
                $query->whereYear('confirmed_date', $year)
                      ->whereMonth('confirmed_date', $month);
            })
            ->with(['user', 'activities'])
            ->get();

        // Crear array de eventos para el calendario
        $events = [];
        
        foreach ($visits as $visit) {
            $status = $visit->status;
            $title = '';
            
            // Todas las visitas se muestran en amarillo como fondo completo del día
            $color = '#ffc107'; // Amarillo para todas las visitas
            $title = "Visita #{$visit->id} - {$visit->user->name} ({$status})";
            
            $eventDate = $visit->confirmed_date ?? $visit->preferred_date;
            $events[] = [
                'id' => $visit->id,
                'title' => $title,
                'start' => $eventDate->format('Y-m-d'),
                'end' => $eventDate->format('Y-m-d'),
                'display' => 'background',
                'color' => $color,
                'visit' => [
                    'id' => $visit->id,
                    'status' => $visit->status,
                    'preferred_date' => $visit->preferred_date ? $visit->preferred_date->format('Y-m-d') : null,
                    'preferred_start_time' => $visit->preferred_start_time,
                    'preferred_end_time' => $visit->preferred_end_time,
                    'contact_email' => $visit->contact_email,
                    'contact_phone' => $visit->contact_phone,
                    'institution_name' => $visit->institution_name,
                    'visit_purpose' => $visit->visit_purpose,
                    'user' => [
                        'name' => $visit->user->name,
                        'email' => $visit->user->email
                    ]
                ],
                'isVisit' => true,
                'extendedProps' => [
                    'isVisit' => true
                ]
            ];
        }
        
        // Agregar días agendados por superadmin como eventos
        $firstDay = Carbon::create($year, $month, 1);
        $lastDay = $firstDay->copy()->endOfMonth();
        $scheduledDays = CalendarDay::whereBetween('date', [$firstDay->format('Y-m-d'), $lastDay->format('Y-m-d')])
            ->where('status', 'scheduled')
            ->get();
        
        foreach ($scheduledDays as $scheduledDay) {
            $entryTime = $scheduledDay->entry_time ? $scheduledDay->entry_time->format('H:i') : '';
            $exitTime = $scheduledDay->exit_time ? $scheduledDay->exit_time->format('H:i') : '';
            $timeRange = ($entryTime && $exitTime) ? " ({$entryTime} - {$exitTime})" : '';
            
            $events[] = [
                'id' => 'calendar_day_' . $scheduledDay->id,
                'title' => $scheduledDay->institution_name . $timeRange,
                'start' => $scheduledDay->date->format('Y-m-d'),
                'end' => $scheduledDay->date->format('Y-m-d'),
                'display' => 'background',
                'color' => '#ffc107', // Amarillo igual que las visitas
                'calendarDay' => [
                    'id' => $scheduledDay->id,
                    'institution_name' => $scheduledDay->institution_name,
                    'entry_time' => $entryTime,
                    'exit_time' => $exitTime,
                    'notes' => $scheduledDay->notes,
                    'date' => $scheduledDay->date->format('Y-m-d'),
                ],
                'isCalendarDay' => true,
                'extendedProps' => [
                    'isCalendarDay' => true
                ]
            ];
        }

        // Obtener días editados por el superadministrador (una sola vez)
        $firstDay = Carbon::create($year, $month, 1);
        $lastDay = $firstDay->copy()->endOfMonth();
        $calendarDaysCollection = CalendarDay::whereBetween('date', [$firstDay->format('Y-m-d'), $lastDay->format('Y-m-d')])
            ->get();
        
        // Días no disponibles (fines de semana, días festivos y días pasados)
        $unavailableDays = $this->getUnavailableDays($year, $month, $calendarDaysCollection);
        
        // Convertir a array asociativo con fecha como clave para JavaScript
        $calendarDays = [];
        foreach ($calendarDaysCollection as $day) {
            $calendarDays[$day->date->format('Y-m-d')] = [
                'id' => $day->id,
                'date' => $day->date->format('Y-m-d'),
                'status' => $day->status,
                'institution_name' => $day->institution_name,
                'entry_time' => $day->entry_time ? $day->entry_time->format('H:i') : null,
                'exit_time' => $day->exit_time ? $day->exit_time->format('H:i') : null,
                'notes' => $day->notes,
            ];
        }
        
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        
        return view('admin.visits.calendar', compact('events', 'unavailableDays', 'year', 'month', 'calendarDays', 'isSuperAdmin'));
    }

    /**
     * Obtener días no disponibles (fines de semana, días festivos y días pasados)
     */
    private function getUnavailableDays($year, $month, $calendarDaysCollection = null)
    {
        $unavailableDays = [];
        
        // Obtener el primer y último día del mes
        $firstDay = Carbon::create($year, $month, 1);
        $lastDay = $firstDay->copy()->endOfMonth();
        
        // Obtener días editados por superadmin si no se pasan como parámetro
        if ($calendarDaysCollection === null) {
            $calendarDaysCollection = CalendarDay::whereBetween('date', [$firstDay->format('Y-m-d'), $lastDay->format('Y-m-d')])
                ->get();
        }
        
        $calendarDays = $calendarDaysCollection->keyBy(function($day) {
            return $day->date->format('Y-m-d');
        });
        
        // Días festivos (puedes agregar más según necesites)
        $holidays = [
            '01-01', // Año Nuevo
            '05-01', // Día del Trabajo
            '07-20', // Independencia
            '08-07', // Batalla de Boyacá
            '12-25', // Navidad
        ];
        
        $currentDay = $firstDay->copy();
        $today = Carbon::today();
        
        while ($currentDay <= $lastDay) {
            $dayOfWeek = $currentDay->dayOfWeek;
            $dateString = $currentDay->format('m-d');
            $dateKey = $currentDay->format('Y-m-d');
            
            // Verificar si el día fue editado por superadmin
            if (isset($calendarDays[$dateKey])) {
                $calendarDay = $calendarDays[$dateKey];
                if ($calendarDay->status === 'unavailable') {
                    $unavailableDays[] = [
                        'date' => $dateKey,
                        'reason' => 'Día no disponible (editado por administrador)',
                        'type' => 'admin_unavailable'
                    ];
                }
                // Si está agendado, no se agrega a unavailableDays (se maneja en events)
            }
            // Días pasados (antes de hoy)
            elseif ($currentDay->lt($today)) {
                $unavailableDays[] = [
                    'date' => $dateKey,
                    'reason' => 'Día pasado',
                    'type' => 'past'
                ];
            }
            // Fines de semana (0 = domingo, 6 = sábado)
            elseif ($dayOfWeek == 0 || $dayOfWeek == 6) {
                $unavailableDays[] = [
                    'date' => $dateKey,
                    'reason' => 'Fin de semana',
                    'type' => 'weekend'
                ];
            }
            // Días festivos
            elseif (in_array($dateString, $holidays)) {
                $unavailableDays[] = [
                    'date' => $dateKey,
                    'reason' => 'Día festivo',
                    'type' => 'holiday'
                ];
            }
            
            $currentDay->addDay();
        }
        
        return $unavailableDays;
    }

    /**
     * Aprobar una visita
     */
    public function approveVisit(Request $request, $id)
    {
        $visit = Visit::findOrFail($id);
        
        $visit->update([
            'status' => 'approved',
            'approved_at' => Carbon::now(),
            'approved_by' => auth()->id(),
            'admin_notes' => $request->input('admin_notes', '')
        ]);

        return redirect()->back()->with('success', 'Visita aprobada exitosamente.');
    }

    /**
     * Posponer una visita
     */
    public function postponeVisit(Request $request, $id)
    {
        $visit = Visit::findOrFail($id);
        
        $request->validate([
            'postponement_reason' => 'required|string|max:500',
            'suggested_date' => 'nullable|date|after:today'
        ]);

        $visit->update([
            'status' => 'postponed',
            'postponed_at' => Carbon::now(),
            'postponed_by' => auth()->id(),
            'postponement_reason' => $request->postponement_reason,
            'suggested_date' => $request->suggested_date,
            'admin_notes' => $request->input('admin_notes', '')
        ]);

        // Crear chat privado automático con el visitante
        $this->createPostponementChat($visit, $request);

        return redirect()->back()->with('success', 'Visita pospuesta exitosamente. Se ha creado un chat privado con el visitante para coordinar la reagendación.');
    }

    /**
     * Crear chat privado automático cuando se pospone una visita
     */
    private function createPostponementChat(Visit $visit, Request $request)
    {
        // Verificar si ya existe un chat para esta visita
        $existingChat = \App\Models\ChatRoom::where('visitor_id', $visit->user_id)
            ->where('subject', 'LIKE', '%Visita #' . $visit->id . '%')
            ->first();

        if ($existingChat) {
            // Si ya existe, enviar mensaje al chat existente
            $this->sendPostponementMessage($existingChat, $visit, $request);
        } else {
            // Crear nuevo chat privado
            $chatRoom = \App\Models\ChatRoom::create([
                'room_id' => \App\Models\ChatRoom::generateRoomId(),
                'visitor_id' => $visit->user_id,
                'admin_id' => auth()->id(),
                'subject' => 'Reagendación de Visita #' . $visit->id,
                'status' => 'active',
                'last_message_at' => now(),
            ]);

            // Enviar mensaje inicial de posponimiento
            $this->sendPostponementMessage($chatRoom, $visit, $request);
        }
    }

    /**
     * Enviar mensaje de posponimiento al chat
     */
    private function sendPostponementMessage(\App\Models\ChatRoom $chatRoom, Visit $visit, Request $request)
    {
        $admin = auth()->user();
        $suggestedDateText = $request->suggested_date ? 
            "Fecha sugerida: " . Carbon::parse($request->suggested_date)->format('d/m/Y') : 
            "Por favor, contacta con nosotros para coordinar una nueva fecha.";

        $message = "Hola {$visit->user->name},\n\n" .
                  "Te informamos que tu visita programada para el " . $visit->preferred_date->format('d/m/Y') . " ha sido pospuesta.\n\n" .
                  "**Motivo:** {$request->postponement_reason}\n\n" .
                  "{$suggestedDateText}\n\n" .
                  "Por favor, responde a este mensaje para coordinar una nueva fecha que sea conveniente para ambas partes.\n\n" .
                  "Saludos,\n" .
                  "Equipo de Administración";

        $chatRoom->messages()->create([
            'sender_id' => $admin->id,
            'sender_type' => 'admin',
            'message' => $message,
        ]);

        // Actualizar timestamp del último mensaje
        $chatRoom->update(['last_message_at' => now()]);
    }

    /**
     * Marcar visita como completada
     */
    public function completeVisit(Request $request, $id)
    {
        $visit = Visit::findOrFail($id);
        
        $visit->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
            'completed_by' => auth()->id(),
            'completion_notes' => $request->input('completion_notes', '')
        ]);

        return redirect()->back()->with('success', 'Visita marcada como completada.');
    }

    /**
     * Mostrar detalles de una visita
     */
    public function visitDetails($id)
    {
        $visit = Visit::with(['user', 'activities', 'attendees', 'logs'])
            ->findOrFail($id);

        return view('admin.visits.details', compact('visit'));
    }

    /**
     * Crear o actualizar un día del calendario (solo superadmin)
     */
    public function updateCalendarDay(Request $request)
    {
        // Verificar que el usuario sea superadmin
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['success' => false, 'error' => 'No tienes permisos para realizar esta acción.'], 403);
        }

        try {
            $validated = $request->validate([
                'date' => 'required|date',
                'status' => 'required|in:available,unavailable,scheduled',
                'institution_name' => 'required_if:status,scheduled|string|max:255',
                'entry_time' => 'required_if:status,scheduled|date_format:H:i',
                'exit_time' => 'required_if:status,scheduled|date_format:H:i',
                'notes' => 'nullable|string|max:1000',
            ]);
            
            // Si el status es 'available', eliminar el registro en lugar de actualizar
            if ($request->status === 'available') {
                $calendarDay = CalendarDay::where('date', $request->date)->first();
                if ($calendarDay) {
                    $calendarDay->delete();
                    return response()->json([
                        'success' => true,
                        'message' => 'El día ahora está disponible.',
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'message' => 'El día ya está disponible.',
                    ]);
                }
            }

            // Validar que la hora de salida sea después de la hora de entrada
            if ($request->status === 'scheduled' && $request->entry_time && $request->exit_time) {
                $entryTime = Carbon::createFromFormat('H:i', $request->entry_time);
                $exitTime = Carbon::createFromFormat('H:i', $request->exit_time);
                if ($exitTime->lte($entryTime)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'La hora de salida debe ser posterior a la hora de entrada.',
                        'errors' => ['exit_time' => ['La hora de salida debe ser posterior a la hora de entrada.']]
                    ], 422);
                }
            }

            $calendarDay = CalendarDay::updateOrCreate(
                ['date' => $request->date],
                [
                    'status' => $request->status,
                    'institution_name' => $request->status === 'scheduled' ? $request->institution_name : null,
                    'entry_time' => $request->status === 'scheduled' ? $request->entry_time : null,
                    'exit_time' => $request->status === 'scheduled' ? $request->exit_time : null,
                    'notes' => $request->notes ?? null,
                    'created_by' => auth()->id(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => $calendarDay->wasRecentlyCreated ? 'Día del calendario creado exitosamente.' : 'Día del calendario actualizado exitosamente.',
                'calendarDay' => $calendarDay->load('creator'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar o restaurar un día del calendario a disponible (solo superadmin)
     */
    public function deleteCalendarDay(Request $request, $date)
    {
        // Verificar que el usuario sea superadmin
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['error' => 'No tienes permisos para realizar esta acción.'], 403);
        }

        $calendarDay = CalendarDay::where('date', $date)->first();

        if ($calendarDay) {
            $calendarDay->delete();
            return response()->json([
                'success' => true,
                'message' => 'Día del calendario eliminado exitosamente. El día vuelve a estar disponible.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'El día ya está disponible.',
        ]);
    }
}
