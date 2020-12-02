<?php

namespace App\Controllers;

use Config\DataBase as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Estado_Mesa;
use App\Models\Turno;
use App\Models\Usuario;
use App\Utils\ValidadorJWT;
use App\Utils\Funciones;
use Illuminate\Contracts\Validation\Validator;

class Estado_MesaController {


    public function addEstado_mesa(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $header = getallheaders();

        $usuario = ValidadorJWT::ObtenerUsuario($header['token']);

        if($usuario->tipo_usuario== 'admin')
        {
            $tipoUsuario = new Estado_Mesa();
            $tipoUsuario->descripcion = $body["descripcion"];
            $rta = json_encode(array("ok" => $tipoUsuario->save()));
        }
        else
        {
            $rta = json_encode("Solo el Admin puede agregar estado mesa");
        }

        $response->getBody()->write($rta);

        return $response;
    }


}