<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TMDBService
{
    protected $baseUrl = 'https://api.themoviedb.org/3';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.key');
    }

    public function searchMovies($query)
    {
        $cacheKey = 'tmdb_search_' . md5($query);

        return Cache::remember($cacheKey, 3600, function () use ($query) {
            $response = Http::get("{$this->baseUrl}/search/movie", [
                'api_key' => $this->apiKey,
                'query' => $query,
                'include_adult' => false,
            ]);

            if ($response->successful()) {
                return collect($response->json()['results'])
                    ->map(function ($movie) {
                        return [
                            'id' => $movie['id'],
                            'title' => $movie['title'],
                            'year' => substr($movie['release_date'], 0, 4),
                            'poster' => $movie['poster_path'] 
                                ? "https://image.tmdb.org/t/p/w92{$movie['poster_path']}"
                                : null,
                        ];
                    })
                    ->take(5);
            }

            return collect();
        });
    }

    public function getMovieDetails($movieId)
    {
        $cacheKey = 'tmdb_movie_' . $movieId;

        return Cache::remember($cacheKey, 86400, function () use ($movieId) {
            $response = Http::get("{$this->baseUrl}/movie/{$movieId}", [
                'api_key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $movie = $response->json();
                return [
                    'id' => $movie['id'],
                    'title' => $movie['title'],
                    'year' => substr($movie['release_date'], 0, 4),
                    'poster' => $movie['poster_path'] 
                        ? "https://image.tmdb.org/t/p/w92{$movie['poster_path']}"
                        : null,
                    'overview' => $movie['overview'],
                ];
            }

            return null;
        });
    }
}