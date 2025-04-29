<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ParticipantApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.participant_api.url', 'http://127.0.0.1:8080/api');
    }

    /**
     * Find a participant by ID number
     *
     * @param string $idNumber
     * @return array|null
     */
    public function findByIdNumber($idNumber)
    {
        try {
            $response = Http::get("{$this->baseUrl}/participants", [
                'id_number' => $idNumber
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Log the raw response for debugging
                Log::info('Raw API Response:', $responseData);
                
                // Check if we have the expected structure and data
                if (isset($responseData['status']) && $responseData['status'] === 'success' && 
                    isset($responseData['data']) && is_array($responseData['data'])) {
                    $participants = $responseData['data'];
                    
                    // Find the exact matching participant
                    foreach ($participants as $participant) {
                        if ($participant['id_number'] === $idNumber) {
                            Log::info('Found matching participant:', ['participant' => $participant]);
                            return $participant;
                        }
                    }
                }
                
                Log::warning('No participant found for ID: ' . $idNumber);
                return null;
            }
            
            Log::warning('API request failed with status: ' . $response->status());
            return null;
        } catch (\Exception $e) {
            Log::error('Error fetching participant from API: ' . $e->getMessage(), [
                'exception' => $e,
                'id_number' => $idNumber
            ]);
            return null;
        }
    }

    /**
     * Find a participant by ID
     *
     * @param int $id
     * @return array|null
     */
    public function findById($id)
    {
        try {
            Log::info('Attempting to fetch participant by ID', ['id' => $id]);
            
            $response = Http::get("{$this->baseUrl}/participants/{$id}");

            Log::info('API response status', ['status' => $response->status()]);
            
            if ($response->successful()) {
                $responseData = $response->json();
                
                // Log the raw response for debugging
                Log::info('Raw API Response for ID:', $responseData);
                
                // Check if we have the expected structure and data
                if (isset($responseData['status']) && $responseData['status'] === 'success' && 
                    isset($responseData['data'])) {
                    $participant = $responseData['data'];
                    
                    Log::info('Found participant by ID:', ['participant' => $participant]);
                    return $participant;
                }
                
                Log::warning('No participant found for API ID: ' . $id, [
                    'response' => $responseData
                ]);
                
                // Try alternative endpoint if the first one fails
                Log::info('Trying alternative endpoint for participant ID', ['id' => $id]);
                return $this->findByIdAlternative($id);
            }
            
            Log::warning('API request failed with status: ' . $response->status(), [
                'response' => $response->body()
            ]);
            
            // Try alternative endpoint if the first one fails
            Log::info('Trying alternative endpoint for participant ID', ['id' => $id]);
            return $this->findByIdAlternative($id);
        } catch (\Exception $e) {
            Log::error('Error fetching participant from API by ID: ' . $e->getMessage(), [
                'exception' => $e,
                'id' => $id
            ]);
            
            // Try alternative endpoint if the first one fails
            Log::info('Trying alternative endpoint for participant ID after exception', ['id' => $id]);
            return $this->findByIdAlternative($id);
        }
    }
    
    /**
     * Alternative method to find a participant by ID
     * This tries a different endpoint structure
     *
     * @param int $id
     * @return array|null
     */
    private function findByIdAlternative($id)
    {
        try {
            Log::info('Attempting alternative method to fetch participant by ID', ['id' => $id]);
            
            // Try with a different endpoint structure
            $response = Http::get("{$this->baseUrl}/participants", [
                'id' => $id
            ]);
            
            Log::info('Alternative API response status', ['status' => $response->status()]);
            
            if ($response->successful()) {
                $responseData = $response->json();
                
                // Log the raw response for debugging
                Log::info('Raw Alternative API Response for ID:', $responseData);
                
                // Check if we have the expected structure and data
                if (isset($responseData['status']) && $responseData['status'] === 'success' && 
                    isset($responseData['data']) && is_array($responseData['data'])) {
                    $participants = $responseData['data'];
                    
                    // Find the exact matching participant
                    foreach ($participants as $participant) {
                        if (isset($participant['id']) && $participant['id'] == $id) {
                            Log::info('Found matching participant using alternative method:', ['participant' => $participant]);
                            return $participant;
                        }
                    }
                }
                
                Log::warning('No participant found for API ID using alternative method: ' . $id);
                return null;
            }
            
            Log::warning('Alternative API request failed with status: ' . $response->status());
            return null;
        } catch (\Exception $e) {
            Log::error('Error in alternative method for fetching participant from API by ID: ' . $e->getMessage(), [
                'exception' => $e,
                'id' => $id
            ]);
            return null;
        }
    }
} 