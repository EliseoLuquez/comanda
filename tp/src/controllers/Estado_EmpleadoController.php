<?php

namespace App\Controllers;

use Config\DataBase as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Estado_Empleado;
use App\Models\Turno;
use App\Models\Usuario;
use App\Utils\ValidadorJWT;
use App\Utils\Funciones;
use Illuminate\Contracts\Validation\Validator;

class Estado_EmpleadoController {


    public function addEstado_empleado(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $header = getallheaders();

        $usuario = ValidadorJWT::ObtenerUsuario($header['token']);

        if($usuario->tipo_usuario== 'admin')
        {
            $estado = new Estado_Empleado();
            $estado->descripcion = $body["descripcion"];
            $rta = json_encode(array("ok" => $estado->save()));
        }
        else
        {
            $rta = json_encode("Solo el Admin puede agregar estado mesa");
        }

        $response->getBody()->write($rta);

        return $response;
    }


}