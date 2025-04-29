<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Services\ParticipantApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Helpers\AlgerianProvinces;

class CraftsmanDashboardController extends Controller
{
    protected $participantApiService;

    public function __construct(ParticipantApiService $participantApiService)
    {
        $this->participantApiService = $participantApiService;
    }

    /**
     * Show the craftsman dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $craftsman = $user->craftsman;
        
        // Check if craftsman has completed their profile
        if (!$craftsman->phone_secondary) {
            return redirect()->route('craftsman.complete-profile')
                ->with('warning', 'يرجى إكمال ملفك الشخصي قبل الوصول إلى لوحة التحكم.');
        }
        
        // Get participant data from API using the stored API ID
        $participant = null;
        
        if ($craftsman->api_id) {
            Log::info('Fetching participant data from API using stored API ID', [
                'api_id' => $craftsman->api_id
            ]);
            
            $participant = $this->participantApiService->findById($craftsman->api_id);
            
            if (!$participant) {
                Log::warning('Could not fetch participant data for API ID: ' . $craftsman->api_id);
                
                // Try to fetch by ID number as a fallback
                $idNumber = Session::get('craftsman_id_number');
                if ($idNumber) {
                    Log::info('Trying to fetch participant data by ID number as fallback', [
                        'id_number' => $idNumber
                    ]);
                    
                    $participant = $this->participantApiService->findByIdNumber($idNumber);
                    
                    if ($participant) {
                        Log::info('Successfully fetched participant data by ID number', [
                            'id_number' => $idNumber
                        ]);
                    } else {
                        Log::warning('Could not fetch participant data by ID number either', [
                            'id_number' => $idNumber
                        ]);
                    }
                }
            } else {
                Log::info('Participant data fetched successfully from API', [
                    'api_id' => $craftsman->api_id,
                    'name' => $participant['first_name'] . ' ' . $participant['last_name']
                ]);
            }
        } else {
            Log::warning('No API ID found for craftsman', ['craftsman_id' => $craftsman->id]);
            
            // Try to fetch by ID number as a fallback
            $idNumber = Session::get('craftsman_id_number');
            if ($idNumber) {
                Log::info('Trying to fetch participant data by ID number as fallback', [
                    'id_number' => $idNumber
                ]);
                
                $participant = $this->participantApiService->findByIdNumber($idNumber);
                
                if ($participant) {
                    Log::info('Successfully fetched participant data by ID number', [
                        'id_number' => $idNumber
                    ]);
                    
                    // Update the craftsman with the API ID for future use
                    $craftsman->api_id = $participant['id'];
                    $craftsman->save();
                    
                    Log::info('Updated craftsman with API ID', [
                        'craftsman_id' => $craftsman->id,
                        'api_id' => $participant['id']
                    ]);
                } else {
                    Log::warning('Could not fetch participant data by ID number either', [
                        'id_number' => $idNumber
                    ]);
                }
            }
        }
        
        // Get projects that match the craftsman's specialty
        $specialty = Session::get('craftsman_specialty');
        
        Log::info('Looking for craftsman specialty', [
            'craftsman_id' => $craftsman->id,
            'session_specialty' => $specialty,
            'craftsman_craft' => $craftsman->craft
        ]);
        
        // استخدام قيمة التخصص من نموذج Craftsman إذا كانت موجودة
        if (!$specialty && $craftsman && !empty($craftsman->craft)) {
            $specialty = $craftsman->craft;
            Log::info('Using specialty from craftsman model', [
                'specialty' => $specialty
            ]);
            
            // تخزين التخصص في الجلسة للاستخدام اللاحق
            Session::put('craftsman_specialty', $specialty);
        }
        
        // If specialty is not set, use a default from the participant data if available
        if (!$specialty && $participant) {
            // تحقق من وجود 'specialization_name' في بيانات API
            if (isset($participant['specialization_name'])) {
                Log::info('Found specialization_name in API data', [
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
                    Log::info('Found specialty-related field in API data', [
                        'field' => isset($participant['craft']) ? 'craft' : (isset($participant['specialty']) ? 'specialty' : 'specialization'),
                        'value' => $apiSpecialty
                    ]);
                    
                    // تعيين التخصص من API مباشرة أو مطابقته مع القيم المعروفة
                    $knownSpecialties = [
                        'الهندسة الكهربائية',
                        'النجارة العامة',
                        'الحدادة الفنية',
                        'الميكانيك العام',
                        'السباكة والتدفئة',
                        'الخياطة وتصميم الأزياء',
                        'تكييف الهواء',
                        'البناء'
                    ];
                    
                    if (in_array($apiSpecialty, $knownSpecialties)) {
                        $specialty = $apiSpecialty;
                    } else {
                        // البحث عن أقرب تطابق
                        foreach ($knownSpecialties as $knownSpecialty) {
                            if (strpos($knownSpecialty, $apiSpecialty) !== false || 
                                strpos($apiSpecialty, $knownSpecialty) !== false) {
                                $specialty = $knownSpecialty;
                                break;
                            }
                        }
                    }
                }
            }
            // إذا لم يتم العثور على حقل معروف، ابحث عن أي حقل يتعلق بالتخصص
            else {
                Log::warning('No known specialty field found in API data, available keys:', [
                    'keys' => array_keys($participant)
                ]);
                
                foreach ($participant as $key => $value) {
                    if (is_string($value) && (
                        strpos(strtolower($key), 'special') !== false || 
                        strpos(strtolower($key), 'craft') !== false || 
                        strpos(strtolower($key), 'skill') !== false ||
                        strpos(strtolower($key), 'profession') !== false
                    )) {
                        Log::info('Found potential specialty field', [
                            'key' => $key,
                            'value' => $value
                        ]);
                        
                        // استخدام هذه القيمة كتخصص
                        $specialty = $value;
                        break;
                    }
                }
            }
            
            // Default to the first available specialty if no match found
            if (!$specialty) {
                $specialty = 'الهندسة الكهربائية';
                Log::warning('Could not find specialty in API data, using default', [
                    'default_specialty' => $specialty
                ]);
            } else {
                Log::info('Set specialty from participant data', [
                    'specialty' => $specialty
                ]);
            }
            
            // تخزين التخصص في الجلسة للاستخدام اللاحق
            Session::put('craftsman_specialty', $specialty);
            
            // تحديث حقل craft في نموذج Craftsman
            if ($craftsman && empty($craftsman->craft)) {
                $craftsman->craft = $specialty;
                $craftsman->save();
                
                Log::info('Updated craftsman model with specialty', [
                    'craftsman_id' => $craftsman->id,
                'specialty' => $specialty
            ]);
            }
        }
        
        // Ensure we have a specialty to search for
        if (!$specialty) {
            $specialty = 'الهندسة الكهربائية'; // Default fallback
            Log::warning('Using default specialty as fallback', [
                'specialty' => $specialty
            ]);
            
            // تخزين التخصص الافتراضي في الجلسة
            Session::put('craftsman_specialty', $specialty);
        }
        
        Log::info('Specialty used for matching', [
            'specialty' => $specialty
        ]);
        
        // استخدام عنوان المستخدم كولاية الحرفي (address في نموذج User)
        $craftsmanProvince = $user->address;
        
        // التأكد من أن لدينا ولاية صالحة للحرفي
        if (empty($craftsmanProvince) || $craftsmanProvince === 'غير محدد') {
            // إذا كان عنوان المستخدم غير محدد، نحاول الحصول عليه من session أو API
            $sessionProvince = Session::get('craftsman_province');
            if (!empty($sessionProvince)) {
                $craftsmanProvince = $sessionProvince;
                Log::info('Using craftsman province from session', [
                    'province' => $craftsmanProvince
                ]);
            } elseif ($participant && isset($participant['address'])) {
                $craftsmanProvince = $participant['address'];
                Log::info('Using craftsman province from API', [
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
                Log::info('Updated user address with province from API', [
                    'user_id' => $user->id,
                    'province' => $user->address
                ]);
            } else {
                // إذا لم نستطع العثور على ولاية، استخدم قيمة افتراضية
                $craftsmanProvince = '16'; // الجزائر العاصمة كقيمة افتراضية
                Log::warning('No province found for craftsman, using default', [
                    'default_province' => $craftsmanProvince
                ]);
            }
        }
        
        Log::info('Craftsman province', [
            'province' => $craftsmanProvince,
            'province_name' => \App\Helpers\AlgerianProvinces::getProvinceName($craftsmanProvince)
        ]);
        
        // تسجيل مزيد من التفاصيل عن ولاية الحرفي
        Log::info('Detailed craftsman province info', [
            'province_code' => $craftsmanProvince,
            'province_name' => \App\Helpers\AlgerianProvinces::getProvinceName($craftsmanProvince),
            'user_id' => $user->id,
            'craftsman_id' => $craftsman->id
        ]);
        
        // تحقق خاص بولاية الجزائر
        $isAlgiers = false;
        if ($craftsmanProvince == 'الجزائر' || $craftsmanProvince == 'الجزائر ' || $craftsmanProvince == '16') {
            $isAlgiers = true;
            Log::info('Craftsman province is Algiers', [
                'province' => $craftsmanProvince
            ]);
        }
        
        // بحث عن المشاريع المطابقة لتخصص الحرفي
        $query = Commande::where('specialist', $specialty)
            ->where('statue', 'pending');
            
        // Verificar si se solicita filtrar por provincia desde la solicitud HTTP (request)
        $provinceFilter = request('province');
        if (!empty($provinceFilter)) {
            Log::info('Filtering projects by specific province', [
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
            
        // تسجيل استعلام قاعدة البيانات للتشخيص    
        $queryString = $query->toSql();
        $queryBindings = $query->getBindings();
        
        Log::info('SQL Query for matching projects', [
            'query' => $queryString,
            'bindings' => $queryBindings,
            'filtering_by_province' => !empty($provinceFilter)
        ]);
        
        // تنفيذ الاستعلام
        $matchingProjects = $query->orderBy('created_at', 'desc')->paginate(10);
            
        // تسجيل كل المشاريع التي تطابق تخصص الحرفي فقط (بدون فلترة الولاية) للتشخيص
        $allSpecialtyProjects = Commande::where('specialist', $specialty)
            ->where('statue', 'pending')
            ->get();
            
        $projectsData = [];
        foreach($allSpecialtyProjects as $project) {
            $projectsData[] = [
                'id' => $project->id,
                'title' => $project->titre,
                'address_code' => $project->address,
                'address_name' => $project->province_name,
                'matches_province' => $project->address === $craftsmanProvince
            ];
        }
        
        Log::info('All projects matching specialty', [
            'total_count' => $allSpecialtyProjects->count(),
            'projects' => $projectsData,
            'specialty' => $specialty
        ]);
            
        Log::info('Found matching projects', [
            'total_count' => $matchingProjects->total(),
        ]);
        
        // استخراج 5 مشاريع فقط من المشاريع المطابقة للتخصص لعرضها للتشخيص
        $specialtyOnlyProjects = Commande::where('specialist', $specialty)
            ->where('statue', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('craftsman.dashboard', [
            'participant' => $participant,
            'craftsman' => $craftsman,
            'matchingProjects' => $matchingProjects,
            'specialtyOnlyProjects' => $specialtyOnlyProjects,
            'craftsmanProvince' => $craftsmanProvince,
            'craftsmanProvinceName' => AlgerianProvinces::getProvinceName($craftsmanProvince),
            'specialty' => $specialty
        ]);
    }
} 