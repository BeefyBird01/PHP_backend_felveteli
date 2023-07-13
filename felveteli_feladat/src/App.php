<?php

namespace felveteli;

class App{
    public function run():void{
         $router = new Router();

         $routesList = new RoutesList();
         $routesList->configure($router);
         $request = new Request();

         $handler = $router->dispatch($request);
         $handler();
         
    }
}