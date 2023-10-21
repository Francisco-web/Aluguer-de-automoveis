
<?php
//Conexão com o banco de dados
include_once '../../config_db.php';
//Inicar Sessão
session_start();
ob_start();

if (isset($_GET['id'])) {
    $CarroID = $_GET['id'];

    //Consulta para apagar registo de aluguer
    $sql="DELETE FROM carros WHERE CarroID = ?";
    //Preparar a consulta
    $preparar=mysqli_prepare($conexao,$sql);
    if ($preparar==false) {
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../veiculo.php");
    }
    //VInvular os parametros
    mysqli_stmt_bind_param($preparar,"i",$CarroID);
    //Exeucutar a preparação 
    if (mysqli_stmt_execute($preparar)) {
        //mensagem de sucesso
        $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
        Cadastro de Veículo Apagado.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header('location:../veiculo.php');
    }else {
        //mensagem de sucesso de erro
        $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
        Erro: Cadastro de Veículo Não Apagado.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header('../veiculo.php');
    }
}