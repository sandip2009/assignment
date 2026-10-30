<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ShortUrlController;

use App\Http\Middleware\RoleMiddleware;
use App\Mail\ClientInvitationMail;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/invitations/accept/{token}', [AuthController::class,'showRegister',])->middleware('throttle:10,1')->name('invitations.accept');
Route::post('/invitations/accept/{token}', [AuthController::class,'register',])->middleware('throttle:5,1')->name('invitations.register');
Route::get('/s/{short_code}', [ClientController::class, 'redirect'])->name('short.redirect');

// Route::get('/invitations/accept/{token}', [InvitationRegistrationController::class,'show'])->middleware('throttle:10,1')->name('invitations.accept');
// Route::post('/invitations/accept/{token}', [InvitationRegistrationController::class,'register'])->middleware('throttle:5,1')->name('invitations.register');

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/client/short-urls/export', [ShortUrlController::class, 'export'])->name('client.short-urls.export');

    // SuperAdmin routes
    Route::middleware('role:SuperAdmin')->prefix('superadmin')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class,'index',])->name('superadmin.dashboard');
        Route::get('/clients/create', [SuperAdminController::class,'create',])->name('clients.create');
        Route::post('/clients/store', [SuperAdminController::class,'store',])->name('clients.store');
        Route::get('/clients/all', [SuperAdminController::class, 'viewAllClients'])->name('clients.all');
        Route::get('/urls/all', [SuperAdminController::class, 'viewAll'])->name('superadmin.urls.all');
        Route::get('/clients/json', [SuperAdminController::class, 'getClientsData'])->name('superadmin.clients.json');
        Route::get('/clients/short-urls/json', [App\Http\Controllers\SuperAdminController::class, 'getShortUrlsData'])->name('superadmin.short-urls.json');
    });

    Route::middleware('role:Admin,Member')->prefix('Client')->group(function () {
        Route::get('/dashboard', [ClientController::class,'index'])->name('client.dashboard');
        Route::get('/members/create', [ClientController::class, 'createMember'])->name('members.create');
        Route::post('/members/invite', [ClientController::class, 'storeMember'])->name('members.store');
        // Route::get('/s/{short_code}', [ClientController::class, 'redirect'])->name('short.redirect');
        Route::get('/members/all', [ClientController::class, 'viewAllMembers'])->name('members.all');
        Route::get('/client/members/json', [App\Http\Controllers\ClientController::class, 'getMembersData'])->name('client.members.json');
        Route::get('/client/short-urls/json', [ClientController::class, 'getShortUrlsData'])->name('client.short-urls.json');
        Route::get('/urls/all', [ClientController::class, 'viewAll'])->name('client.urls.all');
        

        Route::get('/urls/filter', [DashboardController::class, 'filterUrls'])->name('urls.filter');
        Route::get('/urls/download', [DashboardController::class, 'downloadUrls'])->name('urls.download');
        Route::get('/urls/create', [ShortUrlController::class, 'create'])->name('urls.create');
        Route::post('/urls', [ShortUrlController::class, 'store'])->name('urls.store');
        // Route::get('/urls/all', [ShortUrlController::class, 'viewAll'])->name('urls.all');

        
  
    });
    
});