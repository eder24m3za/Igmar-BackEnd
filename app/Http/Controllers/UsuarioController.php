<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Jobs\MandarEmail;

class UsuarioController extends Controller
{
    public function insertarAdmin(Request $request)
    {
        $validacion= validator::make(
            $request->all(),
            [
                "name"=>"required|Max:255",
                "email"=>"required|Max:255",
                "password"=>"required|Max:255"
            ]
        );
        if($validacion->fails())
        {
            return response()->json(
                [
                    "status"=>400,
                    "mensaje"=>"Validacion no exitosa",
                    "Error"=>$validacion->errors(),
                    "Data"=>[]
                ], 400
                );
        }

        $User = new User();
        $User ->name = $request->name;
        $User ->email = $request->email;
        $User ->rol_id = 1;
        $User ->password =Hash::make($request->password) ;

        if($User->save())
        {
            return response()->json(
                [
                    "status"=>201,
                    "mensaje"=>"Usuario registrado",
                    "error"=>null,
                    "data"=>$User,
                ],201
                );
        }
    }


    public function insertarAdminFer(Request $request)
    {
        $validacion= validator::make(
            $request->all(),
            [
                "name"=>"required|Max:255",
                "email"=>"required|Max:255",
                "password"=>"required|Max:255"
            ]
        );
        if($validacion->fails())
        {
            return response()->json(
                [
                    "status"=>400,
                    "mensaje"=>"Validacion no exitosa",
                    "Error"=>$validacion->errors(),
                    "Data"=>[]
                ], 400
                );
        }

    $response = Http::post('http://192.168.127.184:8000/api/musicv6/mioadmin/insertar',
[
    "name"=>$request->name,
    "email"=>$request->email,
    "password"=>$request->password,
]);

if($response->successful())
{
        $User = new User();
        $User ->name = $request->name;
        $User ->email = $request->email;
        $User ->rol_id = 1;
        $User ->password = Hash::make($request->password);
        
        if($User->save())
        {
         return response()->json(
             [
                 "status"=>201,
                 "mensaje"=>"Los datos se insertaron de manera correcta",
                 "error"=>null,
                 "data"=>$User,
             ],201
             );
        }
}
}



public function loginUsuario(Request $request)
{
    $validacion= validator::make(
        $request->all(),
        [
            "email"=>"required|Max:255",
            "password"=>"required|Max:255"
        ]
    );
    if($validacion->fails())
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Validacion no exitosa",
                "Error"=>$validacion->errors(),
                "Data"=>[]
            ], 400
            );
    }

    $User = User::whereEmail($request->email)->first();

    if(!is_null($User) && Hash::check($request->password, $User->password))
    {
        if($User->status == false)
        {
            return response()->json(
                [
                    "status"=>400,
                    "mensaje"=>"Cuenta desactivada",
                ],400
                );
        }

        $token=$User->CreateToken("token")->plainTextToken;

        if($User->save())
        {
         return response()->json(
             [
                 "status"=>201,
                 "mensaje"=>"Los datos se insertaron de manera correcta",
                 "error"=>null,
                 "data"=>$User,
                 "token"=>$token
             ],201
             );
        }

    }

    return response()->json(
        [
            "status"=>400,
            "mensaje"=>"Los datos no son correctos",
            "error"=>null,
            "data"=>$User,
        ],400
        );
}



public function loginUsuarioFer(Request $request)
{
    $validacion= validator::make(
        $request->all(),
        [
            "password"=>"required|Max:255",
            "email"=>"required|Max:255"
        ]
    );
    if($validacion->fails())
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Validacion no exitosa",
                "Error"=>$validacion->errors(),
                "Data"=>[]
            ], 400
            );
    }

    $User = User::whereEmail($request->email)->first();

    if(!$User || !Hash::check($request->password,$User->password))
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Los datos no son correctos",
                "error"=>null,
                "data"=>$User,
            ],400
            );
    }
    
    if($User->status == false)
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Cuenta desactivada",
            ],400
            );
    }


    $response = Http::post('http://192.168.127.184:8000/api/musicv5/login/insertar',
    [
        "name"=>$request->name,
        "email"=>$request->email,
        "password"=>$request->password,
    ]);

    if($response->successful())
{

    $token=$User->CreateToken("Token")->plainTextToken;

    if($User->save())
    {
     return response()->json(
         [
             "status"=>201,
             "mensaje"=>"Los datos se insertaron de manera correcta",
             "error"=>null,
             "data"=>$User,
             "token"=>$token,
             "token2"=>$response->object()->token
         ],201
         );
    }
}
}



public function logoutUsuario(Request $request)
{

    $user=$request->user();

    return response()->json(
        [
            "status"=>201,
            "mensaje"=>"Se ha cerrado exitosamente",
            "error"=>null,
            "token"=>$request->user()->tokens()->delete(),
        ],201
        );
}


public function logoutUsuarioFer(Request $request)
{
    $validacion=validator::make(
        $request->all(),
        [
          
            "token"=>"nullable"
        
        ]
    );

    $response = Http::withToken($request->token)->post('http://192.168.127.184:8000/api/musicv5/mio/logout',
[]);

        if($validacion->fails()){
            return response()->json(
                [
                    "status"=>400,
                    "Mensaje"=>"No se cumplio con las validaciones",
                    "Fails"=>$validacion->errors(),
                    "Data"=>[]
                ], 400);
        }


        if($response->successful())
        {
    $user=$request->user();

    return response()->json(
        [
            "status"=>201,
            "mensaje"=>"Se ha cerrado exitosamente",
            "error"=>null,
            "token"=>$request->user()->tokens()->delete(),
        ],201
        );
    }


}


public function insertarUsuario(Request $request)
{
    $validacion= validator::make(
        $request->all(),
        [
            "name"=>"required|Max:255",
            "email"=>"required|Max:255",
            "password"=>"required|Max:255"
        ]
    );
    if($validacion->fails())
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Validacion no exitosa",
                "Error"=>$validacion->errors(),
                "Data"=>[]
            ], 400
            );
    }

    $User = new User();
    $User ->name = $request->name;
    $User ->email = $request->email;
    $User ->rol_id = 2;
    $User ->password =Hash::make($request->password) ;

  Mail::to($request->email)->send(new SendEmail($User));

  // MandarEmail::dispatch($User)->onQueue('meil');
 
    if($User->save())
    {
        return response()->json(
            [
                "status"=>201,
                "mensaje"=>"Usuario registrado",
                "error"=>null,
                "data"=>$User,
                URL::signedRoute('validacion', ['user' => $User]),
            ],201
            );
    }
}


public function insertarUsuarioFer(Request $request)
{
    $validacion= validator::make(
        $request->all(),
        [
            "name"=>"required|Max:255",
            "email"=>"required|Max:255",
            "password"=>"required|Max:255"
        ]
    );
    if($validacion->fails())
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Validacion no exitosa",
                "Error"=>$validacion->errors(),
                "Data"=>[]
            ], 400
            );
    }

    $response = Http::post('http://192.168.127.184:8000/api/musicv5/mioinsertar/insertar',
[
"name"=>$request->name,
"email"=>$request->email,
"password"=>$request->password,
]);

if($response->successful())
{
    $User = new User();
    $User ->name = $request->name;
    $User ->email = $request->email;
    $User ->rol_id = 2;
    $User ->password = Hash::make($request->password);
    
    if($User->save())
    {
     return response()->json(
         [
             "status"=>201,
             "mensaje"=>"Los datos se insertaron de manera correcta",
             "error"=>null,
             "data"=>$User,
         ],201
         );
    }
}
}


public function insertarInvitado(Request $request)
{
    $validacion= validator::make(
        $request->all(),
        [
            "name"=>"required|Max:255",
            "email"=>"required|Max:255",
            "password"=>"required|Max:255"
        ]
    );
    if($validacion->fails())
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Validacion no exitosa",
                "Error"=>$validacion->errors(),
                "Data"=>[]
            ], 400
            );
    }

    $User = new User();
    $User ->name = $request->name;
    $User ->email = $request->email;
    $User ->rol_id = 3;
    $User ->password =Hash::make($request->password) ;

    if($User->save())
    {
        return response()->json(
            [
                "status"=>201,
                "mensaje"=>"Usuario registrado",
                "error"=>null,
                "data"=>$User,
            ],201
            );
    }
}



public function insertarInvitadoFer(Request $request)
{
    $validacion= validator::make(
        $request->all(),
        [
            "name"=>"required|Max:255",
            "email"=>"required|Max:255",
            "password"=>"required|Max:255"
        ]
    );
    if($validacion->fails())
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Validacion no exitosa",
                "Error"=>$validacion->errors(),
                "Data"=>[]
            ], 400
            );
    }

    $response = Http::post('http://192.168.127.184:8000/api/musicv5/mioinsertar/insertar',
[
"name"=>$request->name,
"email"=>$request->email,
"password"=>$request->password,
]);

if($response->successful())
{
    $User = new User();
    $User ->name = $request->name;
    $User ->email = $request->email;
    $User ->rol_id = 1;
    $User ->password = Hash::make($request->password);
    
    if($User->save())
    {
     return response()->json(
         [
             "status"=>201,
             "mensaje"=>"Los datos se insertaron de manera correcta",
             "error"=>null,
             "data"=>$User,
         ],201
         );
    }
}

}


public function verificarUsuario(Request $request)
{
    if(!$request->hasValidSignature())
    abort(401);

    $user= User::all()->last();
 
    $response = Http::get("https://api.nexmo.com/verify/json?&api_key=89ca7390&api_secret=sFI3tYAjqElnKZGf&number=528714149701&brand=AcmeInc");

    if($response->successful())
    {
        return response()->json([
            "status"=>200,
            "message"=>"por favor verifique su numero",
            "error"=>[],
            "data"=>$response->json()
        ],200);
    }

    return response()->json([
        "status"=>400,
        "message"=>"Algo paso",
        "error"=>[],
        "data"=>[]
    ],400);
}


public function verificarUsuarioSMS (Request $request)
{
    $validacion= validator::make(
        $request->all(),
        [
            "codigo"=>"required",
            "request_id"=>"required"
        ]
    );
    if($validacion->fails())
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Validacion no exitosa",
                "Error"=>$validacion->errors(),
                "Data"=>[]
            ],400);
    }

    $response = Http::get("https://api.nexmo.com/verify/check/json?&api_key=89ca7390&api_secret=sFI3tYAjqElnKZGf&request_id=$request->request_id&code=$request->codigo");

    if($response->successful())
    {
        return response()->json([
            "status"=>200,
            "mensaje"=>"Validacion exitosa",
            "Error"=>[],
            "Data"=>[]
        ],200);
    }

    return response()->json([
        "status"=>400,
        "mensaje"=>"Validacion exitosa",
        "Error"=>[],
        "Data"=>[]
    ]);
}


}
