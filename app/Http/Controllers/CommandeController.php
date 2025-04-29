<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CommandeController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role === 'craftsman') {
                // نسمح بعرض تفاصيل المشاريع للحرفيين
                if ($request->route()->getName() === 'commandes.show') {
                    return $next($request);
                }
                
                return redirect()->route('dashboard')
                    ->with('error', 'لا يمكن للحرفيين الوصول إلى صفحة المشاريع.');
            }
            return $next($request);
        })->except(['show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commandes = Auth::user()->commandes()->orderBy('created_at', 'desc')->paginate(10);
        
        // Obtener estadísticas básicas
        $totalCommandes = Auth::user()->commandes()->count();
        $pendingCommandes = Auth::user()->commandes()->where('statue', 'pending')->count();
        $inProgressCommandes = Auth::user()->commandes()->where('statue', 'in_progress')->count();
        $completedCommandes = Auth::user()->commandes()->where('statue', 'completed')->count();
        
        return view('commandes.index', compact(
            'commandes', 
            'totalCommandes', 
            'pendingCommandes', 
            'inProgressCommandes', 
            'completedCommandes'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('commandes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'specialist' => 'required|string|max:255',
            'budget' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,avi,mov|max:10240000', // 100MB max
        ]);

        $mediaFiles = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('commandes_media', 'public');
                $mediaFiles[] = [
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'name' => $file->getClientOriginalName()
                ];
            }
        }

        if ($request->has('media_url')) {
            foreach ($request->media_url as $url) {
                if (!empty($url)) {
                    $mediaFiles[] = [
                        'url' => $url,
                        'type' => 'url',
                        'name' => $url
                    ];
                }
            }
        }

        $commande = Auth::user()->commandes()->create([
            'titre' => $request->titre,
            'description' => $request->description,
            'specialist' => $request->specialist,
            'statue' => 'pending',
            'budget' => $request->budget,
            'budget_currency' => 'DZD',
            'address' => $request->address,
            'media' => !empty($mediaFiles) ? $mediaFiles : null,
        ]);

        return redirect()->route('commandes.show', $commande)
            ->with('success', 'تم إنشاء المشروع بنجاح!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Commande $commande)
    {
        // Check if the user is authorized to view the commande
        $this->authorize('view', $commande);

        return view('commandes.show', compact('commande'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commande $commande)
    {
        // Check if the user is authorized to edit the commande
        $this->authorize('update', $commande);

        return view('commandes.edit', compact('commande'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commande $commande)
    {
        // Check if the user is authorized to update the commande
        $this->authorize('update', $commande);

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'specialist' => 'required|string|max:255',
            'budget' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,avi,mov|max:10240000', // 100MB max
            'deleted_media' => 'nullable|array',
            'deleted_media.*' => 'nullable|integer',
        ]);

        // تسجيل محتويات الطلب للتحقق
        Log::info('Request contains deleted_media: ' . ($request->has('deleted_media') ? 'Yes' : 'No'));
        Log::info('Request all data: ' . json_encode($request->all()));
        
        $mediaFiles = $commande->media ?: [];
        
        // Handle file media
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('commandes_media', 'public');
                $mediaFiles[] = [
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'name' => $file->getClientOriginalName()
                ];
            }
        }

        // Handle URL media
        if ($request->has('media_url')) {
            foreach ($request->media_url as $url) {
                if (!empty($url)) {
                    $mediaFiles[] = [
                        'url' => $url,
                        'type' => 'url',
                        'name' => $url
                    ];
                }
            }
        }

        // Handle deleted media
        if ($request->has('deleted_media')) {
            $deletedIndices = $request->deleted_media;
            Log::info('Deleting media with indices: ' . json_encode($deletedIndices));
            
            // Convertir a enteros los índices
            $deletedIndices = array_map('intval', $deletedIndices);
            
            $keepMediaFiles = [];
            foreach ($mediaFiles as $index => $media) {
                if (!in_array($index, $deletedIndices)) {
                    $keepMediaFiles[] = $media;
                } else {
                    Log::info('Deleting media item: ' . json_encode($media));
                    // Delete file from storage if it's a file (not a URL)
                    if (isset($media['path'])) {
                        Storage::disk('public')->delete($media['path']);
                        Log::info('Deleted file from storage: ' . $media['path']);
                    }
                }
            }
            $mediaFiles = $keepMediaFiles;
        }

        $commande->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'specialist' => $request->specialist,
            'budget' => $request->budget,
            'budget_currency' => 'DZD',
            'address' => $request->address,
            'media' => !empty($mediaFiles) ? $mediaFiles : null,
        ]);

        return redirect()->route('commandes.show', $commande)
            ->with('success', 'تم تحديث المشروع بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commande $commande)
    {
        // Check if the user is authorized to delete the commande
        $this->authorize('delete', $commande);

        // Delete all media files
        if ($commande->media) {
            foreach ($commande->media as $media) {
                if (isset($media['path'])) {
                    Storage::disk('public')->delete($media['path']);
                }
            }
        }

        $commande->delete();

        return redirect()->route('commandes.index')
            ->with('success', 'تم حذف المشروع بنجاح!');
    }
}
