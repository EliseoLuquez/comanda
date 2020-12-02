<?php

namespace App\Utils;

use \Firebase\JWT\JWT;

class ValidadorJWT
{
    private static $claveSecerta = 'comanda';
    private static $encriptacion = ['HS256'];
    private static $aud = null;

    public static function CrearToken($dato)
    {
        $retorno = false;
        $key = 'comanda';
        $payload = array(
            "usuario" => $dato['usuario'],
            "tipo_usuario"=> $dato['tipo_usuario'],
            "clave"=> $dato['clave'],
        );
        $retorno = JWT::encode($payload, $key);
        return $retorno;
    }

    public static function VerificarToken($token)
    {
        $retorno = JWT::decode($token, 'comanda', array('HS256'));
        return $retorno;
    }

    public static function ObtenerUsuario($token)
    {

        $retorno = JWT::decode($token, self::$claveSecerta, self::$encriptacion);
        
        return $retorno;
    }
    
}