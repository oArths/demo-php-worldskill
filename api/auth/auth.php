<?php
include_once("./database/database.php");

date_default_timezone_set('America/Sao_Paulo');

class Auth{

    private $key = "lua";

    public function create_token($params){
        $db = new database;

       
        $email = $params;
        $now = time();

        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'

        ]; 

        $payload = [
            'email' => $email,
            'create' => $now,
            'exp' => $now + 3600
        ];

        $header = base64_encode(json_encode($header));
        $payload = base64_encode(json_encode($payload));

        $sing = base64_encode(hash_hmac('sha256', $header . "." . $payload, $this->key, true));

        $token = "Bearer  " . $header . "." . $payload . "." . $sing;
        
        return $token;
    }
    public function valid_token($token){

        list($header, $payload, $sing) = explode(".", $token);

        $dec_payload = json_decode(base64_decode($payload));
        $valid_sing = base64_encode(hash_hmac('sha256', $header . "." . $payload, $this->key, true));

        
        if($sing !== $valid_sing){
            return false;
        }
        if($dec_payload->exp < time()){
            return false;
        }
        return $dec_payload;


    }

}
?>