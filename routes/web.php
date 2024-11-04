<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MovieSearchController;
use App\Http\Controllers\MusicSearchController;
use App\Http\Controllers\AchievementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home and Dashboard
Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard') 
        : view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $lists = auth()->user()->following()
            ->with(['lists' => function ($query) {
                $query->latest()->take(10);
            }])
            ->get()
            ->pluck('lists')
            ->flatten();
        return view('dashboard', compact('lists'));
    })->name('dashboard');

    // Lists
    Route::resource('lists', ListController::class);
    Route::post('/lists/{list}/reorder', [ListController::class, 'reorderItems'])
        ->name('lists.reorder');
    Route::post('/lists/{list}/items', [ListController::class, 'addItem'])
        ->name('lists.items.store');
    Route::patch('/lists/items/{item}', [ListController::class, 'updateItem'])
        ->name('lists.items.update');
    Route::delete('/lists/items/{item}', [ListController::class, 'deleteItem'])
        ->name('lists.items.destroy');
    
    // Profile
    Route::get('/profile/{user:username}', [ProfileController::class, 'show'])
        ->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::post('/profile/{user}/follow', [ProfileController::class, 'toggleFollow'])
        ->name('profile.follow');
    Route::get('/profile/{user}/following', [ProfileController::class, 'following'])
        ->name('profile.following');
    Route::get('/profile/{user}/followers', [ProfileController::class, 'followers'])
        ->name('profile.followers');

    // Search
    Route::get('/search/movies', [MovieSearchController::class, 'search'])
        ->name('search.movies');
    Route::get('/movies/{id}', [MovieSearchController::class, 'getMovie'])
        ->name('movies.get');
    Route::get('/search/music', [MusicSearchController::class, 'search'])
        ->name('search.music');
    Route::get('/tracks/{id}', [MusicSearchController::class, 'getTrack'])
        ->name('tracks.get');

    // Achievements
    Route::get('/achievements', [AchievementController::class, 'index'])
        ->name('achievements.index');
    Route::post('/achievements/check', [AchievementController::class, 'checkAchievements'])
        ->name('achievements.check');
    Route::get('/achievements/setup', [AchievementController::class, 'setupAchievements'])
        ->name('achievements.setup');

    // Social Features
    Route::post('/lists/{list}/like', [LikeController::class, 'toggle'])
        ->name('lists.like');
    Route::post('/lists/{list}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    // Notifications    
    Route::post('/notifications/mark-all-as-read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllAsRead');
});

require __DIR__.'/auth.php';