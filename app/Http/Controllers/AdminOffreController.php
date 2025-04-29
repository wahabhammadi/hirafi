<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminOffreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the offers.
     */
    public function index(Request $request)
    {
        $query = Offre::with(['user', 'commande']);

        // البحث حسب حالة العرض
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // البحث حسب رقم المشروع
        if ($request->has('commande_id') && !empty($request->commande_id)) {
            $query->where('commande_id', $request->commande_id);
        }

        // البحث حسب الحرفي
        if ($request->has('craftsman') && !empty($request->craftsman)) {
            $craftsmanIds = User::where('name', 'like', '%' . $request->craftsman . '%')
                ->where('role', 'craftsman')
                ->pluck('id');
            $query->whereIn('user_id', $craftsmanIds);
        }

        // البحث حسب تاريخ التسليم
        if ($request->has('delivery_date') && !empty($request->delivery_date)) {
            $query->whereDate('delivery_date', $request->delivery_date);
        }

        // ترتيب العروض
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $offres = $query->paginate(10);
        
        // عدد العروض حسب الحالة
        $pendingCount = Offre::where('status', 'pending')->count();
        $acceptedCount = Offre::where('status', 'accepted')->count();
        $rejectedCount = Offre::where('status', 'rejected')->count();
        $totalCount = Offre::count();

        return view('admin.offres.index', compact(
            'offres', 
            'pendingCount', 
            'acceptedCount', 
            'rejectedCount', 
            'totalCount'
        ));
    }

    /**
     * Show the details of a specific offer.
     */
    public function show(Offre $offre)
    {
        $offre->load(['user', 'commande']);
        return view('admin.offres.show', compact('offre'));
    }

    /**
     * Update the status of an offer.
     */
    public function updateStatus(Request $request, Offre $offre)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        // تحديث حالة العرض
        $offre->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'تم تحديث حالة العرض بنجاح');
    }

    /**
     * Export offers to CSV.
     */
    public function export()
    {
        $offres = Offre::with(['user', 'commande'])->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="offers.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($offres) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'رقم', 'رقم المشروع', 'عنوان المشروع', 'الحرفي', 'السعر', 'تاريخ التسليم', 'الحالة', 'تاريخ الإنشاء'
            ]);
            
            foreach ($offres as $offre) {
                $status = '';
                if ($offre->status === 'pending') $status = 'قيد الانتظار';
                elseif ($offre->status === 'accepted') $status = 'مقبول';
                elseif ($offre->status === 'rejected') $status = 'مرفوض';
                
                fputcsv($file, [
                    $offre->id,
                    $offre->commande_id,
                    $offre->commande->titre,
                    $offre->user->name,
                    $offre->price,
                    $offre->delivery_date->format('Y-m-d'),
                    $status,
                    $offre->created_at->format('Y-m-d H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
} 