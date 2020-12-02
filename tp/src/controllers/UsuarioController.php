<?php
namespace App\Controllers;

use App\Models\Usuario;
use App\Utils\ValidadorJWT;

class UsuarioController
{
    /*
    public function getAll($request, $response, $args)
    {
        //TRAE TODOS LOS ELEMENTOS DE LA TABLA USER
        $rta = User::get();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }*/
/*
    public function getAll($request, $response, $args)
    {
        //TRAE UN ELEMENTO DE LA TABLA USER
        //$rta = User::find();
        //TRAE EL ELEMENTO INDICADO POR ID
        //$rta = User::where('id', 1)->get();
        //trae el primero
        $rta = User::where('id','<', 1)->first();
        $rta = User::find(1,2,3)->get();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }*/


    //ESTADO 1 ACTIVO
    //       2 SUSPENDIDO
    public function getAll($request, $response, $args)
    {
        $rta = Usuario::all();

        $response->getBody()->write(json_encode($rta));

        return $response;
    }

    public function add($request, $response, $args)
    {
        $body = $request->getParsedBody();

        if(strlen($body["clave"]) >= 4)
        {
            $pass = password_hash($body["clave"], PASSWORD_DEFAULT);
            $usuario = new Usuario;
            $usuario->usuario = $body["usuario"];
            $usuario->nombre = $body["nombre"];
            $usuario->tipo_usuario = $body["tipo_usuario"];
            $usuario->clave = $pass;
            $usuario->estado_id = $body["estado_id"];
            if($body['tipo_empleado'] != '')
            {
                $usuario->tipo_empleado_id = $body['tipo_empleado'];
            }
            if($body['sector_id'] != '')
            {
                $usuario->sector_id = $body['sector_id'];
            }
            $rta = json_encode(array("ok" => $usuario->save()));
        }
        else
        {
            $rta = json_encode("Error, la clave debe tener al menos 4 caracteres");
        }
        $response->getBody()->write($rta);

        return $response->withHeader('Content-Type', 'application/json');;
    }



    public function login($request, $response, $args)
    {
        $body = $request->getParsedBody();
        $usuario = $body["usuario"];
        $clave = $body['clave'];

         $usr = UsuarioController::TraerUnUsuario($usuario);
         $valido = UsuarioController::ValidarLogin($usr, $usuario, $clave);

        if($valido)
        {
             $token = ValidadorJWT::CrearToken($usr);
             $rta = json_encode(array("token" => $token));
        }
        else
        {
            $rta = json_encode("Error, usuario o clave incorrectos");
        }

        $response->getBody()->write($rta);

        return $response->withHeader('Content-Type', 'application/json');;
    }

    public static function TraerUnUsuario($dato)
    {
        $usuario = Usuario::where('usuario', $dato)->first();
        return $usuario;
    }

    public static function TraerUnUsuarioNombre($dato)
    {
        $usuario = Usuario::where('nombre', $dato)->first();
        
        return $usuario;
    }

    public static function TrerId()
    {
        $header = getallheaders();
        $token = $header['token'];

        $usuario = ValidadorJWT::ObtenerUsuario($token);
        $dbUsuario = Usuario::where('usuario', $usuario->usuario)->first();

        return $dbUsuario->id;
    }

    public static function ValidarLogin($usr, $usuario, $clave)
    {
        $rta = false;
        //var_dump($usr);
        $pass = password_verify($clave, $usr->clave);
        if($usr->usuario == $usuario && $pass)
        {
            $rta = true;
        }

        return $rta;
    }

    public function getUsuario($request, $response, $args)
    {
        $usuario = UsuarioController::TraerUnUsuario($args['usuario']);
        //var_dump(unserialize($usuario->foto));
        unserialize($usuario->foto);

        $response->getBody()->write(json_encode($usuario));
        return $response;
    }


    public function getOne($request, $response, $args)
    {
        $response->getBody()->write("getAll");
        return $response;
    }

    public function update($request, $response, $args)
    {
        $id = $args['id'];
        $user = Usuario::find($id);

        //MODIFICO CAMPOS
        $user->name = 'Peter';
        $rta = $user->save();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function delete($request, $response, $args)
    {
        //BORRO USUARIO POR ID
        $id = $args['id'];
        $user = Usuario::find($id);
        $rta = $user->delete();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
}