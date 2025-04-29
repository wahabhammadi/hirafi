<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CraftsmanOffreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:craftsman');
    }

    /**
     * Display a listing of the craftsman's offers.
     */
    public function index(Request $request)
    {
        $query = Offre::where('user_id', Auth::id())
            ->with(['commande']);

        // البحث حسب حالة العرض
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // البحث حسب رقم المشروع
        if ($request->has('commande_id') && !empty($request->commande_id)) {
            $query->where('commande_id', $request->commande_id);
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
        $pendingCount = Offre::where('user_id', Auth::id())->where('status', 'pending')->count();
        $acceptedCount = Offre::where('user_id', Auth::id())->where('status', 'accepted')->count();
        $rejectedCount = Offre::where('user_id', Auth::id())->where('status', 'rejected')->count();
        $totalCount = Offre::where('user_id', Auth::id())->count();

        return view('craftsman.offres.index', compact(
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
        // التأكد من أن الحرفي يستعرض عرضه الخاص به
        if ($offre->user_id !== Auth::id()) {
            abort(403, 'غير مصرح بالوصول إلى هذا العرض.');
        }

        $offre->load(['commande']);
        return view('craftsman.offres.show', compact('offre'));
    }

    /**
     * Show all ratings and reviews that the craftsman has received.
     */
    public function ratings()
    {
        // الحصول على جميع العروض المقيمة للحرفي الحالي (بغض النظر عن الحالة)
        $userId = Auth::id();
        
        \Log::info("Fetching ratings for craftsman with user_id: " . $userId);
        
        // تعديل الاستعلام ليشمل جميع العروض المقيمة بغض النظر عن الحالة
        $ratedOffres = Offre::where('user_id', $userId)
                        ->whereNotNull('rating')
                        ->with(['commande'])
                        ->orderBy('updated_at', 'desc')
                        ->paginate(10);
        
        \Log::info("Total rated offers (any status): " . $ratedOffres->total());
        
        // التحقق من وجود تقييمات
        foreach ($ratedOffres as $offre) {
            \Log::info("Offer ID: " . $offre->id . ", Status: " . $offre->status . ", Rating: " . $offre->rating . ", Review: " . $offre->review);
        }
        
        // حساب متوسط التقييمات
        $averageRating = Offre::where('user_id', $userId)
                        ->whereNotNull('rating')
                        ->avg('rating');
        
        \Log::info("Average rating: " . $averageRating);
        
        // عدد التقييمات
        $ratingsCount = Offre::where('user_id', $userId)
                        ->whereNotNull('rating')
                        ->count();
        
        \Log::info("Ratings count: " . $ratingsCount);

        return view('craftsman.ratings', compact('ratedOffres', 'averageRating', 'ratingsCount'));
    }
} 