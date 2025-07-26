<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/','LandingController@index')->name('home');
Route::get('/tentang-kami','LandingController@about')->name('about');

Route::get('/login','AuthController@showLogin')->name('login');
Route::post('/login','AuthController@login');
Route::get('/daftar','AuthController@showRegister')->name('register');
Route::post('/daftar','AuthController@register');

Route::prefix('/training')->name('training.')->group(function () {
    Route::get('/','TrainingController@index')->name('index');
    Route::get('/{slug}','TrainingController@show')->name('show');
});

Route::prefix('/layanan-kami')->name('services.')->group(function () {
    Route::get('/it-training','ServiceController@training')->name('training');
    Route::get('/it-consultant','ServiceController@consultant')->name('consultant');
    Route::get('/project','ServiceController@project')->name('project');
});

Route::middleware('auth')->name('user.')->prefix('/user')->group(function () {
    Route::post('/logout','AuthController@logout')->name('logout');
    
    Route::name('profile.')->group(function () {
        Route::get('/profil','ProfileController@edit')->name('edit');
        Route::post('/profil','ProfileController@update');
        
        Route::get('/password','ProfileController@password')->name('password');
        Route::post('/password','ProfileController@passwordUpdate');
    });
    Route::post('/task/{id}/status','ProjectController@status')->name('user.project.status');
        
    Route::prefix('/pesanan')->name('order.')->group(function () {
        Route::get('/','OrderController@user')->name('index');
        Route::post('/simpan','OrderController@register')->name('register');
        Route::get('/{id}','OrderController@show')->name('show');
        Route::get('/{id}/invoice','OrderController@invoice')->name('invoice');
        Route::post('/{id}/update','OrderController@update')->name('update');  
        Route::get('/{id}/pembayaran','OrderController@payment')->name('payment');
        Route::get('/{id}/pembayaran/data','OrderController@paymentData')->name('payment.data');
        Route::get('/{id}/project','ProjectController@index')->name('user.project');
        Route::get('/{id}/project/{project}','ProjectController@show')->name('user.project.show');
        Route::get('/{id}/project/{project}/task','ProjectController@task')->name('user.project.task');
        Route::get('/{id}/project/{project}/kalender','ProjectController@calendar')->name('user.project.calendar');
    });

    Route::prefix('/project')->name('project.')->group(function () {
        Route::get('/','ProjectController@index')->name('index');
        Route::get('/{id}','ProjectController@show')->name('show');
        Route::get('/{id}/kalender','ProjectController@calendar')->name('calendar');
        Route::post('/{id}/status','ProjectController@status')->name('status');
    });
    
    Route::prefix('/pembayaran')->name('payment.')->group(function () {
        Route::get('/','PembayaranController@index')->name('index');
        Route::get('/{id}','PembayaranController@show')->name('show');
    });

    Route::prefix('/journey')->name('journey.')->group(function () {
        Route::get('/','JourneyController@index')->name('index');
        Route::get('/{id}','JourneyController@show')->name('show');
    });
});

Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function(){
    
    Route::middleware('guest:admin')->group(function () {
        Route::get('/','LoginController@showLoginForm')->name('login');
        Route::get('/login','LoginController@showLoginForm')->name('login');
        Route::post('/login','LoginController@login');
    });

    Route::middleware(['auth:admin'])->group(function () {
        Route::post('/logout','LoginController@logout')->name('logout');


        Route::get('/profile','ProfileController@index')->name('profile.edit');
        Route::post('/profile','ProfileController@update')->name('profile.update');
        Route::get('/password','ProfileController@password')->name('password');
        Route::post('/password','ProfileController@passwordUpdate')->name('password.update');

        Route::middleware('verified')->group(function () {
            Route::get('/dashboard','DashboardController@index')->name('beranda');
            
            Route::prefix('/konsumen')->name('user.')->group(function () {
                Route::get('/','UserController@index')->name('index');
                Route::post('/select','UserController@select')->name('select');
                Route::get('/create','UserController@create')->name('create');
                Route::post('/store','UserController@store')->name('store');
                Route::get('/json/{id}','UserController@json')->name('json');
                Route::get('/{id}','UserController@show')->name('show');
                Route::get('/{id}/edit','UserController@edit')->name('edit');
                Route::post('{id}/update','UserController@update')->name('update');
                Route::delete('/{id}/delete','UserController@destroy')->name('delete');
                Route::get('/{id}/riwayat','UserController@riwayat')->name('riwayat');
            });

            Route::prefix('/order')->name('order.')->group(function () {
                Route::get('/','OrderController@index')->name('index');
                Route::get('/create','OrderController@create')->name('create');
                Route::post('/store','OrderController@store')->name('store');
                Route::post('/status','OrderController@status')->name('status');
                Route::post('/select','OrderController@select')->name('select');
                Route::get('/report','OrderController@report')->name('report');
                Route::get('/json','OrderController@json')->name('json');
                Route::get('/{id}','OrderController@show')->name('show');
                Route::get('/{id}/edit','OrderController@edit')->name('edit');
                Route::post('{id}/update','OrderController@update')->name('update');
                Route::delete('/{id}/delete','OrderController@destroy')->name('delete');
                Route::get('/{id}/peserta','UserOrderController@index')->name('peserta');
                Route::post('/{id}/peserta/store','UserOrderController@store')->name('peserta.store');
                Route::delete('/{id}/peserta/delete','UserOrderController@destroy')->name('peserta.delete');
                Route::get('/{id}/peserta/{user}/certificate','UserOrderController@certificate')->name('peserta.certificate');
            });
            
            Route::prefix('/project')->name('project.')->group(function () {
                Route::get('/','ProjectController@index')->name('index');
                Route::get('/tambah','ProjectController@create')->name('create');
                Route::post('/simpan','ProjectController@store')->name('store');
                Route::get('/{id}','ProjectController@show')->name('show');
                Route::get('/{id}/kalender','ProjectController@calendar')->name('calendar');
                Route::get('/{id}/edit','ProjectController@edit')->name('edit');
                Route::post('{id}/confirm','ProjectController@confirm')->name('confirm');
                Route::post('{id}/update','ProjectController@update')->name('update');
                Route::delete('/{id}/delete','ProjectController@destroy')->name('delete');
            });

            
            Route::prefix('/task')->name('task.')->group(function () {
                Route::get('/','TaskController@index')->name('index');
                Route::get('/json','TaskController@json')->name('json');
                Route::get('/tambah','TaskController@create')->name('create');
                Route::post('/simpan','TaskController@store')->name('store');
                Route::get('/{id}','TaskController@show')->name('show');
                Route::get('/{id}/edit','TaskController@edit')->name('edit');
                Route::post('{id}/confirm','TaskController@confirm')->name('confirm');
                Route::post('{id}/update','TaskController@update')->name('update');
                Route::delete('/{id}/delete','TaskController@destroy')->name('delete');
            });

            Route::prefix('/paket')->name('paket.')->group(function () {
                Route::get('/','PaketController@index')->name('index');
                Route::get('/create','PaketController@create')->name('create');
                Route::post('/store','PaketController@store')->name('store');
                Route::post('/select','PaketController@select')->name('select');
                Route::get('/json','PaketController@json')->name('json');
                Route::get('/{id}','PaketController@show')->name('show');
                Route::get('/{id}/edit','PaketController@edit')->name('edit');
                Route::post('{id}/update','PaketController@update')->name('update');
                Route::delete('/{id}/delete','PaketController@destroy')->name('delete');
            });

            Route::prefix('/kategori')->name('kategori.')->group(function () {
                Route::get('/','KategoriController@index')->name('index');
                Route::post('/store','KategoriController@store')->name('store');
                Route::get('/{id}','KategoriController@show')->name('show');
                Route::get('/{id}/edit','KategoriController@edit')->name('edit');
                Route::post('/{id}/update','KategoriController@update')->name('store');
                Route::delete('/{id}/delete','KategoriController@destroy')->name('delete');
            });

            Route::prefix('/pembayaran')->name('payment.')->group(function () {
                Route::get('/','PembayaranController@index')->name('index');
                Route::get('/create','PembayaranController@create')->name('create');
                Route::post('/store','PembayaranController@store')->name('store');
                Route::get('/report','PembayaranController@report')->name('report');
                Route::get('/{id}','PembayaranController@show')->name('show');
                Route::get('/{id}/edit','PembayaranController@edit')->name('edit');
                Route::post('{id}/update','PembayaranController@update')->name('update');
                Route::post('{id}/status','PembayaranController@status')->name('status');
                Route::delete('/{id}/delete','PembayaranController@destroy')->name('delete');
            });

            Route::prefix('/staff')->name('staff.')->group(function () {
                Route::get('/','StaffController@index')->name('index');
                Route::get('/data','StaffController@data')->name('data');
                Route::get('/create','StaffController@create')->name('create');
                Route::post('/store','StaffController@store')->name('store');
                Route::get('/{id}','StaffController@show')->name('show');
                Route::get('/{id}/edit','StaffController@edit')->name('edit');
                Route::post('{id}/update','StaffController@update')->name('update');
                Route::delete('/{id}/delete','StaffController@destroy')->name('delete');
            });

            Route::prefix('/journey')->name('journey.')->group(function () {
                Route::get('/','JourneyController@index')->name('index');
                Route::get('/create','JourneyController@create')->name('create');
                Route::post('/store','JourneyController@store')->name('store');
                Route::get('/{id}','JourneyController@show')->name('show');
                Route::get('/{id}/pdf','JourneyController@pdf')->name('pdf');
                Route::get('/{id}/edit','JourneyController@edit')->name('edit');
                Route::post('{id}/update','JourneyController@update')->name('update');
                Route::delete('/{id}/delete','JourneyController@destroy')->name('delete');

            });
            
                
            Route::prefix('/step')->name('step.')->group(function () {
                Route::get('/','JourneyStepController@index')->name('index');
                Route::get('/create','JourneyStepController@create')->name('create');
                Route::post('/store','JourneyStepController@store')->name('store');
                Route::get('/report','JourneyStepController@report')->name('report');
                Route::get('/{id}','JourneyStepController@show')->name('show');
                Route::get('/{id}/edit','JourneyStepController@edit')->name('edit');
                Route::post('{id}/update','JourneyStepController@update')->name('update');
                Route::post('{id}/status','JourneyStepController@status')->name('status');
                Route::delete('/{id}/delete','JourneyStepController@destroy')->name('delete');
            });
        });
    });
});


// require __DIR__.'/auth.php';
