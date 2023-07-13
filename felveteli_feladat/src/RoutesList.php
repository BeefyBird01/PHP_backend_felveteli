<?php

namespace felveteli;

use felveteli\Controller\UserController;
use felveteli\Controller\ParcelController;

class RoutesList{

    public function configure(Router $router):void{
        $router->get('/users',[UserController::class,'Show']);
        $router->post('/users',[UserController::class,'Create']);
        $router->get('/parcels/id',[ParcelController::class,'Show']);
        $router->post('/parcels',[ParcelController::class,'Create']);
    }
}