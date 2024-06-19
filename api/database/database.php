<?php
class database {


public function QUERY($query, $params = null, $close = true, $debug = true){
    $result = null;
    

    $host = 'localhost:3307';
    $dbname = 'crud';
    $user = 'root';
    $pass = '';

    $conn = new PDO(
        "mysql:host=$host;
        dbname=$dbname",
        $user,
        $pass,
        array(PDO::ATTR_PERSISTENT => true));

    if($debug){
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

   try{
        if($params != null){
            $user = $conn->prepare($query);
            $user->execute($params);
            $result = $user->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $user = $conn->prepare($query);
            $user->execute();
            $result = $user->fetchAll(PDO::FETCH_ASSOC);
        }
   }catch(PDOException $e){
        return false;
   }
    
    if( $close){
        $conn = null;
    }
   
    return $result;
}

}

