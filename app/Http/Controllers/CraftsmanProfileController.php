<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ParticipantApiService;

class CraftsmanProfileController extends Controller
{
    protected $participantApiService;

    public function __construct(ParticipantApiService $participantApiService)
    {
        $this->participantApiService = $participantApiService;
        $this->middleware(['auth', 'craftsman']);
    }

    /**
     * عرض الملف الشخصي للحرفي
     */
    public function show()
    {
        $user = Auth::user();
        $craftsman = $user->craftsman;

        if (!$craftsman) {
            return redirect()->route('dashboard')
                           ->with('error', 'لم يتم العثور على معلومات الحرفي.');
        }
        
        // Get participant data from API using the stored API ID
        $participant = null;
        
        if ($craftsman->api_id) {
            $participant = $this->participantApiService->findById($craftsman->api_id);
            
            if (!$participant) {
                \Illuminate\Support\Facades\Log::warning('Could not fetch participant data for API ID: ' . $craftsman->api_id);
                
                // Try to fetch by ID number as a fallback
                $idNumber = \Illuminate\Support\Facades\Session::get('craftsman_id_number');
                if ($idNumber) {
                    \Illuminate\Support\Facades\Log::info('Trying to fetch participant data by ID number as fallback', [
                        'id_number' => $idNumber
                    ]);
                    
                    $participant = $this->participantApiService->findByIdNumber($idNumber);
                    
                    if ($participant) {
                        \Illuminate\Support\Facades\Log::info('Successfully fetched participant data by ID number', [
                            'id_number' => $idNumber
                        ]);
                    } else {
                        \Illuminate\Support\Facades\Log::warning('Could not fetch participant data by ID number either', [
                            'id_number' => $idNumber
                        ]);
                    }
                }
            }
        } else {
            \Illuminate\Support\Facades\Log::warning('No API ID found for craftsman', ['craftsman_id' => $craftsman->id]);
            
            // Try to fetch by ID number as a fallback
            $idNumber = \Illuminate\Support\Facades\Session::get('craftsman_id_number');
            if ($idNumber) {
                \Illuminate\Support\Facades\Log::info('Trying to fetch participant data by ID number as fallback', [
                    'id_number' => $idNumber
                ]);
                
                $participant = $this->participantApiService->findByIdNumber($idNumber);
                
                if ($participant) {
                    \Illuminate\Support\Facades\Log::info('Successfully fetched participant data by ID number', [
                        'id_number' => $idNumber
                    ]);
                    
                    // Update the craftsman with the API ID for future use
                    $craftsman->api_id = $participant['id'];
                    $craftsman->save();
                    
                    \Illuminate\Support\Facades\Log::info('Updated craftsman with API ID', [
                        'craftsman_id' => $craftsman->id,
                        'api_id' => $participant['id']
                    ]);
                } else {
                    \Illuminate\Support\Facades\Log::warning('Could not fetch participant data by ID number either', [
                        'id_number' => $idNumber
                    ]);
                }
            }
        }

        return view('craftsman.profile', [
            'user' => $user,
            'craftsman' => $craftsman,
            'participant' => $participant
        ]);
    }
} 