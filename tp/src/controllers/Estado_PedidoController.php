<?php

namespace App\Controllers;

use Config\DataBase as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Estado_Pedido;
use App\Models\Turno;
use App\Models\Usuario;
use App\Utils\ValidadorJWT;
use App\Utils\Funciones;
use Illuminate\Contracts\Validation\Validator;

class Estado_PedidoController {


    public function addEstado_pedido(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $header = getallheaders();

        $usuario = ValidadorJWT::ObtenerUsuario($header['token']);

        if($usuario->tipo_usuario== 'admin')
        {
            $estado = new Estado_Pedido();
            $estado->descripcion = $body["descripcion"];
            $rta = json_encode(array("ok" => $estado->save()));
        }
        else
        {
            $rta = json_encode("Solo el Admin puede agregar estado pedido");
        }

        $response->getBody()->write($rta);

        return $response;
    }


}