<?php

namespace App\Policies;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommandePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Todos los usuarios autenticados pueden ver la lista
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Commande $commande): bool
    {
        // Permitir a artesanos ver todos los proyectos
        if ($user->role === 'craftsman') {
            return true;
        }
        
        // El cliente solo puede ver sus propios proyectos
        return $user->id === $commande->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Todos los usuarios autenticados pueden crear proyectos
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Commande $commande): bool
    {
        // El usuario solo puede actualizar sus propios proyectos
        return $user->id === $commande->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Commande $commande): bool
    {
        // El usuario solo puede eliminar sus propios proyectos
        return $user->id === $commande->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Commande $commande): bool
    {
        // El usuario solo puede restaurar sus propios proyectos
        return $user->id === $commande->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Commande $commande): bool
    {
        // El usuario solo puede eliminar permanentemente sus propios proyectos
        return $user->id === $commande->user_id;
    }
}
