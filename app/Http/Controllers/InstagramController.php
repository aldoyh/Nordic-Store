<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Product;
use App\Models\InstagramSyncLog;
use App\Services\InstagramService;
use App\Services\ImageDownloadService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InstagramController extends Controller
{
    public function __construct(
        private InstagramService $instagramService,
        private ImageDownloadService $imageDownloadService
    ) {
    }

    public function create(): View
    {
        return view('instagram.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'instagram_username' => 'required|string|max:30|unique:shops,instagram_username',
        ]);

        try {
            $images = $this->instagramService->fetchProfile($validated['instagram_username']);

            $shop = auth()->user()->shops()->create([
                'instagram_username' => $validated['instagram_username'],
                'shop_name' => "@{$validated['instagram_username']} Shop",
                'instagram_fetch_status' => 'fetching',
                'total_images_count' => $images->count(),
            ]);

            $this->downloadImages($shop, $images);

            $shop->update([
                'instagram_fetch_status' => 'completed',
                'instagram_last_synced_at' => now(),
            ]);

            InstagramSyncLog::create([
                'shop_id' => $shop->id,
                'status' => 'success',
                'images_fetched_count' => $images->count(),
            ]);

            return redirect()->route('dashboard')->with('success', "Shop created with {$images->count()} products!");
        } catch (\Exception $e) {
            InstagramSyncLog::create([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()->withErrors(['instagram_username' => $e->getMessage()]);
        }
    }

    private function downloadImages(Shop $shop, $images): void
    {
        foreach ($images as $image) {
            try {
                $imagePath = $this->imageDownloadService->downloadImage(
                    $image['url'],
                    $shop->id,
                    $image['post_id'] ?? 'unknown'
                );

                Product::create([
                    'shop_id' => $shop->id,
                    'instagram_post_id' => $image['post_id'],
                    'title' => substr($image['caption'] ?? 'Untitled', 0, 100),
                    'description' => $image['caption'],
                    'price' => 9.99,
                    'image_path' => $imagePath,
                    'is_available' => true,
                ]);
            } catch (\Exception $e) {
                \Log::warning("Failed to download image {$image['post_id']}: " . $e->getMessage());
            }
        }
    }
}
