<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\ControllerBaseDeDatos;
use App\Http\Controllers\FerController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\URL;
use  App\Http\Controllers\JobsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//->middleware('auth:sanctum','admin')
Route::prefix("/v1")->group(function()
{
    Route::post("/compania/insertar",[ControllerBaseDeDatos::class,"insertarCompanias"]);
    Route::post("/servicios/insertar",[ControllerBaseDeDatos::class,"insertarServicios"]);
    Route::post("/juegos/insertar",[ControllerBaseDeDatos::class,"insertarJuegos"]);
    Route::post("/consola/insertar",[ControllerBaseDeDatos::class,"insertarConsolas"]);
    Route::post("/insertar/usuario",[FerController::class,"registrarUsuario"]);
    Route::put("/compania/borrar/{id}",[ControllerBaseDeDatos::class,"borrarCompania"])->where("id","[1-9]+");
    Route::put("/servicio/borrar/{id}",[ControllerBaseDeDatos::class,"borrarServicio"])->where("id","[1-9]+");
    Route::put("/juego/borrar/{id]",[ControllerBaseDeDatos::class,"borrarJuego"])->where("id","[1-9]+");
    Route::put("/consola/borrar/{id}",[ControllerBaseDeDatos::class,"borrarConsola"])->where("id","[1-9]+");

    Route::post("/fer/insertar/musica",[FerController::class,"insertarMusica"]);
    Route::post("/fer/insertar/artista",[FerController::class,"insertarArtista"]);
    Route::post("/fer/insertar/productora",[FerController::class,"insertarProductora"]);
    Route::post("/fer/insertar/plataforma",[FerController::class,"insertarPlataforma"]);
    Route::delete("/fer/eliminar/musica/{id}",[FerController::class,"eliminarMusica"])->where("id","[1-9]+");
    Route::delete("/fer/eliminar/artista/{id}",[FerController::class,"eliminarArtista"])->where("id","[1-9]+");
    Route::delete("/fer/eliminar/productora/{id}",[FerController::class,"eliminarProductora"])->where("id","[1-9]+");
    Route::delete("/fer/eliminar/plataforma/{id}",[FerController::class,"eliminarPlataforma"])->where("id","[1-9]+");
}
);

//middleware('auth:sanctum','usuario')->
Route::prefix("/v2")->group(function()
{
    Route::put("/compania/modificar/{id}",[ControllerBaseDeDatos::class,"modificarCompania"])->where("id","[1-9]+");
    Route::put("/servicio/modificar",[ControllerBaseDeDatos::class,"modificarServicios"]);
    Route::put("/juego/modificar",[ControllerBaseDeDatos::class,"modificarJuego"]);
    Route::put("/consola/modificar",[ControllerBaseDeDatos::class,"modificarConsola"]);

    Route::get('/compania/modificar/{id}',[ControllerBaseDeDatos::class,'vistaModificarCompania'])->whereNumber("id","[1-9]+");

    Route::put("/fer/modificar/musica/{id}",[FerController::class,"modificarMusica"])->where("id","[1-9]+");
    Route::put("/fer/modificar/artista/{id}",[FerController::class,"modificarArtista"])->where("id","[1-9]+");
    Route::put("/fer/modificar/productora/{id}",[FerController::class,"modificarProductora"])->where("id","[1-9]+");
    Route::put("/fer/modificar/plataforma/{id}",[FerController::class,"modificarPlataforma"])->where("id","[1-9]+");
}
);

//middleware('auth:sanctum','invitado')->
Route::prefix("/v3")->group(function()
{
    Route::get("/compania/buscar/todo",[ControllerBaseDeDatos::class,"consultarCompania"]);
    Route::get("/servicio/buscar/todo",[ControllerBaseDeDatos::class,"consultarServicio"]);
    Route::get("/juego/buscar/todo",[ControllerBaseDeDatos::class,"consultarJuego"]);
    Route::get("/consola/buscar/todo",[ControllerBaseDeDatos::class,"consultarConsola"]);

    Route::get("/fer/buscar/musica",[FerController::class,"buscarMusica"]);
    Route::get("/fer/buscar/artista",[FerController::class,"buscarArtista"]);
    Route::get("/fer/buscar/productora",[FerController::class,"buscarProductora"]);
    Route::get("/fer/buscar/plataforma",[FerController::class,"buscarPlataforma"]);

}
);

Route::prefix("/v4")->group(function()
{
    Route::post("/registrar/usuario",[UsuarioController::class,"insertarUsuario"]);
    Route::post("/registrar/usuarioFer",[UsuarioController::class,"insertarUsuarioFer"]);
    Route::post("/registrar/admin",[UsuarioController::class,"insertarAdmin"]);
    Route::post("/registrar/adminFer",[UsuarioController::class,"insertarAdminFer"]);
    Route::post("/registrar/invitado",[UsuarioController::class,"insertarInvitado"]);
    Route::post("/registrar/invitadoFer",[UsuarioController::class,"insertarInvitadoFer"]);
    Route::post("/login/usuario",[UsuarioController::class,"loginUsuario"]);
    Route::post("/login/usuarioFer",[UsuarioController::class,"loginUsuarioFer"]);
    Route::post("/verificar/usuariosms",[UsuarioController::class,"verificarUsuarioSMS"]);
    Route::post("verificar/usuario",[UsuarioController::class,"verificarUsuario"])->middleware('signed')->name("validacion");
    Route::post("jobs",[JobsController::class,"jobs"]);
}
);

Route::prefix("/v5")->middleware('auth:sanctum','status')->group(function()
{
    Route::post("/logout/usuario",[UsuarioController::class,"logoutUsuario"]);
    Route::post("/logout/usuarioFer",[UsuarioController::class,"logoutUsuarioFer"]);
}
);
