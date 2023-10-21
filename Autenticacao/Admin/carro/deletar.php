
<?php
//Conexão com o banco de dados
include_once '../../config_db.php';
//Inicar Sessão
session_start();
ob_start();

//Mudar estado para não visualizar registo
if (isset($_GET['ID'])&& isset($_GET['Apagar'])) {
 //Consulta para mudar estado
  $ID = $_GET['ID'];
  $Estado = 'Apagado';
  $sql="UPDATE `carros` SET `estadoCarro` = 'Apagado' WHERE `carros`.`CarroID` = $ID";
  if (mysqli_query($conexao,$sql)){
    //mensagem de sucesso de erro
    $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
    Cadastro de Veículo Apagado.
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header('../veiculo.php');
  }else {
    //mensagem de sucesso de erro
    $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
    Erro: Cadastro de Veículo Não Apagado.
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header('../veiculo.php');
  }
  
}

if (isset($_GET['id'])) {
    $CarroID = $_GET['id'];
    $Estado = 'Apagado';
    //Consulta para apagar registo de aluguer
    $sql="UPDATE `carros` SET `estadoCarro` = ? WHERE `carros`.`CarroID` = ?";
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
    mysqli_stmt_bind_param($preparar,"si",$Estado,$CarroID);
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