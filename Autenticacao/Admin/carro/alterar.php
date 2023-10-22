<?php 
include_once '../../config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Botão Cancelar- leva o usuario a pagina aluguer.php
if(isset($_POST['cancelar'])){
    header('location: ../veiculo.php');
}
//Verficar o metodo que trás os dados
if (isset($_POST['actualizar'])) {
  $Modelo = mysqli_escape_string($conexao,$_POST['modelo']);
  $Ano = mysqli_escape_string($conexao,$_POST['ano']);
  $Placa = mysqli_escape_string($conexao,$_POST['placa']);
  $ValorDiario = mysqli_escape_string($conexao,$_POST['valorDiario']);
  $Descricao = mysqli_escape_string($conexao,$_POST['descricao']);
  $CarroID = mysqli_escape_string($conexao,$_POST['CarroID']);

  if(empty($Modelo)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Digite o Modelo do Veículo!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../veiculo.php");
  }elseif(empty($Ano)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Inisira o Ano do Veículo!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../veiculo.php");
  }elseif(empty($Placa)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Insira a Placa ou Matrícula do Veículo!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../veiculo.php");
  }elseif(empty($ValorDiario)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Inisira o Valor Diário do Veículo!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../veiculo.php");
  }else {
    //verificar se existe um veiculo com este nome
    $sql="SELECT CarroID,Imagem,Modelo,Ano,Placa,Disponivel,ValorDiaria FROM carros  ORDER BY Modelo";
    $query = mysqli_query($conexao,$sql);
    $dados=mysqli_fetch_array($query);
    $ModeloAnterior = $dados['Modelo'];
    $AnoAnterior = $dados['Ano'];
    $ValorDiariaAnterior = $dados['ValorDiaria'];

    if($Modelo == "$ModeloAnterior" && $Ano == "$AnoAnterior" && $ValorDiario == "$ValorDiariaAnterior" ){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Este Veículo já está Cadastrado!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../veiculo.php");
    }else{
        

      //Consulta para inserir marcacao de Aluguer
      $sql ="UPDATE `carros` SET `Modelo` = ?, `Ano` = ?, `Descricao` = ?, `ValorDiaria` = ? WHERE `carros`.`CarroID` = $CarroID";
      //Preparar a consulta
      $preparar = mysqli_prepare($conexao,$sql);
      if ($preparar==false) {
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../veiculo.php");
      }
      //vincular os parametros
      mysqli_stmt_bind_param($preparar,"sssd",$Modelo,$Ano,$Descricao,$ValorDiario);

      //Executar a consulta
      if (mysqli_stmt_execute($preparar)) {
          $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
          Dados do Veículo Actualizados com Sucesso.
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          header("location:../veiculo.php");
      }else {
          $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
          Erro ao Actualizar Dados do Veículo!
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          header("location:../veiculo.php");
      }
          
    } 
  }    

}
//Fechar a e consulta e a conexao
mysqli_stmt_close($preparar);
mysqli_close($conexao);
?>