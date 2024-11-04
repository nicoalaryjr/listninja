<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SpotifyService
{
    protected $baseUrl = 'https://api.spotify.com/v1';
    protected $clientId;
    protected $clientSecret;
    protected $accessToken;

    public function __construct()
    {
        $this->clientId = config('services.spotify.client_id');
        $this->clientSecret = config('services.spotify.client_secret');
        $this->accessToken = $this->getAccessToken();
    }

    protected function getAccessToken()
    {
        return Cache::remember('spotify_token', 3500, function () {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post('https://accounts.spotify.com/api/token', [
                    'grant_type' => 'client_credentials',
                ]);

            return $response->json()['access_token'];
        });
    }

    public function searchTracks($query)
    {
        $cacheKey = 'spotify_search_' . md5($query);

        return Cache::remember($cacheKey, 3600, function () use ($query) {
            $response = Http::withToken($this->accessToken)
                ->get("{$this->baseUrl}/search", [
                    'q' => $query,
                    'type' => 'track',
                    'limit' => 5
                ]);

            if ($response->successful()) {
                return collect($response->json()['tracks']['items'])
                    ->map(function ($track) {
                        return [
                            'id' => $track['id'],
                            'title' => $track['name'],
                            'artist' => $track['artists'][0]['name'],
                            'album' => $track['album']['name'],
                            'image' => $track['album']['images'][2]['url'] ?? null,
                        ];
                    });
            }

            return collect();
        });
    }

    public function getTrack($trackId)
    {
        $cacheKey = 'spotify_track_' . $trackId;

        return Cache::remember($cacheKey, 86400, function () use ($trackId) {
            $response = Http::withToken($this->accessToken)
                ->get("{$this->baseUrl}/tracks/{$trackId}");

            if ($response->successful()) {
                $track = $response->json();
                return [
                    'id' => $track['id'],
                    'title' => $track['name'],
                    'artist' => $track['artists'][0]['name'],
                    'album' => $track['album']['name'],
                    'image' => $track['album']['images'][2]['url'] ?? null,
                ];
            }

            return null;
        });
    }
}