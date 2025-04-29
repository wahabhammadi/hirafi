<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\Commande;
use App\Services\CurrencyConversionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OffreController extends Controller
{
    /**
     * خدمة تحويل العملة
     */
    protected $currencyService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CurrencyConversionService $currencyService)
    {
        $this->middleware('auth');
        $this->middleware('role:craftsman');
        $this->currencyService = $currencyService;
    }

    /**
     * Show the form for creating a new offer.
     */
    public function create(Commande $commande)
    {
        // تحقق مما إذا كان المستخدم الحالي أضاف عرضًا بالفعل لهذا المشروع
        $existingOffer = Offre::where('user_id', Auth::id())
            ->where('commande_id', $commande->id)
            ->first();

        if ($existingOffer) {
            return redirect()->route('offres.edit', $existingOffer)
                ->with('info', 'لديك عرض موجود بالفعل لهذا المشروع. يمكنك تعديله هنا.');
        }

        // جلب سعر الصرف الحالي - نستخدم سعر تحويل DZD إلى SAR
        $exchangeRate = $this->currencyService->getExchangeRate('DZD', 'SAR');
        
        return view('offres.create', compact('commande', 'exchangeRate'));
    }

    /**
     * Store a newly created offer in storage.
     */
    public function store(Request $request, Commande $commande)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'delivery_date' => 'required|date|after:today',
            'description' => 'required|string',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,avi,mov|max:10240', // 10MB max
            'media_url' => 'nullable|array',
            'media_url.*' => 'nullable|url',
        ]);

        // الحصول على الحرفي المرتبط بالمستخدم الحالي
        $user = Auth::user();
        $craftsman = $user->craftsman;
        
        if (!$craftsman) {
            return redirect()->back()->with('error', 'لا يمكنك تقديم عرض لأنك لست مسجلاً كحرفي.');
        }

        $mediaFiles = [];
        
        // Handle file media
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('offres_media', 'public');
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

        Offre::create([
            'user_id' => Auth::id(),
            'craftsman_id' => $craftsman->id,
            'commande_id' => $commande->id,
            'price' => $request->price,
            'price_currency' => 'DZD', // تعيين العملة إلى الدينار الجزائري
            'delivery_date' => $request->delivery_date,
            'description' => $request->description,
            'media' => !empty($mediaFiles) ? $mediaFiles : null,
            'status' => 'pending',
        ]);

        return redirect()->route('commandes.show', $commande)
            ->with('success', 'تم تقديم العرض بنجاح!');
    }
    
    /**
     * Show the form for editing the specified offer.
     */
    public function edit(Offre $offre)
    {
        // تحقق من أن المستخدم الحالي هو صاحب العرض
        if ($offre->user_id !== Auth::id()) {
            abort(403, 'غير مصرح بالوصول إلى هذا العرض.');
        }

        $commande = $offre->commande;
        $exchangeRate = $this->currencyService->getExchangeRate('DZD', 'SAR');
        
        return view('offres.edit', compact('offre', 'commande', 'exchangeRate'));
    }

    /**
     * Update the specified offer in storage.
     */
    public function update(Request $request, Offre $offre)
    {
        // تحقق من أن المستخدم الحالي هو صاحب العرض
        if ($offre->user_id !== Auth::id()) {
            abort(403, 'غير مصرح بالوصول إلى هذا العرض.');
        }
        
        // تحقق من أن العرض لا يزال معلقًا
        if ($offre->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'لا يمكن تعديل العرض بعد قبوله أو رفضه.');
        }

        $request->validate([
            'price' => 'required|numeric|min:0',
            'delivery_date' => 'required|date|after:today',
            'description' => 'required|string',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,avi,mov|max:10240', // 10MB max
            'media_url' => 'nullable|array',
            'media_url.*' => 'nullable|url',
            'deleted_media' => 'nullable|array',
            'deleted_media.*' => 'nullable|integer',
        ]);

        $mediaFiles = $offre->media ?: [];
        
        // Handle file media
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('offres_media', 'public');
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
            $deletedIndices = array_map('intval', $deletedIndices);
            
            $keepMediaFiles = [];
            foreach ($mediaFiles as $index => $media) {
                if (!in_array($index, $deletedIndices)) {
                    $keepMediaFiles[] = $media;
                } else {
                    // Delete file from storage if it's a file (not a URL)
                    if (isset($media['path'])) {
                        Storage::disk('public')->delete($media['path']);
                    }
                }
            }
            $mediaFiles = $keepMediaFiles;
        }

        // تحديث العرض مع الحفاظ على العملة كدينار جزائري
        $offre->update([
            'price' => $request->price,
            'price_currency' => 'DZD', // تأكيد على استخدام الدينار الجزائري
            'delivery_date' => $request->delivery_date,
            'description' => $request->description,
            'media' => !empty($mediaFiles) ? $mediaFiles : null,
        ]);

        return redirect()->route('commandes.show', $offre->commande_id)
            ->with('success', 'تم تحديث العرض بنجاح!');
    }

    /**
     * Remove the specified offer from storage.
     */
    public function destroy(Offre $offre)
    {
        // تسجيل المحاولة للتتبع
        Log::info('Attempting to delete offer', [
            'offer_id' => $offre->id,
            'user_id' => Auth::id(),
            'offer_user_id' => $offre->user_id,
            'offer_status' => $offre->status
        ]);

        // تحقق من أن المستخدم الحالي هو صاحب العرض
        if ($offre->user_id !== Auth::id()) {
            Log::warning('Unauthorized access to delete offer', [
                'offer_id' => $offre->id,
                'user_id' => Auth::id(),
                'offer_user_id' => $offre->user_id
            ]);
            
            abort(403, 'غير مصرح بحذف هذا العرض.');
        }
        
        // تحقق من أن العرض لا يزال معلقًا
        if ($offre->status !== 'pending') {
            Log::warning('Attempt to delete non-pending offer', [
                'offer_id' => $offre->id,
                'status' => $offre->status
            ]);
            
            return redirect()->back()
                ->with('error', 'لا يمكن حذف العرض بعد قبوله أو رفضه.');
        }

        // احفظ معرف المشروع قبل حذف العرض
        $commandeId = $offre->commande_id;

        try {
            // حذف الملفات المرتبطة بالعرض
            if ($offre->media) {
                foreach ($offre->media as $media) {
                    if (isset($media['path'])) {
                        Storage::disk('public')->delete($media['path']);
                    }
                }
            }

            // حذف العرض من قاعدة البيانات
            $offre->delete();
            
            Log::info('Offer deleted successfully', [
                'offer_id' => $offre->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('craftsman.offres.index')
                ->with('success', 'تم حذف العرض بنجاح!');
        } catch (\Exception $e) {
            Log::error('Error deleting offer', [
                'offer_id' => $offre->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء محاولة حذف العرض. الرجاء المحاولة مرة أخرى.');
        }
    }
}
