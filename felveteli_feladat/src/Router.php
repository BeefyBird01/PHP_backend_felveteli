<?php

namespace felveteli;

class Router{

    private const HTTP_GET = 'GET';
    private const HTTP_POST = 'POST';

    public function __construct(private array $routes = []){}

    public function get(string $path, callable $callback):void{
        $this->routes[self::HTTP_GET][$path] = $callback;
    }

    public function post(string $path, callable $callback):void{
        $this->routes[self::HTTP_POST][$path] = $callback;
    }
    public function dispatch(Request $req){
        $path = $req->getPath();
        $method = $req->getMethod();

       $callback = $this->routes[$method][$path] ?? false;
       if(!$callback){
        http_response_code(404);
            echo 'Not found';
            exit;
       }
       return $callback;
    }
}