<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientOffreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:client');
    }

    /**
     * Display a listing of the offers received by the client.
     */
    public function index(Request $request)
    {
        // الحصول على قائمة المشاريع الخاصة بالعميل الحالي
        $commandeIds = Commande::where('user_id', Auth::id())->pluck('id');
        
        // استعلام العروض المرتبطة بمشاريع العميل
        $query = Offre::whereIn('commande_id', $commandeIds)
            ->with(['commande', 'user']);

        // البحث حسب حالة العرض
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // البحث حسب رقم المشروع
        if ($request->has('commande_id') && !empty($request->commande_id)) {
            $query->where('commande_id', $request->commande_id);
        }

        // البحث حسب الحرفي
        if ($request->has('craftsman_id') && !empty($request->craftsman_id)) {
            $query->where('user_id', $request->craftsman_id);
        }

        // ترتيب العروض
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $offres = $query->paginate(10);
        
        // إحصائيات العروض
        $pendingCount = Offre::whereIn('commande_id', $commandeIds)->where('status', 'pending')->count();
        $acceptedCount = Offre::whereIn('commande_id', $commandeIds)->where('status', 'accepted')->count();
        $rejectedCount = Offre::whereIn('commande_id', $commandeIds)->where('status', 'rejected')->count();
        $completedCount = Offre::whereIn('commande_id', $commandeIds)->where('status', 'completed')->count();
        $totalCount = Offre::whereIn('commande_id', $commandeIds)->count();

        // قائمة المشاريع للفلترة
        $commandes = Commande::where('user_id', Auth::id())->get();

        return view('client.offres.index', compact(
            'offres', 
            'pendingCount', 
            'acceptedCount', 
            'rejectedCount',
            'completedCount',
            'totalCount',
            'commandes'
        ));
    }

    /**
     * Show the details of a specific offer.
     */
    public function show(Offre $offre)
    {
        // التحقق من أن العرض ينتمي لمشروع العميل
        $commande = Commande::findOrFail($offre->commande_id);
        
        if ($commande->user_id !== Auth::id()) {
            abort(403, 'غير مصرح بالوصول إلى هذا العرض.');
        }

        // تحميل علاقات المشروع والمستخدم والحرفي وأعماله
        $offre->load([
            'commande', 
            'user',
            'user.craftsman'
        ]);
        
        return view('client.offres.show', compact('offre'));
    }

    /**
     * Update the status of an offer.
     */
    public function updateStatus(Request $request, Offre $offre)
    {
        // التحقق من أن العرض ينتمي لمشروع العميل
        $commande = Commande::findOrFail($offre->commande_id);
        
        if ($commande->user_id !== Auth::id()) {
            abort(403, 'غير مصرح بالوصول إلى هذا العرض.');
        }

        $request->validate([
            'status' => 'required|in:pending,accepted,rejected,completed',
        ]);

        // إذا تم قبول عرض، يتم رفض جميع العروض الأخرى لنفس المشروع
        if ($request->status === 'accepted') {
            Offre::where('commande_id', $offre->commande_id)
                ->where('id', '!=', $offre->id)
                ->update(['status' => 'rejected']);
                
            // تسجيل حالة المشروع قبل التحديث
            \Log::info("Project status before update: " . $commande->statue);
                
            // تحديث حالة المشروع إلى "قيد التنفيذ"
            $commande->update(['statue' => 'in_progress']);
            
            // إعادة تحميل المشروع للتأكد من التحديث
            $commande->refresh();
            
            // إذا لم يتم التحديث، نحاول استخدام استعلام SQL مباشر
            if ($commande->statue !== 'in_progress') {
                \Log::warning("Regular update failed, trying direct SQL update for project ID: " . $commande->id);
                DB::table('commandes')
                    ->where('id', $commande->id)
                    ->update(['statue' => 'in_progress']);
                
                $commande->refresh();
            }
            
            // تسجيل حالة المشروع بعد التحديث
            \Log::info("Project status after update: " . $commande->statue);
        }
        
        // إذا تم تغيير الحالة إلى مكتمل، تحديث حالة المشروع إلى "مكتمل"
        if ($request->status === 'completed') {
            // تسجيل حالة المشروع قبل التحديث
            \Log::info("Project status before completion: " . $commande->statue);
                
            $commande->update(['statue' => 'completed']);
            
            // إعادة تحميل المشروع للتأكد من التحديث
            $commande->refresh();
            
            // إذا لم يتم التحديث، نحاول استخدام استعلام SQL مباشر
            if ($commande->statue !== 'completed') {
                \Log::warning("Regular update failed, trying direct SQL update for project ID: " . $commande->id);
                DB::table('commandes')
                    ->where('id', $commande->id)
                    ->update(['statue' => 'completed']);
                
                $commande->refresh();
            }
            
            // تسجيل حالة المشروع بعد التحديث
            \Log::info("Project status after completion: " . $commande->statue);
        }

        $offre->update(['status' => $request->status]);

        // تسجيل معلومات نهائية
        \Log::info("Final offer status: " . $offre->status . ", project status: " . $commande->statue);

        return redirect()->back()->with('status', 'تم تحديث حالة العرض بنجاح.');
    }

    /**
     * Rate a craftsman after completing the job.
     */
    public function rateCraftsman(Request $request, Offre $offre)
    {
        // التحقق من أن العرض ينتمي لمشروع العميل وتم إكماله
        $commande = Commande::findOrFail($offre->commande_id);
        
        // إضافة معلومات تصحيح
        \Log::info("Attempting to rate craftsman for offer ID: " . $offre->id);
        \Log::info("Current offer status: " . $offre->status);
        \Log::info("Offer belongs to commande: " . $commande->id . " (user_id: " . $commande->user_id . ")");
        \Log::info("Current user ID: " . Auth::id());
        
        // تغيير الشرط للسماح بتقييم العروض حتى لو لم تكن مكتملة
        if ($commande->user_id !== Auth::id()) {
            \Log::error("Permission denied: Offer does not belong to this client");
            abort(403, 'غير مصرح بتقييم هذا الحرفي.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);

        // تعديل حالة العرض إلى مكتمل إذا لم يكن كذلك
        if ($offre->status !== 'completed') {
            \Log::info("Updating offer status to completed before rating");
            
            // تسجيل حالة المشروع قبل التحديث
            \Log::info("Project status before completion in rating: " . $commande->statue);
            
            $offre->status = 'completed';
            $commande->update(['statue' => 'completed']);
            
            // إعادة تحميل المشروع للتأكد من التحديث
            $commande->refresh();
            
            // إذا لم يتم التحديث، نحاول استخدام استعلام SQL مباشر
            if ($commande->statue !== 'completed') {
                \Log::warning("Regular update failed in rating, trying direct SQL update for project ID: " . $commande->id);
                DB::table('commandes')
                    ->where('id', $commande->id)
                    ->update(['statue' => 'completed']);
                
                $commande->refresh();
            }
            
            // تسجيل حالة المشروع بعد التحديث
            \Log::info("Project status after completion in rating: " . $commande->statue);
        }

        // حفظ التقييم والمراجعة
        $offre->update([
            'rating' => $request->rating,
            'review' => $request->review,
            'status' => 'completed'
        ]);

        \Log::info("Offer rated successfully: rating=" . $request->rating . ", review=" . $request->review);

        // تحديث متوسط تقييم الحرفي
        $craftsman = User::findOrFail($offre->user_id);
        $averageRating = Offre::where('user_id', $craftsman->id)
            ->whereNotNull('rating')
            ->avg('rating');
            
        \Log::info("Calculated average rating for craftsman: " . $averageRating);
            
        // الصحيح من أن المتوسط ليس فارغًا قبل التحديث
        if ($averageRating !== null) {
            // التحقق من وجود سجل حرفي لهذا المستخدم
            $craftsmanRecord = $craftsman->craftsman;
            
            if ($craftsmanRecord) {
                try {
                    $craftsmanRecord->update(['rating' => $averageRating]);
                    \Log::info("Craftsman rating updated successfully: " . $averageRating);
                } catch (\Exception $e) {
                    \Log::error("Error updating craftsman rating: " . $e->getMessage());
                }
            } else {
                \Log::error("No craftsman record found for user ID: " . $craftsman->id);
            }
        } else {
            \Log::error("Average rating is null, cannot update craftsman record");
        }

        return redirect()->back()->with('status', 'تم تقييم الحرفي بنجاح.');
    }

    /**
     * حذف عرض من قائمة العروض الواردة للعميل
     */
    public function destroy(Offre $offre)
    {
        // التحقق من أن العرض ينتمي لمشروع العميل
        $commande = Commande::findOrFail($offre->commande_id);
        
        if ($commande->user_id !== Auth::id()) {
            abort(403, 'غير مصرح بحذف هذا العرض.');
        }

        // التحقق من أن العرض لم يتم قبوله مسبقاً
        if ($offre->status === 'accepted') {
            return redirect()->back()->with('error', 'لا يمكن حذف عرض تم قبوله.');
        }

        try {
            // حذف العرض
            $offre->delete();
            \Log::info("Offer ID:{$offre->id} deleted successfully by client ID:" . Auth::id());
            
            return redirect()->route('client.offres.index')->with('status', 'تم حذف العرض بنجاح.');
        } catch (\Exception $e) {
            \Log::error("Error deleting offer: " . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء محاولة حذف العرض.');
        }
    }
} 