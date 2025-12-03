<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\User;
use App\Models\VisitActivity;
use App\Models\VisitSchedule;
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

        // Días no disponibles (fines de semana, días festivos y días pasados)
        $unavailableDays = $this->getUnavailableDays($year, $month);
        
        return view('admin.visits.calendar', compact('events', 'unavailableDays', 'year', 'month'));
    }

    /**
     * Obtener días no disponibles (fines de semana, días festivos y días pasados)
     */
    private function getUnavailableDays($year, $month)
    {
        $unavailableDays = [];
        
        // Obtener el primer y último día del mes
        $firstDay = Carbon::create($year, $month, 1);
        $lastDay = $firstDay->copy()->endOfMonth();
        
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
            
            // Días pasados (antes de hoy)
            if ($currentDay->lt($today)) {
                $unavailableDays[] = [
                    'date' => $currentDay->format('Y-m-d'),
                    'reason' => 'Día pasado',
                    'type' => 'past'
                ];
            }
            // Fines de semana (0 = domingo, 6 = sábado)
            elseif ($dayOfWeek == 0 || $dayOfWeek == 6) {
                $unavailableDays[] = [
                    'date' => $currentDay->format('Y-m-d'),
                    'reason' => 'Fin de semana',
                    'type' => 'weekend'
                ];
            }
            // Días festivos
            elseif (in_array($dateString, $holidays)) {
                $unavailableDays[] = [
                    'date' => $currentDay->format('Y-m-d'),
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
}
