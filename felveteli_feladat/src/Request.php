<?php

namespace felveteli;

class Request{
    private array $server;
    public function __construct(){
        $this->server = $_SERVER;
    }

    public function getServer(string $name){
        return $this->server[$name] ?? null;
    }

    public function getUri(){
        return parse_url($this->getServer('REQUEST_URI'));
    }

    public function getPath(){
        $uri = $this->getUri();
        $path = explode('/',$uri['path']);
        if(count($path)==3 && $path[1]=='parcels'){
            return '/parcels/id';
        }
        return $uri['path'];
    }

    public function getParcelNumber(){
        $uri = $this->getUri();
        $path = explode('/',$uri['path']);
        return $path[2];
    }
    public function getMethod():string{
        return $this->getServer('REQUEST_METHOD');
    }
    
}