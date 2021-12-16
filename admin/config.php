<?php
// my sqli / My sqli oop |PDO

// PDO => 


$dsn = "mysql:host=localhost;dbname=php61";
$username = "root";
$password = "";
try{
    $con = new PDO($dsn , $username ,$password);
    // echo "connect";

}catch(PDOExecption $e){
    echo $e;
}


?>