<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "rentacar";

$conexao = mysqli_connect("localhost","root","","rentacar");
mysqli_set_charset($conexao,"utf8");
if(mysqli_connect_error()){
    echo"Falha ao Conectar com o Banco de dados".mysqli_connect_error();
}
?> 