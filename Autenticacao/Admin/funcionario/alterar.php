<?php 
include_once '../../config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Botão Cancelar- leva o usuario a pagina aluguer.php
if(isset($_POST['cancelar'])){
    header('location: ../funcionario.php');
}
//Redefinr Senha
if (isset($_POST['redefinir_senha'])) {
  $UsuarioID = mysqli_escape_string($conexao,$_POST['UsuarioID']);
  $Senha =  123456;
  $Senha = password_hash($Senha,PASSWORD_DEFAULT);

  //Consulta para apagar registo de aluguer
  $sql="UPDATE `usuarios` SET `Senha` = ? WHERE `usuarios`.`UsuarioID` = ?";
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
  mysqli_stmt_bind_param($preparar,"si",$Senha,$UsuarioID);
  //Exeucutar a preparação 
  if (mysqli_stmt_execute($preparar)) {
      //mensagem de sucesso
      $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
      Senha Restaurada
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
  </div>";
  header('location:../cliente.php');
  }else {
      //mensagem de sucesso de erro
      $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
      Erro ao restaurar Senha
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
  </div>";
  header('../cliente.php');
  }

}
//Verficar o metodo que trás os dados
if (isset($_POST['actualizar'])) {
  $Nome =  mysqli_escape_string($conexao,$_POST['nome']);
  $FuncionarioID = mysqli_escape_string($conexao,$_POST['FuncionarioID']);
  //Dado de Usuario
  $Email =  mysqli_escape_string($conexao,$_POST['email']);
  $UsuarioID = mysqli_escape_string($conexao,$_POST['UsuarioID']);
 
  if(empty($Nome)){
    $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
    Digite o seu Nome!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../funcionario.php");
  }elseif(empty($Email)){
    $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
    Digite o seu Endereço de Email!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../funcionario.php");
  }else{
    //verificar se existe um veiculo com este nome
    $sql="SELECT f.Nome FROM funcionarios f inner join usuarios u on f.UsuarioID = u.UsuarioID WHERE EstadoFuncionario = 'Activo' and FuncionarioID != $FuncionarioID";
    $query = mysqli_query($conexao,$sql);
    $dados=mysqli_fetch_array($query);
    $NomeAnterior = $dados['Nome'];

    if($Nome == "$NomeAnterior"){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Este Funcionário já está Registrado!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../funcionario.php");
    }else{
        

      //Consulta para inserir marcacao de Aluguer
      $sql ="UPDATE `funcionarios` SET `Nome` = ? WHERE `funcionarios`.`FuncionarioID` = ?";
      //Preparar a consulta
      $preparar = mysqli_prepare($conexao,$sql);
      if ($preparar==false) {
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
      }
      //vincular os parametros
      mysqli_stmt_bind_param($preparar,"si",$Nome,$FuncionarioID);

      //Executar a consulta
      if (mysqli_stmt_execute($preparar)) {
          $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
          Dados do Funcionário Actualizados com Sucesso.
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          header("location:../funcionario.php");
      }else {
          $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
          Erro ao Actualizar Dados do Funcionário!
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          header("location:../funcionario.php");
      }

      //DADOS DO USUÁRIO
      //Consulta para inserir marcacao de Aluguer
      $sql ="UPDATE `usuarios` SET `Email` = ? WHERE `usuarios`.`UsuarioID` = ?";
      //Preparar a consulta
      $preparar = mysqli_prepare($conexao,$sql);
      if ($preparar==false) {
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
      }
      //vincular os parametros
      mysqli_stmt_bind_param($preparar,"si",$Email,$UsuarioID);

      //Executar a consulta
      if (mysqli_stmt_execute($preparar)) {
        $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
        Dados do Usuário Actualizados com Sucesso.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
      }else {
        $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
        Erro ao Actualizar Dados do Usuário!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
      }
          
    } 
  }    

}
//Fechar a e consulta e a conexao
mysqli_stmt_close($preparar);
mysqli_close($conexao);
?>