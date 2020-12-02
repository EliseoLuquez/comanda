<?php

namespace App\Controllers;

use Config\DataBase as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Tipo_Empleado;
use App\Models\Turno;
use App\Models\Usuario;
use App\Utils\ValidadorJWT;
use App\Utils\Funciones;
use Illuminate\Contracts\Validation\Validator;

class Tipo_EmpleadoController {


    public function addTipo_empleado(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $header = getallheaders();

        $usuario = ValidadorJWT::ObtenerUsuario($header['token']);
        $usr = UsuarioController::TraerUnUsuario($usuario->usuario);
        var_dump($usr->tipo_usuario);
        if($usr->tipo_usuario == 'admin')
        {
            $tipoEmpleado = new Tipo_Empleado();
            $tipoEmpleado->descripcion = $body["descripcion"];
            $rta = json_encode(array("ok" => $tipoEmpleado->save()));
        }
        else
        {
            $rta = json_encode("Solo el Admin puede agregar Tipo Usuario");
        }

        $response->getBody()->write($rta);

        return $response;
    }

    public function getEmpleado(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $header = getallheaders();
        //select * from tipo_empleado join usuarios on tipo_empleado.id_tipo_empleado = usuarios.tipo_empleado_id
        
        $response->getBody()->write($rta);

        return $response;
    }

}