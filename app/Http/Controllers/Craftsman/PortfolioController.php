<?php

namespace App\Http\Controllers\Craftsman;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $works = auth()->user()->works()->orderBy('created_at', 'desc')->get();
        return view('craftsman.portfolio.index', compact('works'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('craftsman.portfolio.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'completion_date' => 'required|date',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'videos.*' => 'nullable|mimes:mp4,mov,ogg,qt|max:20480',
            'links.*' => 'nullable|url',
        ]);

        $mediaItems = [];

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('works/images', 'public');
                $mediaItems[] = [
                    'type' => $image->getMimeType(),
                    'path' => $path,
                    'name' => $image->getClientOriginalName(),
                ];
            }
        }

        // Handle videos
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $path = $video->store('works/videos', 'public');
                $mediaItems[] = [
                    'type' => $video->getMimeType(),
                    'path' => $path,
                    'name' => $video->getClientOriginalName(),
                ];
            }
        }

        // Handle external links
        if ($request->links && is_array($request->links)) {
            foreach ($request->links as $link) {
                if (!empty($link)) {
                    $mediaItems[] = [
                        'type' => 'url',
                        'url' => $link,
                    ];
                }
            }
        }

        // Create work record
        $work = auth()->user()->works()->create([
            'title' => $request->title,
            'description' => $request->description,
            'completion_date' => $request->completion_date,
            'media' => $mediaItems,
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('craftsman.portfolio.index')
            ->with('status', 'تمت إضافة العمل بنجاح!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Work $work)
    {
        // Check if this work belongs to the logged-in user
        if ($work->user_id !== auth()->id()) {
            abort(403, 'غير مصرح بالوصول');
        }

        return view('craftsman.portfolio.show', compact('work'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Work $work)
    {
        // Check if this work belongs to the logged-in user
        if ($work->user_id !== auth()->id()) {
            abort(403, 'غير مصرح بالوصول');
        }

        return view('craftsman.portfolio.edit', compact('work'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Work $work)
    {
        // Check if this work belongs to the logged-in user
        if ($work->user_id !== auth()->id()) {
            abort(403, 'غير مصرح بالوصول');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'completion_date' => 'required|date',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'videos.*' => 'nullable|mimes:mp4,mov,ogg,qt|max:20480',
            'links.*' => 'nullable|url',
        ]);

        $mediaItems = $work->media ?: [];
        $removedMedia = $request->removed_media ? json_decode($request->removed_media, true) : [];

        // Remove deleted media
        if (!empty($removedMedia)) {
            $newMediaItems = [];
            foreach ($mediaItems as $index => $item) {
                if (!in_array($index, $removedMedia)) {
                    $newMediaItems[] = $item;
                } else if (isset($item['path'])) {
                    // Delete the file from storage
                    Storage::disk('public')->delete($item['path']);
                }
            }
            $mediaItems = $newMediaItems;
        }

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('works/images', 'public');
                $mediaItems[] = [
                    'type' => $image->getMimeType(),
                    'path' => $path,
                    'name' => $image->getClientOriginalName(),
                ];
            }
        }

        // Handle videos
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $path = $video->store('works/videos', 'public');
                $mediaItems[] = [
                    'type' => $video->getMimeType(),
                    'path' => $path,
                    'name' => $video->getClientOriginalName(),
                ];
            }
        }

        // Handle external links
        if ($request->links && is_array($request->links)) {
            foreach ($request->links as $link) {
                if (!empty($link)) {
                    $mediaItems[] = [
                        'type' => 'url',
                        'url' => $link,
                    ];
                }
            }
        }

        // Update work record
        $work->update([
            'title' => $request->title,
            'description' => $request->description,
            'completion_date' => $request->completion_date,
            'media' => $mediaItems,
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('craftsman.portfolio.index')
            ->with('status', 'تم تحديث العمل بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Work $work)
    {
        // Check if this work belongs to the logged-in user
        if ($work->user_id !== auth()->id()) {
            abort(403, 'غير مصرح بالوصول');
        }

        // Delete media files
        if (!empty($work->media)) {
            foreach ($work->media as $item) {
                if (isset($item['path'])) {
                    Storage::disk('public')->delete($item['path']);
                }
            }
        }

        $work->delete();

        return redirect()->route('craftsman.portfolio.index')
            ->with('status', 'تم حذف العمل بنجاح!');
    }

    /**
     * Display the craftsman's public portfolio for clients
     */
    public function publicPortfolio($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        
        if ($user->role !== 'craftsman') {
            abort(404);
        }
        
        $works = $user->works()->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc')->get();
        $craftsman = $user->craftsman;
        
        return view('craftsman.portfolio.public', compact('works', 'user', 'craftsman'));
    }
}
