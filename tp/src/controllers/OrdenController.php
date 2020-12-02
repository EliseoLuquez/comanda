<?php

namespace App\Controllers;

use Config\DataBase as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Orden;
use App\Models\Usuario;
use App\Models\Sector;
use App\Models\Pedido;
use App\Models\Estado_Pedido;
use App\Utils\ValidadorJWT;
use DateTime;
use DateInterval;
use Illuminate\Contracts\Validation\Validator;

class OrdenController {


    public function addOrden(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $header = getallheaders();

        $pedido = $body['pedido_id'];
        $pedido = Pedido::find($pedido);

        if($pedido != null)
        {
            $orden = new Orden();
            $orden->pedido_id = $pedido->id;
            $orden->descripcion = $body['descripcion'];
            $orden->sector_id = $body['sector_id'];
            $estado_id = Estado_Pedido::where('descripcion', 'pendiente')->first();
            $orden->estado_id = $estado_id->id;
            $orden->cantidad = $body['cantidad'];
            $orden->precio = $body['precio'];
            $cuenta = $orden->precio * $orden->cantidad;
            $orden->precio = $cuenta;

            $rta = json_encode(array("ok" => $orden->save()));
        }
        else
        {
            $rta = json_encode("Pedido inexistente");
        }

        $response->getBody()->write($rta);

        return $response;
    }
    /*
    public function getAllOrden($request, $response, $args)
    {
        $rta = Pedido::select('pedidos.id', 'pedidos.estado_id', 'estado_pedido.descripcion as estado', 'mesas.estado_id', 'estado_mesa.descripcion as mesa', 'orden.descripcion', 'orden.precio')
            ->join('estado_pedido', 'estado_pedido.id', 'orden.estado_id')
            ->join('mesas', 'mesas.id', 'pedidos.mesa_id')
            ->join('estado_mesa', 'estado_mesa.id', 'mesas.estado_id')
            //->join('orden', 'orden.pedido_id', 'orden.id')
            ->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getOrden($request, $response, $args)
    {
        $id = $args['id'];
        $rta = Orden::select('orden.id', 'orden.cliente', 'orden.estado_pedido_id', 'orden.mesa_id', 'orden.codigo', 'estado_pedido.descripcion as estado', 'mesas.estado_id', 'estado_mesa.descripcion as mesa', 'orden.descripcion', 'orden.precio')
            ->join('estado_pedido', 'estado_pedido.id', 'orden.estado_pedido_id')
            ->join('mesas', 'mesas.id', 'orden.mesa_id')
            ->join('estado_mesa', 'estado_mesa.id', 'mesas.estado_id')
            ->join('orden', 'orden.pedido_id', 'orden.id')
            ->where('orden.id', $id)
            ->get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }*/

    public function cambioEstado(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $header = getallheaders();
        $token = $header['token'];

        $demora_ = $body['demora'];
        //var_dump($demora_ );
        $usuario = ValidadorJWT::ObtenerUsuario($token);
        $usuario = Usuario::where('usuario', $usuario->usuario)->first();
        $id = $args['id'];

        $orden = Orden::where('id', $id)->first();
        $pedido = Pedido::where('id', $orden->pedido_id)->first();

        if($orden != null)
        {
            if($orden->sector_id == $usuario->sector_id)
            {
                $estado = Estado_Pedido::where('id', $orden->estado_id)->first();
               
                switch($estado->descripcion)
                {
                    case "pendiente":
                        $estadoOrden= Estado_Pedido::where('descripcion', 'en preparacion')->first();
                        $orden->estado_id = $estadoOrden->id;
                        $orden->empleado_id = $usuario->id;
                        //REVISAR SI ES LA DEMORA MAS GRANDE PARA ASIGNARSELA AL PEDIDO
                        if($demora_ != '')
                        {
                            $demora = new DateTime();
                            $demora->add(new DateInterval('PT' . $demora_ . 'M'));
                            $orden->demora = $demora;
                            
                            if($pedido->demora == null || $pedido->demora < $demora->format('Y-m-d H:i:s'))
                            {
                                $pedido->demora = $demora;
                                $pedido->save();
                            }
                            $rta = json_encode(array("ok" => $orden->save()));
                        }
                        else
                        {
                            $rta = json_encode("Es necesario que ingrese la demora de la orden");
                        }
                        break;
                    case "en preparacion":
                         //REVISAR SI LA ORDEN ES LA ULTIMA EN SALIR
                        $estadoOrden = Estado_Pedido::where('descripcion', 'finalizado')->first();
                        $aux = Orden::where('pedido_id', $orden->pedido_id)->where('estado_id', $estadoOrden->id)->get();
                        if($aux != null)
                        {
                            $estadoPedido = Estado_Pedido::where('descripcion', 'finalizado')->first();
                            $pedido->estado_id = $estadoPedido->id;
                            $pedido->save();
                        }
                        $orden->estado_id = $estadoOrden->id;
                        $rta = json_encode(array("ok" => $orden->save()));
                        break;
                    default:
                        $rta = json_encode("La orden ya se encuentra finalizada");
                        break;
                }

            }
            else
            {
                $rta = json_encode("La orden no tiene pedidos de su sector");
            }
        }
        else
        {
            $rta = json_encode("La orden no existe");
        }

        $response->getBody()->write($rta);

        return $response;
    }

    public function getOrdenPorSector($request, $response, $args)
    {
        $header = getallheaders();
        $token = $header['token'];

        $usuario = ValidadorJWT::ObtenerUsuario($token);
        $usuario = Usuario::where('usuario', $usuario->usuario)->first();

        if($usuario != null)
        {
            if($usuario->sector_id != null)
            {
                //var_dump($usuario->sector_id);
                $sector = Sector::where('id', $usuario->sector_id)->first();
                $estado = Estado_Pedido::where('descripcion', 'pendiente')->first();
                //var_dump($estado->id);
                $orden = Orden::where('sector_id', $usuario->sector_id)->where('estado_id', $estado->id)->get(); 

                $rta = json_encode(array('Pendientes: ' => $orden));
            }
            else
            {
                $rta = json_encode(array('error' => "El usuario no pertenece a ningun sector"));
            }
        }
        else
        {
            $rta = json_encode(array('error' => "El usuario no existe"));
        }

        $response->getBody()->write($rta);
        return $response;
    }


    public function CodigoPedido()
    {   
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $codigo = substr(str_shuffle(chars), 0, 5);
        return $codigo;
    }
}