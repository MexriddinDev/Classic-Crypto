<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/caesar', function () {
    return Inertia::render('CaesarCipher');
})->middleware(['auth', 'verified'])->name('cipher.caesar');

Route::get('/monoalphabetic', function () {
    return Inertia::render('MonoalphabeticCipher');
})->middleware(['auth', 'verified'])->name('monoalphabetic');

Route::get('playfair', function () {
    return Inertia::render('PlayfairCipher');
})->middleware(['auth', 'verified'])->name('playfair');


Route::get('/vegenere', function () {
    return Inertia::render('VeginereCipher');
})->middleware(['auth', 'verified'])->name('vigenere');

Route::get('hillcipher', function () {
    return Inertia::render('HillCipher');
})->middleware(['auth', 'verified'])->name('hillcipher');


Route::get('beaufort', function () {
    return Inertia::render('BeaufortCipher');
})->middleware(['auth', 'verified'])->name('beaufort');

Route::get('autokey', function () {
    return Inertia::render('AutokeyCipher');
})->middleware(['auth', 'verified'])->name('autokey');

Route::get('homophonic', function () {
    return Inertia::render('HomophonicCipher');
})->middleware(['auth', 'verified'])->name('homophonic');
Route::get('affine', function () {
    return Inertia::render('AffineCipher');
})->middleware(['auth', 'verified'])->name('affine');
Route::get('atbash', function () {
    return Inertia::render('AtbashCipher');
})->middleware(['auth', 'verified'])->name('atbash');

Route::get('scytale', function () {
    return Inertia::render('ScytaleCipher');
})->middleware(['auth', 'verified'])->name('scytale');

Route::get('columnar', function () {
    return Inertia::render('ColumnarCipher');
})->middleware(['auth', 'verified'])->name('columnar');

Route::get('ciphervideos', function () {
    return Inertia::render('CipherVideos');
})->middleware(['auth', 'verified'])->name('ciphervideo');

Route::get('jamoa', function () {
    return Inertia::render('Jamoa');
})->middleware(['auth', 'verified'])->name('jamoa');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::inertia('/team', 'Team')->name('team');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
