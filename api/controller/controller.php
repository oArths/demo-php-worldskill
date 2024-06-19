<?php
include_once('./database/database.php');
include_once('./auth/auth.php');
class Controller{
    public function erro_messager($men){
        $messange = [
            'erro' => $men
        ];

        return $messange;
    }
    public function messager($men){
        $messange = [
            'messange' => $men
        ];

        return $messange;
    }
    public function createUser($params){
        $db = new database;
        $auth = new Auth;

        $name = isset($params['name']) ? $params['name'] : null;
        $email = isset($params['email']) ? $params['email'] : null;
        $pass = isset($params['pass']) ? $params['pass'] : null;

        if($name == null || $email == null || $pass == null){
            http_response_code(401);
            return $this->erro_messager('campos vazios');
        }
        $prepare = [
            ':email' => $email,
        ];
        $consulta = $db->QUERY('SELECT * FROM user WHERE email = :email', $prepare);
        
        if(!empty($consulta)){
            http_response_code(402);
            return $this->erro_messager('usurario ja esxite');
        }

        $token = $auth->create_token($email);

       
        // return $token;

        $prepare = [
            ':nome' => $name,
            ':email' => $email,
            ':pass' => $pass
        ];

        $insert =  $db->QUERY("INSERT INTO user (name, email, senha) VALUES ( :nome, :email, :pass)", $prepare);

        $men = [
            'messager' => 'usuario cadastrado com sucesso',
            'token' => $token
        ];

        if($insert !== false){
            http_response_code(201);
            return $this->messager($men);
        }
        return $insert;

    }
    public function listUser(){
        $db = new database;

        $consulta = $db->QUERY("SELECT * FROM user");

        return $consulta;
    }
    public function UpdateUser($params){
        $db = new database;
        $auth = new Auth;

        $name = isset($params['data']['name']) ? $params['data']['name'] : null;
        $email = isset($params['data']['email']) ? $params['data']['email'] : null;
        $pass = isset($params['data']['pass']) ? $params['data']['pass'] : null;
        $token = isset($params['token']) ? $params['token'] : null;

        $valid = $auth->valid_token($token);

        if($valid === false){
            http_response_code(401);
            return $this->erro_messager("token invalkido ou expirado");
        }
        
        
        if($name == null || $email == null || $pass == null){
            http_response_code(401);
            return $this->erro_messager('campos vazios');
        }
        $consulta = $db->QUERY("SELECT * FROM user");
        
        $prepare = [
            ':email' => $valid->email,
        ];
        // return $prepare;
        $consulta = $db->QUERY('SELECT * FROM user WHERE email = :email', $prepare);
        
        if(empty($consulta)){
            http_response_code(402);
            return $this->erro_messager('usurario não existe');
        }
        
        $updateinser = [
            ':nome' => $name,
            ':email' => $email,
            'oldemail'=> $valid->email,
            ':pass' => $pass
        ];

        $update = $db->QUERY("UPDATE user SET name = :nome, email = :email, senha = :pass WHERE email = :oldemail", $updateinser);
        if(empty($update)){
            http_response_code(201);
            return $this->messager("ususario atualizado com sucesso");
        }
    }
    public function DeleteUser($params){
        $db = new database;

        $email = isset($params['email']) ? $params['email'] : null;

        if($email == null){
            http_response_code(401);
            return $this->erro_messager('campos vazios');
        }
        $prepare = [
            ':email' => $email,
        ];
        $consulta = $db->QUERY('SELECT * FROM user WHERE email = :email', $prepare);

        if(empty($consulta)){
            http_response_code(402);
            return $this->erro_messager('usurario não existe');
        }
        $updateinser = [
            ':email' => $email,
        ];

        $update = $db->QUERY("DELETE FROM user WHERE  email = :email ", $updateinser);
        
        if(empty($update)){
            http_response_code(201);
            return $this->messager("ususario excluido com sucesso");
        }
    }
}

?>