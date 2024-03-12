<?php

$server = "localhost";
$usuario = "root";
$pass = "";
$dbname = "francycarros"; 


try{
    $conexao = new PDO("mysql:host=$server;dbname=$dbname",$usuario,$pass);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $erro){
    echo"Falha ao Conectar com o Banco de dados:{$erro->getMessage()}";
    $conexao = null;
}
?>  