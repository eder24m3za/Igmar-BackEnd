<?php

namespace App\Http\Controllers;

use App\Models\plataforma;
use App\Models\musica;
use App\Models\artista;
use App\Models\productora;
use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class FerController extends Controller
{
 
    public function registrarUsuario(Request $request)
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

        $response = Http::post('http://192.168.127.184:8000/api/musicv1/user/insertar',
[
    "name"=>$request->name,
    "email"=>$request->email,
    "password"=>$request->password,
]);

if($response->successful())
{
            $User = User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>Hash::make($request->password),
        ]);

        $token=$User->CreateToken("Token")->plainTextToken;
        $User ->name = $request->name;
        $User ->email = $request->email;
        $User ->password = Hash::make($request->password);
        
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
  }  }





    public function insertarArtista(Request $request)
{
    $validacion=validator::make(
        $request->all(),
        [
            "nombre_artistico"=>"required|max:50",
            "nombre_personal"=> "required|max:50",
            "ap_paterno"=>"required|max:50",
            "ap_materno"=>"nullable",
            "token"=>"nullable"
        
        ],
        [
            'nombre_artistico.required'=>"El campo es obligatorio",
            'nombre_personal.required'=>"El campo es obligatorio",
            'ap_paterno.required'=>"El campo es obligatorio",
        ]
        );

        if($validacion->fails()){
            return response()->json(
                [
                    "status"=>400,
                    "Mensaje"=>"No se cumplio con las validaciones",
                    "Fails"=>$validacion->errors(),
                    "Data"=>[]
                ], 400);
        }

$response = Http::withToken($request->token)->post('hhttp://192.168.127.184:8000/api/musicv1/artista/insertar',
[
    "nombre_artistico"=>$request->nombre_artistico,
    "nombre_personal"=>$request->nombre_personal,
    "ap_paterno"=>$request->ap_paterno,
    "ap_materno"=>$request->ap_materno,
]);

if($response->successful())
{
        $artista = new artista();
        $artista->nombre_artistico       = $request->nombre_artistico;
        $artista->nombre_personal        = $request->nombre_personal;
        $artista->ap_paterno             = $request->ap_paterno;
        $artista->ap_materno             = $request->ap_materno;
        $artista->status                 ='activo';

        if($artista->save())
        {
            return response()->json([
                "status"=>201,
                "message"=>"Se insertaron correctamente los datos",
                "error"=>null,
                "data"=>$artista,
            ],201);
        }
    
}
return response()->json([
    "estatus"=>$response->status()
],400
);
}



public function insertarProductora(Request $request)
{
    $validacion=validator::make(
        $request->all(),
        [
            "cantante"=>"required",
            "nombre"=>"required|max:50",
            "descripcion"=> "required|max:50",    
            "token"=>"nullable"      
        ],
        [
            'cantante.required'=>"El campo es obligatorio",
            'nombre.required'=>"El campo es obligatorio",
            'descripcion.required'=>"El campo es obligatorio",
        ]
        );

        if($validacion->fails()){
            return response()->json(
                [
                    "status"=>400,
                    "Mensaje"=>"No se cumplio con las validaciones",
                    "Fails"=>$validacion->errors(),
                    "Data"=>[]
                ], 400);
        }

$response = Http::withToken($request->token)->post('http://192.168.127.184:8000/api/musicv1/productora/insertar',
[
    "cantante"=>$request->cantante,
    "nombre"=>$request->nombre,
    "descripcion"=>$request->descripcion,  
]);

if($response->successful())
{
    $productora = new productora();
    $productora->cantante           = $request->cantante;
    $productora->nombre             = $request->nombre;
    $productora->descripcion        = $request->descripcion;
    $productora->status             = 'activo';

    if($productora->save())
    {
        return response()->json([
            "status"=>201,
            "message"=>"Se insertaron correctamente los datos",
            "error"=>null,
            "data"=>$productora
        ],201);
    }
    
}
return response()->json([
    "estatus"=>$response->status(),
    "error"=>$response->clientError(),
],400
);
}





public function insertarMusica(Request $request)
{

    $validacion=validator::make(
        $request->all(),
        [
            "singer"=>"required|Integer",
            "productora"=>"required|Integer",
            "titulo"=> "required|max:50", 
            "genero"=> "required|max:50", 
            "duracion"=> "required|max:50",    
            "token"=>"nullable"        
        ],
        [
            'singer.required'=>"El campo es obligatorio",
            'productora.required'=>"El campo es obligatorio",
            'titulo.required'=>"El campo es obligatorio",
            'genero.required'=>"El campo es obligatorio",
            'duracion.required'=>"El campo es obligatorio",
        ]
        );

        if($validacion->fails()){
            return response()->json(
                [
                    "status"=>400,
                    "Mensaje"=>"No se cumplio con las validaciones",
                    "Fails"=>$validacion->errors(),
                    "Data"=>[]
                ], 400);
        }

        $response = Http::withToken($request->token)->post('http://192.168.127.184:8000/api/musicv1/musica/insertar',
[
            "singer"=>$request->singer,
            "productora"=>$request->productora,
            "titulo"=> $request->titulo, 
            "genero"=> $request->genero, 
            "duracion"=> $request->duracion,    
]);


if($response->successful())
{
    $artista= artista::find($request->singer);
            if(!$artista)
            {
                return response()->json([
                    "status"=>400,
                    "message"=>"No se encontro el artista",
                    "error"=>null,
                    "data"=>$artista
                ],400);
            }
            $productora= productora::find($request->productora);
            if(!$productora)
            {
                return response()->json([
                    "status"=>400,
                    "message"=>"No se encontro la productora",
                    "error"=>null,
                    "data"=>$productora
                ],400);
            }
    $musica = new musica();
    $musica->singer           = $request->singer;
    $musica->productora         = $request->productora;
    $musica->titulo             = $request->titulo;
    $musica->genero             = $request->genero;
    $musica->duracion           = $request->duracion;
    $musica->status             = 'activo';

    if($musica->save())
    {
        return response()->json([
            "status"=>201,
            "message"=>"Se insertaron correctamente los datos",
            "error"=>null,
            "data"=>$musica
        ],201);
    }
    
}
return response()->json([
    "estatus"=>$response->status()
],400
);

}




public function insertarPlataforma(Request $request)
{
    $validacion=validator::make(
        $request->all(),
        [
            "cantant"=>"required|Integer",
            "nombre_plataforma"=>"required|max:50",
            "descripcion"=> "required",    
            "token"=>"nullable"        
        ],
        [
            'cantant.required'=>"El campo es obligatorio",
            'nombre.required'=>"El campo es obligatorio",
            'descripcion.required'=>"El campo es obligatorio",
        ]
        );

        if($validacion->fails()){
            return response()->json(
                [
                    "status"=>400,
                    "Mensaje"=>"No se cumplio con las validaciones",
                    "Fails"=>$validacion->errors(),
                    "Data"=>[]
                ], 400);
        }


   $response = Http::withToken($request->token)->post('http://192.168.127.184:8000/api/musicv1/plataforma/insertar',
[
    "cantant"=>$request->cantant,
    "nombre_plataforma"=>$request->nombre_plataforma,
    "descripcion"=>$request->descripcion,      
]);


if($response->successful())
{
    $plataforma = new plataforma();
    $plataforma->cantant                     = $request->cantant;
    $plataforma->nombre_plataforma           = $request->nombre_plataforma ;
    $plataforma->descripcion                 = $request->descripcion;
    $plataforma->status                      = 'activo';

    if($plataforma->save())
    {
        return response()->json([
            "status"=>201,
            "message"=>"Se insertaron correctamente los datos",
            "error"=>null,
            "data"=>$plataforma
        ],201);
    }
    
}
return response()->json([
    "estatus"=>$response->status()
],400
);

}




public function modificarArtista(Request $request, $id)
{
    $validacion = validator::make(
        $request->all(),
        [
            'nombre_artistico'=>"required|Max:50",
            'nombre_personal'=>"required|Max:50",
            'ap_paterno'=>"required|max:50",
            'ap_materno'=>"nullable",
            'status'=>"required",
            "token"=>"nullable"
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

    $response = Http::withToken($request->token)->put('http://192.168.127.184:8000/api/musicv2/artista/modificar/'.$id,
[
    "nombre_artistico"=>$request->nombre_artistico,
    "nombre_personal"=>$request->nombre_personal,
    "ap_paterno"=>$request->ap_paterno,
    "ap_materno"=>$request->ap_materno,
    "status"=>$request->status,
]);

if($response->successful())
{
    $artista = new artista(); 
    $artista = artista::find($id);
    if($artista)
    {
        $artista->nombre_artistico       = $request->nombre_artistico;
        $artista->nombre_personal        = $request->nombre_personal;
        $artista->ap_paterno             = $request->ap_paterno;
        $artista->ap_materno             = $request->ap_materno;
        $artista->status                 = $request->status;
    
        if($artista->save())
        {
            return response()->json([
                "status"=>201,
                "message"=>"Se insertaron correctamente los datos",
                "error"=>null,
                "data"=>$artista
            ],201);
        }
    }

    else
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Artista no encontrado",
                "error"=>[],
            ],400
        );
    }

}

return response()->json([
    "estatus"=>$response->status()
],400
);

}





public function modificarMusica(Request $request, $id)
{
    $validacion = validator::make(
        $request->all(),
        [
            "singer"=>"required",
            "productora"=>"required|Integer",
            "titulo"=> "required|max:50", 
            "genero"=> "required|max:50", 
            'duracion'=>"required",
            'status'=>"required",
            "token"=>"nullable"
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

    $response = Http::withToken($request->token)->put('http://192.168.127.184:8000/api/musicv2/musica/modificar/'.$id,
[
    "singer"=>$request->singer,
    "productora"=>$request->productora,
    "titulo"=> $request->titulo, 
    "genero"=> $request->genero, 
    "duracion"=>$request->duracion,
    'status'=>$request->status
]);


if($response->successful())
{
    $musica = new musica(); 
       $musica = musica::find($id);
       if($musica)
       {
        $musica ->singer        = $request->singer;
        $musica ->productora    = $request->productora;
        $musica ->titulo        = $request->titulo;
        $musica ->genero        = $request->genero;
        $musica ->status        =$request->status;
     
       if($musica->save())
       {
        return response()->json(
            [
                "status"=>200,
                "mensaje"=>"Los datos se modificaron de manera correcta",
                "error"=>null,
                "data"=>$musica
            ],200
            );
       }
    }
    else
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Musica no encontrado",
                "error"=>[],
            ],400
        );
    }

}

return response()->json([
    "estatus"=>$response->status()
],400
);

}





public function modificarProductora(Request $request, $id)
{
    $validacion = validator::make(
        $request->all(),
        [
            "cantante"=>"required",
            "nombre"=>"required|max:50",
            "descripcion"=> "required|max:50", 
            'status'=>"required",
            "token"=>"nullable"
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

    $response = Http::withToken($request->token)->put('http://192.168.127.184:8000/api/musicv2/productora/modificar/'.$id,
[
    "cantante"=>$request->cantante,
    "nombre"=>$request->nombre,
    "descripcion"=>$request->descripcion, 
    'status'=>$request->status
]);


if($response->successful())
{
    $productora = new productora(); 
    $productora = productora::find($id);
    if($productora)
    {
     $productora->cantante           = $request->cantante;
     $productora->nombre             = $request->nombre;
     $productora->descripcion        = $request->descripcion;
     $productora ->status          =$request->status;
  
    if($productora->save())
    {
     return response()->json(
         [
             "status"=>200,
             "mensaje"=>"Los datos se modificaron de manera correcta",
             "error"=>null,
             "data"=>$productora
         ],200
         );
    }
 }
 else
 {
     return response()->json(
         [
             "status"=>400,
             "mensaje"=>"Productora no encontrada",
             "error"=>[],
         ],400
     );
 }

}

return response()->json([
    "estatus"=>$response->status()
],400
);

}








public function modificarPlataforma(Request $request, $id)
{
    $validacion = validator::make(
        $request->all(),
        [
            "cantant"=>"required|Integer",
            "nombre_plataforma"=>"required|max:50",
            "descripcion"=> "required", 
            'status'=>"required",
            "token"=>"nullable"
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

    $response = Http::withToken($request->token)->put('http://192.168.127.184:8000/api/musicv2/plataforma/modificar/'.$id,
[
    "cantant"=>$request->cantant,
    "nombre_plataforma"=>$request->nombre_plataforma,
    "descripcion"=>$request->descripcion,  
    'status'=>$request->status
]);


if($response->successful())
{
    $plataforma = new plataforma(); 
       $plataforma = plataforma::find($id);
       if($plataforma)
       {
        $plataforma->cantant                     = $request->cantant;
        $plataforma->nombre_plataforma           = $request->nombre_plataforma ;
        $plataforma->descripcion                 = $request->descripcion;
        $plataforma ->status                     =$request->status;
     
       if($plataforma->save())
       {
        return response()->json(
            [
                "status"=>200,
                "mensaje"=>"Los datos se modificaron de manera correcta",
                "error"=>null,
                "data"=>$plataforma
            ],200
            );
       }
    }
    else
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Plataforma no encontrada    ",
                "error"=>[],
            ],400
        );
    }
}

return response()->json([
    "estatus"=>$response->status()
],400
);

}



public function eliminarMusica(Request $request, $id)
{
    $validacion = validator::make(
        $request->all(),
        [
            "token"=>"nullable"
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

    $response = Http::withToken($request->token)->put('http://192.168.127.184:8000/api/musicv4/musica/eliminar/'.$id,
    []);

    if($response->successful())
    {
        $musica = new musica(); 
        $musica = musica::find($id);
        if($musica)
        {
         $musica ->status = 'Inactivo';
        
        if($musica->save())
        {
         return response()->json(
             [
                 "status"=>201,
                 "mensaje"=>"Se elimino la compañia de manera exitosa",
                 "error"=>null,
                 "data"=>$musica
             ],201
             );
        }
     }
     else
     {
         return response()->json(
             [
                 "mensaje"=>"Compañia no encontrado",
                 "error"=>[],
             ],200
         );
     }
    }
}




public function eliminarArtista(Request $request, $id)
{
    $validacion = validator::make(
        $request->all(),
        [
            "token"=>"nullable"
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

    $response = Http::withToken($request->token)->put('http://192.168.127.184:8000/api/musicv4/artista/eliminar/'.$id,
    []);

    if($response->successful())
    {
        $artista = new artista(); 
       $artista = artista::find($id);
       if($artista)
       {
        $artista ->status = 'Inactivo';
       
       if($artista->save())
       {
        return response()->json(
            [
                "status"=>201,
                "mensaje"=>"Se elimino la compañia de manera exitosa",
                "error"=>null,
                "data"=>$artista
            ],201
            );
       }
    }
    else
    {
        return response()->json(
            [
                "mensaje"=>"Compañia no encontrado",
                "error"=>[],
            ],200
        );
    }
    }
}





public function eliminarProductora(Request $request, $id)
{
    $validacion = validator::make(
        $request->all(),
        [
            "token"=>"nullable"
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

    $response = Http::withToken($request->token)->put('http://192.168.127.184:8000/api/musicv4/productora/eliminar/'.$id,
    []);

    if($response->successful())
    {
        $productora = new productora(); 
        $productora = productora::find($id);
        if($productora)
        {
         $productora ->status = 'Inactivo';
        
        if($productora->save())
        {
         return response()->json(
             [
                 "status"=>201,
                 "mensaje"=>"Se elimino la compañia de manera exitosa",
                 "error"=>null,
                 "data"=>$productora
             ],201
             );
        }
     }
     else
     {
         return response()->json(
             [
                 "mensaje"=>"Compañia no encontrado",
                 "error"=>[],
             ],200
         );
     }
    }
}




public function eliminarPlataforma(Request $request, $id)
{
    $validacion = validator::make(
        $request->all(),
        [
            "token"=>"nullable"
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

    $response = Http::withToken($request->token)->put('http://192.168.127.184:8000/api/musicv4/plataforma/eliminar/'.$id,
    []);

    if($response->successful())
    {
        $plataforma = new plataforma(); 
        $plataforma = plataforma::find($id);
        if($plataforma)
        {
         $plataforma ->status = 'Inactivo';
        
        if($plataforma->save())
        {
         return response()->json(
             [
                 "status"=>201,
                 "mensaje"=>"Se elimino la compañia de manera exitosa",
                 "error"=>null,
                 "data"=>$plataforma
             ],201
             );
        }
     }
     else
     {
         return response()->json(
             [
                 "mensaje"=>"Compañia no encontrado",
                 "error"=>[],
             ],200
         );
     }
    }
}



public function buscarMusica()
{
    $musica = new musica();
    $musica = DB::table('musica')
             ->select('musica.*')
             ->where('status','=','activo')
             ->get();

             return response()->json(
                [
                    "datos"=>$musica
                ]
                );
}



public function buscarArtista()
{
    $artista = new artista();
    $artista = DB::table('artistas')
             ->select('artistas.*')
             ->where('status','=','activo')
             ->get();

             return response()->json(
                [
                    "datos"=>$artista
                ]
                );
}


public function buscarPlataforma()
{
    $plataforma = new plataforma();
    $plataforma = DB::table('plataforma_streaming')
             ->select('plataforma_streaming.*')
             ->where('status','=','activo')
             ->get();

             return response()->json(
                [
                    "datos"=>$plataforma
                ]
                );
}


public function buscarProductora()
{
    $productora= new productora();
    $productora = DB::table('productora')
             ->select('productora.*')
             ->where('status','=','activo')
             ->get();

             return response()->json(
                [
                    "datos"=>$productora
                ]
                );
}

}