<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WaterQualityController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FarmController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});
#Route::get('/', [HomeController::class, 'index']);
#dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
#Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
#sensores
Route::get('/Sensores', function () {
    return view('sensores');
});
Route::get('/Sensores/DetallesSensores', function () {
    return view('DetallesSensores');
});
Route::get('/Sensores/detalles', function () {
    return "Ver detalles de un sensor";
});

#historicos
Route::get('/historicos', function () {
    return view('historicos');
});
Route::get('/historicos/bs', function () {
    return view('mes');
});

Route::get('/vivo', function () {
    return view('vivo');
});

Route::get('/creditos', function () {
    return view('creditos');
});

#control
Route::get('/Control/bombas', function () {
    return view('Bombas');
});
Route::get('/luces', function () {
    return view('luces');
});
Route::get('/Control/Valvulas', function () {
    return view('Valvulas');
});

#alertas
Route::get('/Alertas', function () {
    return "Alertas y notificaciones";
});

Route::get('/Alertas/Activas', function () {
    return view('activas');
});

Route::get('Alertas/Historial', function () {
    return view('halertas');
});
Route::get('/Admin/Sensores', function () {
    return view('asensores');
});

#usuarios
Route::get('/Usuarios', function () {
    return "Gestión de Usuarios";
});
Route::get('/Usuarios/lista', function () {
    return "Ver lista de usuarios";
});
Route::get('/Usuarios/crear', function () {
    return "Crear usuario";
});
Route::get('/Usuarios/edit', function () {
    return "Editar / Eliminar usuario";
});

#Configuración
Route::get('/Configuracion', function () {
    return "Configuración del sistema";
});
Route::get('/Configuracion', function () {
    return "Umbrales de sensores (pH, temperatura, etc.)
    Intervalo de muestreo
    Parámetros de control automático";
});

require __DIR__.'/auth.php';

#ruta con parametros
Route::get('/post/{parametro}', function ($parametro) {
    return "Ver detalles de un sensor {$parametro}";
});

#ruta multiples parametros 
/*Route::get('/post/{parametro}/{categoria}', function ($parametro, $categoria) {
    return "Ver detalles de un sensor {$parametro} de la categoría {$categoria}";
});*/

#ruta multiples parametros pero opcional
Route::get('/post/{parametro}/{categoria?}', function ($parametro, $categoria= null) {
    if ($categoria === null) {
        return "Ver detalles de un sensor {$parametro} sin categoría";
    }
    return "Ver detalles de un sensor {$parametro} de la categoría {$categoria}";
});



// Rutas para la API
Route::prefix('api/water-quality')->group(function () {
    Route::get('/data', [WaterQualityController::class, 'getData']);
    Route::post('/fetch-data', [WaterQualityController::class, 'fetchData']);
    Route::delete('/clear-data', [WaterQualityController::class, 'clearData']);
});