<?php

namespace App\Controllers;

use Config\DataBase as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Orden;
use App\Models\Usuario;
use App\Models\Estado_Mesa;
use App\Utils\ValidadorJWT;
use Illuminate\Contracts\Validation\Validator;

class MesaController {


    public function addMesa(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $header = getallheaders();

        $codigo = MesaController::CodigoMesa();
        $usuario = ValidadorJWT::ObtenerUsuario($header['token']);
        //var_dump($usuario);
        $usuario = Usuario::where('usuario', $usuario->usuario)->first();

        if($usuario->tipo_usuario == 'admin')
        {
            $mesa = new Mesa();
            $mesa->estado_id = $body["estado_id"];
            $mesa->codigo = $codigo;
            $rta = json_encode(array("ok" => $mesa->save()));
        }
        else
        {
            $rta = json_encode("Solo el Admin puede agregar mesa");
        }

        $response->getBody()->write($rta);

        return $response;
    }

    public function cambioEstado(Request $request, Response $response, $args)
    {
        //$body = $request->getParsedBody();
        //$header = getallheaders();
        $codigo = $args['codigo'];

        $mesa = Mesa::where('codigo', $codigo)->first();

        //$usuario = ValidadorJWT::ObtenerUsuario($header['token']);
        //var_dump($usuario);
        //$usuario = Usuario::where('usuario', $usuario->usuario)->first();
        var_dump($mesa->estado_id);
        if($mesa != null)
        {
            $estado = Estado_Mesa::where('id', $mesa->estado_id)->first();

            switch($estado->descripcion)
            {
                case "con cliente esperando pedido":
                    $estadoMesa = Estado_Mesa::where('descripcion', 'con clientes comiendo')->first();
                    $mesa->estado_id = $estadoMesa->id;
                    break;
                case "con clientes comiendo":
                    $estadoMesa = Estado_Mesa::where('descripcion', 'con clientes pagando')->first();
                    $mesa->estado_id = $estadoMesa->id;
                    $pedido = Pedido::where('mesa_id', $mesa->id)->first();
                    $pedidoPrecio = Orden::where('pedido_id', $pedido->id)->get()->sum('precio');
                    $pedido->precio = $pedidoPrecio;
                    $pedido->save();
                    var_dump($pedidoPrecio);
                    break;
                default:
                    break;
            }
            var_dump($mesa->estado_id);
            $rta = json_encode(array("ok" => $mesa->save()));
        }
        else
        {
            $rta = json_encode("La mesa no existe");
        }

        $response->getBody()->write($rta);

        return $response;
    }

    public function cerrarMesa(Request $request, Response $response, $args)
    {
        //$body = $request->getParsedBody();
        //$header = getallheaders();
        $codigo = $args['codigo'];

        $mesa = Mesa::where('codigo', $codigo)->first();

        if($mesa != null)
        {
            $estado = Estado_Mesa::where('id', $mesa->estado_id)->first();

            $estadoMesa = Estado_Mesa::where('descripcion', 'cerrada')->first();
            $mesa->estado_id = $estadoMesa->id;

            $rta = json_encode(array("ok" => $mesa->save()));
        }
        else
        {
            $rta = json_encode("La mesa no existe");
        }

        $response->getBody()->write($rta);

        return $response;
    }

    public static function CodigoMesa()
    {
        do{
            $codigo = rand(10000, 99999);
            $mesa = Mesa::where('codigo', $codigo);
            
        }while($mesa == null);

        return $codigo;
    }


}