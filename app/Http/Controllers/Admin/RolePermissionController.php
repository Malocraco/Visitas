<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Mostrar lista de roles
     */
    public function index()
    {
        $roles = Role::withCount('users')->with('permissions')->paginate(15);
        
        return view('admin.roles.index', compact('roles'));
    }



    /**
     * Mostrar formulario para editar rol
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all() ?? collect();
        $rolePermissions = $role->permissions ? $role->permissions->pluck('id')->toArray() : [];
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Actualizar rol
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // No permitir cambiar el nombre de los roles del sistema
        if (in_array($role->name, ['superadmin', 'administrador', 'visitante'])) {
            $role->update([
                'description' => $request->description,
            ]);
        } else {
            $role->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
        }

        // Sincronizar permisos
        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Eliminar rol
     */
    public function destroy(Role $role)
    {
        // No permitir eliminar ninguno de los 3 roles del sistema
        if (in_array($role->name, ['superadmin', 'administrador', 'visitante'])) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'No se puede eliminar un rol del sistema.');
        }

        // Verificar si el rol tiene usuarios asignados
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'No se puede eliminar un rol que tiene usuarios asignados.');
        }

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol eliminado exitosamente.');
    }
}
