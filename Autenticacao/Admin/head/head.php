<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Página Inicial - FrancyCarros</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<?php
include_once '../credencias/config_db.php';
if (!isset($_SESSION)) session_start();// A sessão precisa ser iniciada em cada página diferente
ob_start();
//Verificar sessão
if (!isset($_SESSION['UsuarioID']) AND ($_SESSION['Permissao']!='Administrador')) {
  header("location:../sair.php");
}
$UsuarioID_Admin = $_SESSION['UsuarioID'];
//Dados do Admin
$sql= "SELECT Nome,Permissao,Situacao,Senha,Email,Provincia,Municipio,Bairro FROM usuarios WHERE UsuarioID =:usuarioID";
$prepare_us= $conexao->prepare($sql);
$prepare_us->bindParam(':usuarioID',$UsuarioID_Admin,PDO::PARAM_STR);
$prepare_us->execute();
$resultado=$prepare_us->fetchAll(PDO::FETCH_ASSOC);
foreach($resultado as $dados){
  $Nome_us= $dados['Nome'];
  $Email_us= $dados['Email'];
  $Senha_us=$dados['Senha'];
  $Telefone_us= $dados['Telefone'];
  $Permissao_us= $dados['Permissao'];
  $Provincia_us=$dados['Provincia'];
  $Municipio_us=$dados['Municipio'];
  $Bairro_us=$dados['Bairro'];
  $Situacao_us=$dados['Situacao'];
}
?>
