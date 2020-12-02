<?php

namespace Config;

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UsuarioController;
use App\Controllers\InscripcionController;
use App\Controllers\Tipo_EmpleadoController;
use App\Controllers\SectorController;
use App\Controllers\MesaController;
use App\Controllers\PedidoController;
use App\Controllers\OrdenController;
use App\Controllers\Estado_MesaController;
use App\Controllers\Estado_EmpleadoController;
use App\Controllers\Estado_PedidoController;
use App\Middlewares\UsuarioValidateMiddleware;
use App\Middlewares\RegistroMiddleware;
use App\Middlewares\ExisteNombreMiddleware;
use App\Middlewares\ExisteUsuarioMiddleware;
use App\Middlewares\Tipo_UsuarioMiddleware;
use App\Middlewares\LoginMiddleware;
use App\Middlewares\MozoMiddleware;
use App\Middlewares\SocioMiddleware;

return function ($app){
        //REGISTRO
        $app->post('/registro', UsuarioController::class . ':add')->add(ExisteUsuarioMiddleware::class)
                                                                ->add(RegistroMiddleware::class);
    
        //LOGIN
        $app->post('/login', UsuarioController::class . ':login')->add(LoginMiddleware::class);                                                   
        
        //TIPO EMPLEADO
        $app->post('/tipo_empleado', Tipo_EmpleadoController::class . ':addTipo_empleado')->add(UsuarioValidateMiddleware::class);
                                                                                
        //$app->get('/usuario/{email}', UsuarioController::class . ':getUsuario');
        //SECTOR
        $app->post('/sector', SectorController::class . ':addSector')->add(UsuarioValidateMiddleware::class);
        //$app->post('/inscripcion/{id_materia}', InscripcionController::class . ':addInscripcion')->add(UsuarioValidateMiddleware::class);
    
        //MESA
        $app->group('/mesa', function (RouteCollectorProxy $group)
        {
                $group->post('/mesa', MesaController::class . ':addMesa');
                //SOLO MOZO MIDDLEWARE
                $group->post('/{codigo}', MesaController::class . ':cambioEstado')->add(MozoMiddleware::class);
                //SOLO SOCIO MIDDLEWARE
                $group->put('/{codigo}', MesaController::class . ':cerrarMesa')->add(SocioMiddleware::class);
        })->add(UsuarioValidateMiddleware::class);
        //$app->put('/notas/{id_materia}', NotaController::class . ':addNota')->add(UsuarioValidateMiddleware::class);

        //ESTADO MESA
        $app->post('/estado_mesa', Estado_MesaController::class . ':addEstado_mesa')->add(UsuarioValidateMiddleware::class);
             
        //ESTADO EMPLEADO
        $app->post('/estado_empleado', Estado_EmpleadoController::class . ':addEstado_empleado')->add(UsuarioValidateMiddleware::class);
        //$app->get('/inscripcion/{id_materia}', InscripcionController::class . ':inscriptos')->add(UsuarioValidateMiddleware::class);

        //ESTADO PEDIDO
        $app->post('/estado_pedido', Estado_PedidoController::class . ':addEstado_pedido')->add(UsuarioValidateMiddleware::class);

        //PEDIDO
        $app->group('/pedido', function (RouteCollectorProxy $group)
        {
                $group->post('[/]', PedidoController::class . ':addPedido');
                $group->get('[/]', PedidoController::class . ':getAllPedido')->add(SocioMiddleware::class);
                $group->get('/{id}', PedidoController::class . ':getPedido');
        })->add(UsuarioValidateMiddleware::class);

        //ORDEN
        $app->group('/orden', function (RouteCollectorProxy $group)
        {
                $group->post('[/]', OrdenController::class . ':addOrden');
                $group->post('/{id}', OrdenController::class . ':cambioEstado');
                $group->get('/sector', OrdenController::class . ':getOrdenPorSector');
        })->add(UsuarioValidateMiddleware::class);
        
        //CLIENTE
        $app->get('/cliente/{codigo}', PedidoController::class . ':getPedidoCliente');
        //$app->post('/turno', TurnoController::class . ':addTurno')->add(UsuarioValidateMiddleware::class);
    
        //PUNTO 7 ->add(ExisteServicioMiddleware::class)
        //$app->post('/stats[/{tipo}]', ServicioController::class . ':traerTipo')->add(UsuarioValidateMiddleware::class);
        

};