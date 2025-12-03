<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    /**
     * Dashboard para visitantes
     */
    public function visitor()
    {
        $user = Auth::user();
        $visits = $user->visits()->orderBy('id', 'asc')->take(5)->get();
        
        return view('dashboard.visitor', compact('user', 'visits'));
    }

    /**
     * Dashboard para administradores
     */
    public function admin()
    {
        $user = Auth::user();
        $assignedVisits = $user->assignedVisits()
            ->where('status', 'approved')
            ->orderBy('id', 'asc')
            ->take(10)
            ->get();
        
        return view('dashboard.admin', compact('user', 'assignedVisits'));
    }

    /**
     * Dashboard para super administradores
     */
    public function superadmin()
    {
        $user = Auth::user();
        $pendingVisits = \App\Models\Visit::where('status', 'pending')
            ->orderBy('id', 'asc')
            ->take(10)
            ->get();
        
        $recentVisits = \App\Models\Visit::orderBy('id', 'asc')
            ->take(10)
            ->get();
        
        return view('dashboard.superadmin', compact('user', 'pendingVisits', 'recentVisits'));
    }

    /**
     * Redirigir al dashboard según el rol
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin()) {
            return redirect()->route('dashboard.superadmin');
        } elseif ($user->isAdmin()) {
            return redirect()->route('dashboard.admin');
        } else {
            return redirect()->route('dashboard.visitor');
        }
    }
}
