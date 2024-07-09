<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedoreController;
use App\Models\Proveedore;

// Ruta de bienvenida
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

//Route::post('login', 'Auth\LoginController@login')->middleware('verifycaptcha');
//Route::post('register', 'Auth\RegisterController@register')->middleware('verifycaptcha');

//Auth::routes(['login' => false, 'register' => false]); 

Route::middleware(['auth'])->group(function () {
    Route::get('users/pdf', [UserController::class, 'exportPdf'])->name('users.pdf');
    Route::get('clientes/pdf', [ClienteController::class, 'exportPdf'])->name('clientes.pdf');
    Route::get('productos/pdf', [ProductoController::class, 'exportPdf'])->name('productos.pdf');
    Route::get('proveedores/pdf', [ProveedoreController::class, 'exportPdf'])->name('proveedores.pdf');
    Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('/clientes', ClienteController::class);
    Route::resource('/detalleventas', App\Http\Controllers\DetalleventaController::class);
    Route::resource('/transaccions', App\Http\Controllers\TransaccionController::class);
    Route::resource('/modulos', App\Http\Controllers\ModuloController::class);
    Route::resource('/empleados', App\Http\Controllers\EmpleadoController::class);
    Route::resource('/compras', App\Http\Controllers\CompraController::class);
    Route::resource('/proveedores', App\Http\Controllers\ProveedoreController::class);
    Route::resource('/detallecompras', App\Http\Controllers\DetallecompraController::class);
    Route::resource('/inventarios', App\Http\Controllers\InventarioController::class);
    Route::resource('/productos', App\Http\Controllers\ProductoController::class);
    Route::resource('/categorias', App\Http\Controllers\CategoriaController::class);
    Route::resource('/roles', App\Http\Controllers\RoleController::class);
    Route::resource('/permisos', App\Http\Controllers\PermisoController::class);
    Route::resource('/users', App\Http\Controllers\UserController::class);
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
    Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
});

// Rutas adicionales aquí fuera del grupo middleware si son necesarias

