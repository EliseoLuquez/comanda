<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Utils\ValidadorJWT;
use App\Models\Usuario;
use App\Models\Tipo_Empleado;


class SocioMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $header = getallheaders();
        $token = $header['token'];

        //$valido = ValidadorJWT::VerificarToken($token);
        $usuario = ValidadorJWT::ObtenerUsuario($token);
        //$usuario = Usuario::where('usuario', $usuario->usuario)->first();
        //$tipo_empleado = Tipo_Empleado::where('id', $usuario->tipo_empleado_id)->first();

        if ($usuario->tipo_usuario == 'socio') 
        {
            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();
            $resp = new Response();
            $resp->getBody()->write($existingContent);
            return $resp;
        }
        else 
        {
            $response = new Response();
            throw new \Slim\Exception\HttpForbiddenException($request);
            //$response->getBody()->write("Token Invalido");
            return $response->withStatus(403);

        }

    }
}
