<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el dashboard del cliente con la lista de proyectos
     */
    public function index()
    {
        // Obtener los proyectos del usuario autenticado, ordenados por fecha de creación descendente
        $commandes = Auth::user()->commandes()
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        // Obtener estadísticas básicas
        $totalCommandes = Auth::user()->commandes()->count();
        $pendingCommandes = Auth::user()->commandes()->where('statue', 'pending')->count();
        $inProgressCommandes = Auth::user()->commandes()->where('statue', 'in_progress')->count();
        $completedCommandes = Auth::user()->commandes()->where('statue', 'completed')->count();
        
        return view('dashboard', compact(
            'commandes', 
            'totalCommandes', 
            'pendingCommandes', 
            'inProgressCommandes', 
            'completedCommandes'
        ));
    }
}
