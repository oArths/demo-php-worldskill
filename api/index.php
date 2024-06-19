<?php
include_once(dirname(__FILE__) . "./core/router.php");
include_once(dirname(__FILE__) . "./controller/controller.php");



$router = new router;
$controle = new Controller;
$url = '/crud-php-worldskill/api';


$router->add('POST', $url . '/createuser', function() use ($controle){
    $data = json_decode(file_get_contents('php://input'), true);

    $response = $controle->createUser($data);

    echo json_encode($response);


});
$router->add('GET', $url . '/listuser', function() use ($controle){
    

    $response = $controle->listUser();

    echo json_encode($response);


});
$router->add('PUT', $url . '/updateUser', function() use ($controle){
    $get = json_decode(file_get_contents('php://input'), true);
    $header = getallheaders();
    
    if(empty($header['Authorization'])){
        echo json_encode('authetich user');
        return;
    }else{
        $clear = explode(" ", $header['Authorization']);
        $token = $clear[1];
    }
    $data = [
        'data' => $get,
        'token' => $token
    ];

    $response = $controle->UpdateUser($data);

    echo json_encode($response);


});
$router->add('DELETE', $url . '/deleteuser', function() use ($controle){
    $data = json_decode(file_get_contents('php://input'), true);

    $response = $controle->DeleteUser($data);

    echo json_encode($response);


});

$router->run();
?>