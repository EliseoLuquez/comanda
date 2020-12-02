<?php

namespace App\Controllers;

use Config\DataBase as Capsule;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Sector;
use App\Models\Usuario;
use App\Utils\ValidadorJWT;
use Illuminate\Contracts\Validation\Validator;

class SectorController {


    public function addSector(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $header = getallheaders();

        $usuario = ValidadorJWT::ObtenerUsuario($header['token']);

        if($usuario->tipo_usuario == 'admin')
        {
            $sector = new Sector();
            $sector->descripcion = $body["descripcion"];
            $rta = json_encode(array("ok" => $sector->save()));
        }
        else
        {
            $rta = json_encode("Solo el Admin puede agregar Sector");
        }

        $response->getBody()->write($rta);

        return $response;
    }


}