<?php

namespace App\Http\Controllers;

use App\Services\SpotifyService;
use Illuminate\Http\Request;

class MusicSearchController extends Controller
{
    protected $spotify;

    public function __construct(SpotifyService $spotify)
    {
        $this->spotify = $spotify;
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $tracks = $this->spotify->searchTracks($query);

        return response()->json($tracks);
    }

    public function getTrack($id)
    {
        $track = $this->spotify->getTrack($id);

        if (!$track) {
            return response()->json(['error' => 'Track not found'], 404);
        }

        return response()->json($track);
    }
}