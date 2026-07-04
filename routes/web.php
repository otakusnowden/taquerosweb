<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/servicios', [PageController::class, 'servicios'])->name('servicios');
Route::get('/servicios/{solution}', [PageController::class, 'solution'])->name('solution');
Route::get('/contacto', [PageController::class, 'contacto'])->name('contacto');
Route::post('/contacto', [PageController::class, 'enviarContacto'])
    ->middleware('throttle:5,1')
    ->name('contacto.enviar');
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/aviso-de-privacidad', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terminos-y-condiciones', [PageController::class, 'terms'])->name('terms');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
