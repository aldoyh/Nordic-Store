<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Exception;

class ImageDownloadService
{
    private const MAX_RETRIES = 3;
    private const DELAY_BETWEEN_DOWNLOADS = 1;
    private const IMAGE_QUALITY = 80;

    public function downloadImage(string $imageUrl, int $shopId, string $postId): string
    {
        $retries = 0;

        while ($retries < self::MAX_RETRIES) {
            try {
                $response = Http::timeout(15)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    ])
                    ->get($imageUrl);

                if ($response->successful()) {
                    return $this->storeImage($response->body(), $shopId, $postId);
                }

                $retries++;
                if ($retries < self::MAX_RETRIES) {
                    sleep(self::DELAY_BETWEEN_DOWNLOADS);
                }
            } catch (Exception $e) {
                $retries++;
                if ($retries >= self::MAX_RETRIES) {
                    throw new Exception("Failed to download image after {$this->MAX_RETRIES} retries: " . $e->getMessage());
                }
                sleep(self::DELAY_BETWEEN_DOWNLOADS);
            }
        }

        throw new Exception("Failed to download image from Instagram.");
    }

    private function storeImage(string $imageContent, int $shopId, string $postId): string
    {
        try {
            $filename = $postId . '_' . time() . '.jpg';
            $path = "products/{$shopId}";

            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }

            $fullPath = $path . '/' . $filename;
            Storage::disk('public')->put($fullPath, $imageContent);

            return $fullPath;
        } catch (Exception $e) {
            throw new Exception("Failed to store downloaded image: " . $e->getMessage());
        }
    }
}
