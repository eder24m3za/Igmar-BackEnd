<?php

namespace App\Http\Controllers;

use App\Models\Compania;
use App\Models\Juegos;
use App\Models\Consola;
use App\Models\Servicios;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ControllerBaseDeDatos extends Controller
{
    
    public function insertarCompanias(Request $request)
    {
        $validacion = validator::make(
            $request->all(),
            [
                'Nombre'=>"required|Max:15",
                'Sede'=>"required|Max:30",
                'Fundador'=>"required|Max:20",
                'Fundacion'=>"required|Date",
                
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

        $Compania = new Compania(); 
        $Compania ->Nombre = $request->Nombre;
        $Compania ->Sede = $request->Sede;
        $Compania ->Fundador = $request->Fundador;
        $Compania ->Fundacion = $request->Fundacion;
        $Compania ->Estatus = 'Activo';
        
        if($Compania->save())
        {
         return response()->json(
             [
                 "status"=>201,
                 "mensaje"=>"Los datos se insertaron de manera correcta",
                 "error"=>null,
                 "data"=>$Compania
             ],201
             );
        }

    }



    public function insertarServicios(Request $request)
    {
        $validacion = validator::make(
            $request->all(),
            [
                'Nombre'=>"required|Max:20",
                'Precio'=>"required",
                'Suscripciones'=>"required|Max:30",
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

        $Servicios = new Servicios(); 
        $Servicios ->Nombre = $request->Nombre;
        $Servicios ->Precio = $request->Precio;
        $Servicios ->Suscripciones = $request->Suscripciones;
        $Servicios ->Estatus = 'Activo';
        
        if($Servicios->save())
        {
         return response()->json(
             [
                 "status"=>201,
                 "mensaje"=>"Los datos se insertaron de manera correcta",
                 "error"=>null,
                 "data"=>$Servicios
             ],201
             );
        }

    }



    public function insertarJuegos(Request $request)
    {
        $validacion = validator::make(
            $request->all(),
            [
                'Juego'=>"required|Max:50",
                'Desarrollador'=>"required|Max:30",
                'Lanzamiento'=>"required|Date",
                'Genero'=>"required|Max:100",
                'servicio'=>'required|Integer',
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

        
        $Juegos = new Juegos(); 
        $Servicio=Servicios::find($request->servicio);

        if(!$Servicio)
        {
            return response()->json(
                [
                    "status"=>400,
                    "mensaje"=>"Datos no ingresados correctamente",
                    "Error"=>"No se encontro el servicio",
                    "Data"=>[]
                ], 400
                );

        }

        $Juegos ->Juego = $request->Juego;
        $Juegos ->Desarrollador = $request->Desarrollador;
        $Juegos ->Lanzamiento = $request->Lanzamiento;
        $Juegos ->Genero = $request->Genero;
        $Juegos ->Estatus = 'Activo';
        $Juegos ->servicio_id = $request->servicio;
        
        if($Juegos->save())
        {
         return response()->json(
             [
                 "status"=>201,
                 "mensaje"=>"Los datos se insertaron de manera correcta",
                 "error"=>null,
                 "data"=>$Juegos
             ],201
             );
        }

    }


    
    public function insertarConsolas(Request $request)
    {
        $validacion = validator::make(
            $request->all(),
            [
                'Compania'=>"required|Integer",
                'Servicio'=>"required|Integer",
                'Nombre'=>"required|Max:50",
                'Almacenamiento'=>"required|Max:10",
                'RAM'=>"required|Max:20",
                'Precio'=>"required|Integer",
                'Color'=>'required|Max:25',
                'Lanzamiento'=>'required|Date'
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

        $Consola= new Consola();
        $Servicio=Servicios::find($request->Servicio);
        $Compania=Compania::find($request->Compania);

        if(!$Servicio)
        {
            return response()->json(
                [
                    "status"=>400,
                    "mensaje"=>"Datos no ingresados correctamente",
                    "Error"=>"No se encontro el servicio",
                    "Data"=>[]
                ], 400
                );
        }

        if(!$Compania)
        {
            return response()->json(
                [
                    "status"=>400,
                    "mensaje"=>"Datos no ingresados correctamente",
                    "Error"=>"No se encontro la compañia",
                    "Data"=>[]
                ], 400
                );
        }

        $Consola ->Nombre = $request->Nombre;
        $Consola ->Almacenamiento = $request->Almacenamiento;
        $Consola ->RAM = $request->RAM;
        $Consola ->Precio = $request->Precio;
        $Consola ->Color = $request->Color;
        $Consola ->Lanzamiento = $request->Lanzamiento;
        $Consola->Servicio_id = $request->Servicio;
        $Consola->compania_id = $request->Compania;

        
        if($Consola->save())
        {
         return response()->json(
             [
                 "status"=>201,
                 "mensaje"=>"Los datos se insertaron de manera correcta",
                 "error"=>null,
                 "data"=>$Consola
             ],201
             );
        }

    }


    public function vistaModificarCompania($id)
    {
        $Compania=new Compania();
        $Compania=Compania::find($id);

        return response()->json(
            [
                "data"=>$Compania
            ],201
            );
    }



    public function modificarCompania(Request $request, $id)
    {
    
        $validacion = validator::make(
            $request->all(),
            [
                'Nombre'=>"required|Max:15",
                'Sede'=>"required|Max:30",
                'Fundador'=>"required|Max:20",
                'Fundacion'=>"required|Date",
                'Estatus'=>'required'
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

       $Compania = new Compania(); 
       $Compania = Compania::find($id);
       if($Compania)
       {
        $Compania ->Nombre = $request->Nombre;
        $Compania ->Sede = $request->Sede;
        $Compania ->Fundador = $request->Fundador;
        $Compania ->Fundacion = $request->Fundacion;
        $Compania ->Estatus = $request->Estatus;
       
       if($Compania->save())
       {
        return response()->json(
            [
                "status"=>201,
                "mensaje"=>"Los datos se modificaron de manera correcta",
                "error"=>null,
                "data"=>$Compania
            ],201
            );
       }
        }
        else
        {
            return response()->json(
                [
                    "status"=>400,
                    "mensaje"=>"Compañia no encontrado",
                    "error"=>[],
                ],400
            );
        }
    }




public function modificarServicios(Request $request)
{

    $validacion = validator::make(
        $request->all(),
        [
            'id'=>'required|Integer',
            'Nombre'=>"required|Max:20",
            'Precio'=>"required",
            'Suscripciones'=>"required|Max:30",
            'Estatus'=>'required'
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

    $Servicios = new Servicios(); 

    $Servicios = Servicios::find($request->id);

    if($Servicios)
    {
    $Servicios ->Nombre = $request->Nombre;
    $Servicios ->Precio = $request->Precio;
    $Servicios ->Suscripciones = $request->Suscripciones;
    $Servicios ->Estatus = $request->Estatus;
    
    if($Servicios->save())
    {
     return response()->json(
         [
             "status"=>201,
             "mensaje"=>"Los datos se insertaron de manera correcta",
             "error"=>null,
             "data"=>$Servicios
         ],201
         );
    }

    }
    else
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Servicio no encontrado",
                "error"=>[],
            ],400
        );
    }
}




public function modificarJuego(Request $request)
{
    $validacion = validator::make(
        $request->all(),
        [
            'id'=>'required|Integer',
            'Juego'=>"required|Max:50",
            'Desarrollador'=>"required|Max:30",
            'Lanzamiento'=>"required|Date",
            'Genero'=>"required|Max:100",
            'servicio'=>'required|Integer',
            'Estatus'=>'required'
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

    $Juegos = new Juegos(); 
    $Juegos=Juegos::find($request->id);

    if(!$Juegos)
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Datos no ingresados correctamente",
                "Error"=>"No se encontro el juego",
                "Data"=>[]
            ], 400
            );
    }

    $Servicio=Servicios::find($request->servicio);

    if(!$Servicio)
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Datos no ingresados correctamente",
                "Error"=>"No se encontro el servicio",
                "Data"=>[]
            ], 400
            );

    }

    $Juegos ->Juego = $request->Juego;
    $Juegos ->Desarrollador = $request->Desarrollador;
    $Juegos ->Lanzamiento = $request->Lanzamiento;
    $Juegos ->Genero = $request->Genero;
    $Juegos ->Estatus = $request->Estatus;
    $Juegos ->servicio_id = $request->servicio;
    
    if($Juegos->save())
    {
     return response()->json(
         [
             "status"=>201,
             "mensaje"=>"Los datos se insertaron de manera correcta",
             "error"=>null,
             "data"=>$Juegos
         ],201
         );
    }
    return response()->json(
        [
            "status"=>400,
            "mensaje"=>"error",
            "error"=>null,
            "data"=>$Juegos
        ],400);

}



public function modificarConsola(Request $request)
{
    $validacion = validator::make(
        $request->all(),
        [
            'id'=>'required|Integer',
            'Compania'=>"required|Integer",
            'Servicio'=>"required|Integer",
            'Nombre'=>"required|Max:50",
            'Almacenamiento'=>"required|Max:10",
            'RAM'=>"required|Max:20",
            'Precio'=>"required|Integer",
            'Color'=>'required|Max:25',
            'Lanzamiento'=>'required|Date',
            'Estatus'=>'required'
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

    $Consola= new Consola();
    $Servicio=Servicios::find($request->Servicio);
    $Compania=Compania::find($request->Compania);
    $Consola=Consola::find($request->id);

    if(!$Consola)
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Datos no ingresados correctamente",
                "Error"=>"No se encontro la consola",
                "Data"=>[]
            ], 400
            );
    }

    if(!$Servicio)
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Datos no ingresados correctamente",
                "Error"=>"No se encontro el servicio",
                "Data"=>[]
            ], 400
            );
    }

    if(!$Compania)
    {
        return response()->json(
            [
                "status"=>400,
                "mensaje"=>"Datos no ingresados correctamente",
                "Error"=>"No se encontro la compañia",
                "Data"=>[]
            ], 400
            );
    }

    $Consola ->Nombre = $request->Nombre;
    $Consola ->Almacenamiento = $request->Almacenamiento;
    $Consola ->RAM = $request->RAM;
    $Consola ->Precio = $request->Precio;
    $Consola ->Color = $request->Color;
    $Consola ->Lanzamiento = $request->Lanzamiento;
    $Consola->Estatus=$request->Estatus;
    $Consola->servicio_id = $request->Servicio;
    $Consola->compania_id = $request->Compania;
    
    if($Consola->save())
    {
     return response()->json(
         [
             "status"=>201,
             "mensaje"=>"Los datos se insertaron de manera correcta",
             "error"=>null,
             "data"=>$Consola
         ],201
         );
    }

}


public function consultarCompania(Request $request)
{ 
    $Compania = new Compania();
    $Compania = DB::table('compania')
             ->select('compania.*')
             ->where('Estatus','=','Activo')
             ->get();

             return response()->json(
                [
                    "datos"=>$Compania
                ]
                );
}

public function consultarServicio(Request $request)
{ 
    $Servicio = new Servicios();
    $Servicio = DB::table('servicios')
             ->select('servicios.*')
             ->where('Estatus','=','Activo')
             ->get();

             return response()->json(
                [
                    "datos"=>$Servicio
                ]
                );
}

public function consultarConsola(Request $request)
{ 
    $Consola = new Consola();
    $Consola = DB::table('consolas')
             ->select('consolas.*')
             ->where('Estatus','=','Activo')
             ->get();

             return response()->json(
                [
                    "datos"=>$Consola
                ]
                );
}

public function consultarJuego(Request $request)
{ 
    $Juegos = new Juegos();
    $Juegos = DB::table('juegos')
             ->select('juegos.*')
             ->get();

             return response()->json(
                [
                    "datos"=>$Juegos
                ]
                );
}


public function consultarCompaniaNombre(Request $request, $name)
{ 
    $Compania = new Compania();
    $Compania = DB::table('compania')
             ->select('compania.*')
             ->where('Nombre','=',$name,'and','Estatus','=','Activo')
             ->get();

             return response()->json(
                [
                    "datos"=>$Compania
                ]
                );
}


public function consultarServiciosJuegos(Request $request)
{ 
    $Servicio = new Servicios();
    $Servicio = DB::table('servicios')
            ->join('juegos','juegos.servicio_id','=','servicios.id')
             ->select('servicios.nombre', 'juegos.Juego')
             ->where('servicios.Estatus','=','Activo','and','Juegos.Estatus','=','Activo')
             ->get();

             return response()->json(
                [
                    "datos"=>$Servicio
                ]
                );
}


public function borrarCompania(Request $request, $id)
    {
       $Compania = new Compania(); 
       $Compania = Compania::find($id);
       if($Compania)
       {
        $Compania ->Estatus = 'Inactivo';
       
       if($Compania->save())
       {
        return response()->json(
            [
                "status"=>201,
                "mensaje"=>"Se elimino la compañia de manera exitosa",
                "error"=>null,
                "data"=>$Compania
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


public function borrarServicio(Request $request, $id)
    {
       $Servicio = new Servicios(); 
       $Servicio = Servicios::find($id);
       if($Servicio)
       {
        $Servicio ->Estatus = 'Inactivo';
       
       if($Servicio->save())
       {
        return response()->json(
            [
                "status"=>201,
                "mensaje"=>"Se elimino el servicio de manera exitosa",
                "error"=>null,
                "data"=>$Servicio
            ],201
            );
       }
    }
    else
    {
        return response()->json(
            [
                "mensaje"=>"Servicio no encontrado",
                "error"=>[],
            ],200
        );
    }

}

}