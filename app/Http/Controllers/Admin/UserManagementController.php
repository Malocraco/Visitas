<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    /**
     * Mostrar lista de usuarios
     */
    public function index(Request $request)
    {
        $query = User::with('roles');
        
        // Filtro por búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('institution_name', 'like', "%{$search}%");
            });
        }
        
        // Filtro por rol
        if ($request->filled('role')) {
            $role = $request->role;
            $query->whereHas('roles', function($q) use ($role) {
                $q->where('name', $role);
            });
        }
        
        // Filtro por institución
        if ($request->filled('institution_type')) {
            $institutionType = $request->institution_type;
            $query->where('institution_type', $institutionType);
        }
        
        // Ordenamiento
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);
        
        $users = $query->paginate(15)->withQueryString();
        
        // Obtener roles para el filtro
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Mostrar formulario para crear usuario
     */
    public function create()
    {
        // Filtrar roles disponibles - excluir SuperAdmin
        $roles = Role::where('name', '!=', 'superadmin')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'institution_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        // Validar que no se esté asignando el rol de SuperAdmin
        $selectedRole = Role::find($request->role_id);
        if ($selectedRole && $selectedRole->name === 'superadmin') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['role_id' => 'No se puede asignar el rol de Super Administrador desde esta interfaz.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'institution_name' => $request->institution_name,
            'phone' => $request->contact_phone,
        ]);

        // Asignar rol
        $user->roles()->attach($request->role_id);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar formulario para editar usuario
     */
    public function edit(User $user)
    {
        // Filtrar roles disponibles - excluir SuperAdmin
        $roles = Role::where('name', '!=', 'superadmin')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'institution_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        // Validar que no se esté asignando el rol de SuperAdmin
        $selectedRole = Role::find($request->role_id);
        if ($selectedRole && $selectedRole->name === 'superadmin') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['role_id' => 'No se puede asignar el rol de Super Administrador desde esta interfaz.']);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'institution_name' => $request->institution_name,
            'phone' => $request->contact_phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Sincronizar roles
        $user->roles()->sync([$request->role_id]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar usuario
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}
