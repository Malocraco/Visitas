<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    /**
     * Mostrar el formulario de registro
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Procesar el registro
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'required|string|max:20',
            'institution_name' => 'required|string|max:255',
            'institution_type' => 'required|string|in:empresa,universidad,colegio,otro',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'institution_name' => $request->institution_name,
            'institution_type' => $request->institution_type,
        ]);

        // Asignar rol de visitante
        $visitorRole = Role::where('name', 'visitante')->first();
        if ($visitorRole) {
            $user->roles()->attach($visitorRole->id);
        }

        // Autenticar al usuario
        Auth::login($user);

        return redirect()->route('dashboard.visitor')
            ->with('success', '¡Registro exitoso! Bienvenido al sistema de visitas.');
    }
}
