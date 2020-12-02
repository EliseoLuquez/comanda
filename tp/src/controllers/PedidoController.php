<?php

namespace App\Controllers;

use Config\DataBase as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Pedido;
use App\Models\Mesa;
use App\Models\Estado_Mesa;
use App\Models\Estado_Pedido;
use App\Models\Usuario;
use App\Utils\ValidadorJWT;
use DateTime;
use Illuminate\Contracts\Validation\Validator;

class PedidoController {


    public function addPedido(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $header = getallheaders();
        //PASAR MESA POR BODY
        $mesa_codigo = $body['mesa_codigo'];
        $mesa = Mesa::where('codigo', $mesa_codigo)->first();
        //$mesa = Mesa::where('estado_id', $estadoMesa[0]['id'])->get();

        $estadoMesa = Estado_Mesa::where('descripcion', 'cerrada')->first();
        $estado = Estado_Pedido::where('descripcion', 'pendiente')->first();
        $usuario = ValidadorJWT::ObtenerUsuario($header['token']);
        $usuario = Usuario::where('usuario', $usuario->usuario)->first();
        //var_dump($usuario->usuario);
        if($mesa != null)
        {
            //var_dump($mesa->estado_id);
            //var_dump($estadoMesa->id);
            if($mesa->estado_id == $estadoMesa->id)
            {
                $pedido = new Pedido();
                $pedido->codigo = PedidoController::CodigoPedido();
                $pedido->mesa_id = $mesa->id;
                $pedido->usuario_id = $usuario->id;
                $pedido->descripcion = $body["descripcion"];
                $pedido->cliente = $body["cliente"];
                $pedido->estado_pedido_id = $estado->id;

                //PONGO ESTADO MESA EN ESPERANDO
                $estadoMesa = Estado_Mesa::where('descripcion', 'con cliente esperando pedido')->first();
                $mesa->estado_id =$estadoMesa->id;
                $mesa->save();
                $rta = json_encode(array("ok" => $pedido->save(), "codigo pedido:" => $pedido->codigo, "codigo mesa:" => $mesa->codigo));
                
            }
            else
            {
                $rta = json_encode(array("error" => "La mesa esta ocupada"));
            }
        }
        else
        {
            $rta = json_encode(array("error" => "El codigo de mesa es inexistente"));
        }

        $response->getBody()->write($rta);

        return $response;
    }

    public function getAllPedido($request, $response, $args)
    {
        $rta = Pedido::select('pedidos.id', 'pedidos.cliente', 'pedidos.estado_pedido_id', 'pedidos.mesa_id', 'pedidos.codigo', 'estado_pedido.descripcion as estado', 'mesas.estado_id', 'estado_mesa.descripcion as mesa', 'orden.descripcion', 'orden.precio')
            ->join('estado_pedido', 'estado_pedido.id', 'pedidos.estado_pedido_id')
            ->join('mesas', 'mesas.id', 'pedidos.mesa_id')
            ->join('estado_mesa', 'estado_mesa.id', 'mesas.estado_id')
            ->join('orden', 'orden.pedido_id', 'pedidos.id')
            ->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getPedido($request, $response, $args)
    {
        $id = $args['id'];
        $rta = Pedido::select('pedidos.id', 'pedidos.cliente', 'pedidos.estado_pedido_id', 'pedidos.mesa_id', 'pedidos.codigo', 'estado_pedido.descripcion as estado', 'mesas.estado_id', 'estado_mesa.descripcion as mesa', 'orden.descripcion', 'orden.precio')
            ->join('estado_pedido', 'estado_pedido.id', 'pedidos.estado_pedido_id')
            ->join('mesas', 'mesas.id', 'pedidos.mesa_id')
            ->join('estado_mesa', 'estado_mesa.id', 'mesas.estado_id')
            ->join('orden', 'orden.pedido_id', 'pedidos.id')
            ->where('pedidos.id', $id)
            ->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getPedidoCliente($request, $response, $args)
    {
        $codigo = $args['codigo'];
        $pedido = Pedido::select('pedidos.demora')
            ->where('pedidos.codigo', $codigo)
            ->first();
         
        $date = new DateTime();
        $dateTime = new DateTime($pedido->demora);
        $espera = $dateTime->diff($date);
        $rta = array("Su pedido estara listo en " => $espera->i);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
/*
    public function deletePedido($request, $response, $args)
    {
        $codigo = $args['codigo'];
        $pedido = Pedido::where('pedidos.demora')
            ->where('pedidos.codigo', $codigo)
            ->first();
         
        $date = new DateTime();
        $dateTime = new DateTime($pedido->demora);
        $espera = $dateTime->diff($date);
        $rta = array("Su pedido estar listo en " => $espera->i);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
*/
    public function CodigoPedido()
    {   
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $codigo = substr(str_shuffle($chars), 0, 5);
        return $codigo;
    }


}