<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use Exception;

class InstagramService
{
    private const INSTAGRAM_BASE_URL = 'https://www.instagram.com';
    private const TIMEOUT = 10;

    public function validateUsername(string $username): bool
    {
        return preg_match('/^[a-zA-Z0-9._]{1,30}$/', $username) === 1;
    }

    public function fetchProfile(string $username): Collection
    {
        if (!$this->validateUsername($username)) {
            throw new Exception("Invalid Instagram username format.");
        }

        try {
            $response = Http::timeout(self::TIMEOUT)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
                ->get("{$this->INSTAGRAM_BASE_URL}/{$username}/?__a=1&__w=1");

            if ($response->failed()) {
                throw new Exception("Failed to fetch Instagram profile. Profile may be private or doesn't exist.");
            }

            return $this->extractImageUrls($response->json());
        } catch (Exception $e) {
            throw new Exception("Instagram fetch error: " . $e->getMessage());
        }
    }

    private function extractImageUrls(array $data): Collection
    {
        $images = collect();

        try {
            $posts = $data['user']['edge_user_to_photos_of_content']['edges'] ?? [];

            foreach ($posts as $post) {
                $node = $post['node'] ?? null;
                if (!$node) continue;

                if ($node['__typename'] === 'GraphImage') {
                    $images->push([
                        'url' => $node['display_url'] ?? null,
                        'post_id' => $node['id'] ?? null,
                        'caption' => $node['edge_media_to_caption']['edges'][0]['node']['text'] ?? null,
                        'timestamp' => $node['taken_at_timestamp'] ?? null,
                    ]);
                } elseif ($node['__typename'] === 'GraphSidecar') {
                    $carouselItems = $node['edge_sidecar_to_children']['edges'] ?? [];
                    foreach ($carouselItems as $item) {
                        $itemNode = $item['node'] ?? null;
                        if ($itemNode && $itemNode['__typename'] === 'GraphImage') {
                            $images->push([
                                'url' => $itemNode['display_url'] ?? null,
                                'post_id' => $node['id'] ?? null,
                                'caption' => $node['edge_media_to_caption']['edges'][0]['node']['text'] ?? null,
                                'timestamp' => $node['taken_at_timestamp'] ?? null,
                            ]);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception("Failed to parse Instagram profile data: " . $e->getMessage());
        }

        if ($images->isEmpty()) {
            throw new Exception("No images found in this Instagram profile.");
        }

        return $images->take(50);
    }
}
