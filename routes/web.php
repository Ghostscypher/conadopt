<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChildController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('logout', function(){
    Auth::logout();

    return redirect('login');
});

Route::get('login', function(){
    if(auth()->check()){
        return redirect('dashboard');
    }

    return view('auth.login');
});

Route::get('register', function(){
    if(auth()->check()){
        return redirect('dashboard');
    }

    return view('auth.register');
});

Route::get('contact_us', function(){
    return view('contact_us');
});

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::group(['middleware' => 'auth'], function(){
    Route::get('dashboard', function () {

        switch (Auth::User()->role) {
            case 'admin':
                return view('users.admin.dashboard');

            case 'parent':
                return view('users.parents.dashboard');

            default:
                Auth::logout();
                return redirect('login')->withErrors(['Incorrect username or password.']);
        }

    })->name('dashboard');

    Route::post('child/adopt', [ChildController::class, 'adopt']);

    Route::group(['middleware' => ['admin_only']], function () {

        Route::get('child/reports/get/{filter?}', [ChildController::class, 'getAdoptionReport']);

        Route::post('child/save', [ChildController::class, 'save']);
        Route::post('child/edit', [ChildController::class, 'edit']);
        Route::post('child/adoption/process', [ChildController::class, 'adoptionChoice']);

    });

});
