<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Offre;
use App\Models\Work;
use App\Models\User;
use App\Services\ParticipantApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $participantApiService;

    public function __construct(ParticipantApiService $participantApiService)
    {
        $this->middleware('auth');
        $this->participantApiService = $participantApiService;
    }

    public function index()
    {
        $user = auth()->user();
        $stats = new \stdClass();

        // Common statistics for the dashboard
        if ($user->role === 'craftsman') {
            $craftsman = $user->craftsman;
            
            // Check if craftsman has completed their profile
            if ($craftsman && !$craftsman->phone_secondary) {
                return redirect()->route('craftsman.complete-profile')
                    ->with('warning', 'يرجى إكمال ملفك الشخصي قبل الوصول إلى لوحة التحكم.');
            }
            
            // Get participant data from API using the stored API ID
            $participant = null;
            
            if ($craftsman && $craftsman->api_id) {
                $participant = $this->participantApiService->findById($craftsman->api_id);
                
                if (!$participant) {
                    // Try to fetch by ID number as a fallback
                    $idNumber = Session::get('craftsman_id_number');
                    if ($idNumber) {
                        $participant = $this->participantApiService->findByIdNumber($idNumber);
                    }
                }
            } else {
                // Try to fetch by ID number as a fallback
                $idNumber = Session::get('craftsman_id_number');
                if ($idNumber) {
                    $participant = $this->participantApiService->findByIdNumber($idNumber);
                    
                    // Update the craftsman with the API ID for future use
                    if ($participant && $craftsman) {
                        $craftsman->api_id = $participant['id'];
                        $craftsman->save();
                    }
                }
            }
            
            // Craftsman statistics
            $stats->total_offers = Offre::where('user_id', $user->id)->count();
            $stats->pending_offers = Offre::where('user_id', $user->id)->where('status', 'pending')->count();
            $stats->accepted_offers = Offre::where('user_id', $user->id)->where('status', 'accepted')->count();
            $stats->rejected_offers = Offre::where('user_id', $user->id)->where('status', 'rejected')->count();
            $stats->portfolio_items = Work::where('user_id', $user->id)->count();
            
            // Average rating if available
            $stats->avg_rating = Offre::where('user_id', $user->id)
                ->whereNotNull('rating')
                ->avg('rating') ?? 0;
                
            // Get specialty for matching projects
            $specialty = Session::get('craftsman_specialty');
            
            // استخدام قيمة التخصص من نموذج Craftsman إذا كانت موجودة
            if (!$specialty && $craftsman && !empty($craftsman->craft)) {
                $specialty = $craftsman->craft;
                Log::info('Using specialty from craftsman model in HomeController', [
                    'specialty' => $specialty
                ]);
                
                // تخزين التخصص في الجلسة للاستخدام اللاحق
                Session::put('craftsman_specialty', $specialty);
            }
            
            // If specialty is not set, use a default from the participant data if available
            if (!$specialty && $participant) {
                // تحقق من وجود 'specialization_name' في بيانات API
                if (isset($participant['specialization_name'])) {
                    Log::info('Found specialization_name in API data in HomeController', [
                        'specialization_name' => $participant['specialization_name']
                    ]);
                    
                // Map the API specialization to our system specialization
                $specialtyMapping = [
                    'الهندسة الكهربائية' => 'الهندسة الكهربائية',
                    'النجارة' => 'النجارة العامة',
                    'الحدادة' => 'الحدادة الفنية',
                    'الميكانيك' => 'الميكانيك العام',
                    'السباكة' => 'السباكة والتدفئة',
                    'الخياطة' => 'الخياطة وتصميم الأزياء',
                    'تكييف' => 'تكييف الهواء',
                    'البناء' => 'البناء'
                ];
                
                foreach ($specialtyMapping as $apiKey => $systemValue) {
                    if (strpos($participant['specialization_name'], $apiKey) !== false) {
                        $specialty = $systemValue;
                        break;
                        }
                    }
                }
                // البحث عن حقول أخرى في API تحتوي على معلومات التخصص
                elseif (isset($participant['craft']) || isset($participant['specialty']) || isset($participant['specialization'])) {
                    $apiSpecialty = $participant['craft'] ?? $participant['specialty'] ?? $participant['specialization'] ?? null;
                    
                    if ($apiSpecialty) {
                        Log::info('Found specialty-related field in API data in HomeController', [
                            'field' => isset($participant['craft']) ? 'craft' : (isset($participant['specialty']) ? 'specialty' : 'specialization'),
                            'value' => $apiSpecialty
                        ]);
                        
                        // تعيين التخصص من API مباشرة
                        $specialty = $apiSpecialty;
                    }
                }
                
                // Default to the first available specialty if no match found
                if (!$specialty) {
                    $specialty = 'الهندسة الكهربائية';
                    Log::warning('Could not find specialty in API data in HomeController, using default', [
                        'default_specialty' => $specialty
                    ]);
                } else {
                    Log::info('Set specialty from participant data in HomeController', [
                        'specialty' => $specialty
                    ]);
                }
                
                // تخزين التخصص في الجلسة للاستخدام اللاحق
                Session::put('craftsman_specialty', $specialty);
                
                // تحديث حقل craft في نموذج Craftsman
                if ($craftsman && empty($craftsman->craft)) {
                    $craftsman->craft = $specialty;
                    $craftsman->save();
                    
                    Log::info('Updated craftsman model with specialty in HomeController', [
                        'craftsman_id' => $craftsman->id,
                        'specialty' => $specialty
                    ]);
                }
            }
            
            // Ensure we have a specialty to search for
            if (!$specialty) {
                $specialty = 'الهندسة الكهربائية'; // Default fallback
                Log::warning('Using default specialty as fallback in HomeController', [
                    'specialty' => $specialty
                ]);
                
                // تخزين التخصص الافتراضي في الجلسة
                Session::put('craftsman_specialty', $specialty);
            }
            
            // استخدام عنوان المستخدم كولاية الحرفي (address في نموذج User)
            $craftsmanProvince = $user->address;
            
            // التأكد من أن لدينا ولاية صالحة للحرفي
            if (empty($craftsmanProvince) || $craftsmanProvince === 'غير محدد') {
                // إذا كان عنوان المستخدم غير محدد، نحاول الحصول عليه من session أو API
                $sessionProvince = Session::get('craftsman_province');
                if (!empty($sessionProvince)) {
                    $craftsmanProvince = $sessionProvince;
                    Log::info('Using craftsman province from session in HomeController', [
                        'province' => $craftsmanProvince
                    ]);
                } elseif ($participant && isset($participant['address'])) {
                    $craftsmanProvince = $participant['address'];
                    Log::info('Using craftsman province from API in HomeController', [
                        'province' => $craftsmanProvince
                    ]);
                    
                    // تخزين العنوان في جلسة المستخدم للاستخدام اللاحق
                    Session::put('craftsman_province', $craftsmanProvince);
                    
                    // تحديث عنوان المستخدم في قاعدة البيانات
                    // Si el valor de la dirección es un nombre de provincia, buscar su código correspondiente
                    if (!is_numeric($craftsmanProvince) && strlen($craftsmanProvince) > 5) {
                        // Buscar el código de provincia por su nombre
                        $provinceCode = null;
                        $provinces = \App\Helpers\AlgerianProvinces::getProvinces();
                        foreach ($provinces as $code => $name) {
                            if (trim(strtolower($name)) === trim(strtolower($craftsmanProvince))) {
                                $provinceCode = $code;
                                break;
                            }
                        }
                        
                        // Si encontramos un código, lo usamos; de lo contrario, usamos el predeterminado
                        if ($provinceCode) {
                            $user->address = $provinceCode;
                        } else {
                            $user->address = '16'; // Default to Algiers if no match
                        }
                    } else if (is_numeric($craftsmanProvince) || strlen($craftsmanProvince) <= 5) {
                        // Si ya es un código (numérico o corto), usarlo directamente
                        $user->address = $craftsmanProvince;
                    } else {
                        // Si no se puede determinar, usar el predeterminado
                        $user->address = '16'; // Default to Algiers
                    }
                    
                    $user->save();
                    Log::info('Updated user address with province from API in HomeController', [
                        'user_id' => $user->id,
                        'province' => $user->address
                    ]);
                } else {
                    // إذا لم نستطع العثور على ولاية، استخدم قيمة افتراضية
                    $craftsmanProvince = '16'; // الجزائر العاصمة كقيمة افتراضية
                    Log::warning('No province found for craftsman in HomeController, using default', [
                        'default_province' => $craftsmanProvince
                    ]);
                }
            }
            
            Log::info('Craftsman province in HomeController', [
                'province' => $craftsmanProvince,
                'province_name' => \App\Helpers\AlgerianProvinces::getProvinceName($craftsmanProvince)
            ]);
            
            // تحقق خاص بولاية الجزائر
            $isAlgiers = false;
            if ($craftsmanProvince == 'الجزائر' || $craftsmanProvince == 'الجزائر ' || $craftsmanProvince == '16') {
                $isAlgiers = true;
                Log::info('Craftsman province is Algiers in HomeController', [
                    'province' => $craftsmanProvince
                ]);
            }
            
            // بحث عن المشاريع المطابقة لتخصص الحرفي
            $query = Commande::where('specialist', $specialty)
                ->where('statue', 'pending');
                
            // Verificar si se solicita filtrar por provincia desde la solicitud HTTP (request)
            $provinceFilter = request('province');
            if (!empty($provinceFilter)) {
                Log::info('Filtering projects by specific province in HomeController', [
                    'province_filter' => $provinceFilter
                ]);
                
                if ($provinceFilter == 'الجزائر' || $provinceFilter == 'الجزائر ' || $provinceFilter == '16') {
                    // Si se busca Argel, incluir todas las variantes posibles
                    $query->where(function($q) {
                        $q->where('address', 'الجزائر')
                          ->orWhere('address', 'الجزائر ')
                          ->orWhere('address', '16')
                          ->orWhere('address', 16);
                    });
                } else {
                    // Para otras provincias, buscar coincidencias exactas y variantes
                    $query->where(function($q) use ($provinceFilter) {
                        $q->where('address', $provinceFilter)
                          ->orWhere('address', trim($provinceFilter));
                          
                        // Si es un número, buscar también el nombre de la provincia
                        if (is_numeric($provinceFilter)) {
                            $provinceName = \App\Helpers\AlgerianProvinces::getProvinceName($provinceFilter);
                            if ($provinceName) {
                                $q->orWhere('address', $provinceName);
                            }
                        }
                        // Si es un nombre, buscar también el código de la provincia
                        else {
                            $provinces = \App\Helpers\AlgerianProvinces::getProvinces();
                            foreach ($provinces as $code => $name) {
                                if (trim(strtolower($name)) === trim(strtolower($provinceFilter))) {
                                    $q->orWhere('address', $code)
                                      ->orWhere('address', (int)$code);
                                    break;
                                }
                            }
                        }
                    });
                }
            }
            // No aplicamos filtro automático por provincia, solo mostramos proyectos según la especialidad
            
            // تنفيذ الاستعلام
            $matchingProjects = $query->orderBy('created_at', 'desc')->paginate(5);
                
            // استخراج 5 مشاريع فقط من المشاريع المطابقة للتخصص لعرضها للتشخيص
            $specialtyOnlyProjects = Commande::where('specialist', $specialty)
                ->where('statue', 'pending')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
                
            return view('dashboard', [
                'participant' => $participant,
                'craftsman' => $craftsman,
                'matchingProjects' => $matchingProjects,
                'specialtyOnlyProjects' => $specialtyOnlyProjects,
                'craftsmanProvince' => $craftsmanProvince,
                'craftsmanProvinceName' => \App\Helpers\AlgerianProvinces::getProvinceName($craftsmanProvince),
                'specialty' => $specialty,
                'stats' => $stats
            ]);
        } 
        elseif ($user->role === 'client') {
            // Client statistics
            $stats->total_projects = Commande::where('user_id', $user->id)->count();
            $stats->pending_projects = Commande::where('user_id', $user->id)->where('statue', 'pending')->count();
            $stats->in_progress_projects = Commande::where('user_id', $user->id)->where('statue', 'in_progress')->count();
            $stats->completed_projects = Commande::where('user_id', $user->id)->where('statue', 'completed')->count();
            
            // Get number of offers for the client's projects
            $stats->total_offers = Offre::whereHas('commande', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();
            
            // Recent projects
            $recentProjects = Commande::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
                
            return view('dashboard', [
                'stats' => $stats,
                'recentProjects' => $recentProjects
            ]);
        }
        else if ($user->role === 'admin') {
            // Admin statistics
            $stats->total_users = User::count();
            $stats->total_craftsmen = User::where('role', 'craftsman')->count();
            $stats->total_clients = User::where('role', 'client')->count();
            $stats->total_projects = Commande::count();
            $stats->total_offers = Offre::count();
            $stats->pending_offers = Offre::where('status', 'pending')->count();
            $stats->accepted_offers = Offre::where('status', 'accepted')->count();
            
            // Recent activity
            $recentProjects = Commande::orderBy('created_at', 'desc')
                ->take(5)
                ->get();
                
            $recentOffers = Offre::with(['commande', 'craftsman.user'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
                
            return view('dashboard', [
                'stats' => $stats,
                'recentProjects' => $recentProjects,
                'recentOffers' => $recentOffers
            ]);
        }
        
        // Default dashboard
        return view('dashboard');
    }
} 