<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk serving logo dari private storage
Route::get('/organization-logo/{path}', function ($path) {
    // Jika path sudah mengandung organization-logos/, gunakan langsung
    // Jika tidak, tambahkan prefix organization-logos/
    if (!str_starts_with($path, 'organization-logos/')) {
        $path = 'organization-logos/' . $path;
    }
    
    if (!Storage::disk('local')->exists($path)) {
        abort(404);
    }
    
    $file = Storage::disk('local')->get($path);
    $mimeType = Storage::disk('local')->mimeType($path);
    
    return Response::make($file, 200, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('organization.logo');
