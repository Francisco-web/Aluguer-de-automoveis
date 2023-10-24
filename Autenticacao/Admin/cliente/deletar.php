
<?php
//Conexão com o banco de dados
include_once '../../config_db.php';
//Inicar Sessão
session_start();
ob_start();

if (isset($_GET['id'])) {
    $UsuarioID= $_GET['id'];
    $Estado = 'Apagado';
    //Consulta para apagar registo de aluguer
    $sql="UPDATE `clientes` SET `EstadoCliente` = ? WHERE `clientes`.`ClienteID` = ?";
    //Preparar a consulta
    $preparar=mysqli_prepare($conexao,$sql);
    if ($preparar==false) {
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../cliente.php");
    }
    //VInvular os parametros
    mysqli_stmt_bind_param($preparar,"si",$Estado,$UsuarioID);
    //Exeucutar a preparação 
    if (mysqli_stmt_execute($preparar)) {
        //mensagem de sucesso
        $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
        Cadastro de Cliente Apagado.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header('location:../cliente.php');
    }else {
        //mensagem de sucesso de erro
        $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
        Erro: Cadastro de Cliente Não Apagado.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header('../cliente.php');
    }
}
//Fechar a e consulta e a conexao
mysqli_stmt_close($preparar);
mysqli_close($conexao);