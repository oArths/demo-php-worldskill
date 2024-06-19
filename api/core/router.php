<?php

class router{
    private $Routes = [];

    public function add($method, $url, $callback){

        $this->Routes[] = [
            'method' => $method,
            'url' => $url,
            'callback' => $callback 
        ];

    }
    public function run(){

        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];
        // $querystring = $_SERVER['QUERY_STRING'];

        foreach($this->Routes as $route){
            if($route['method'] === $method && $route['url'] === $url){
                header('Content-Type: aplication/json');
                call_user_func($route['callback']);
                return;
            }else{
                http_response_code(404);
                header('Content-Type: application/json');
                json_encode('sdsd');
            }
        }


    }
}


?>