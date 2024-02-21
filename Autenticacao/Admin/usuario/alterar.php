<?php 
include_once '../../credencias/config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Botão Cancelar- leva o usuario a pagina aluguer.php
if(isset($_POST['cancelar'])){
    header('location: ../usuario.php');
}

//Verficar o metodo que trás os dados
if (isset($_POST['actualizar'])) {
  $Permissao =  strip_tags($_POST['permissao']);
  $SenhaInput = strip_tags($_POST['senha_atualizada']);
  $Senha = password_hash($SenhaInput,PASSWORD_DEFAULT);
  //Dado de Usuario
  $Email =  strip_tags($_POST['email']);
  $UsuarioID = strip_tags($_POST['UsuarioID']);
 
  if(empty($Permissao)){
    $_SESSION['msg_usuario']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
    Seleciona a Permissão de Acesso!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_usuario.php?id=$UsuarioID");
  }elseif(empty($Email)){
    $_SESSION['msg_usuario']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
    Digite o seu Endereço de Email!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_usuario.php?id=$UsuarioID");
  }else{

      //verificar se existe um email com este nome
      $sql="SELECT * FROM usuarios WHERE Email =:email and UsuarioID !=:usuarioID";
      $prepare_email = $conexao->prepare($sql);
      $prepare_email->bindParam(':email', $Email, PDO::PARAM_STR);
      $prepare_email->bindParam(':usuarioID', $UsuarioID, PDO::PARAM_INT);
      $prepare_email->execute();
      if($prepare_email->rowCount() == 1){
        $_SESSION['msg_usuario']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Este usuário já Existe!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../edit_usuario.php?id=$UsuarioID");
      }
      //verificar se existe um senha com este email
      $sql="SELECT Senha FROM usuarios WHERE UsuarioID =:usuarioID and Senha ='' ";
      $prepare_senha = $conexao->prepare($sql);
      $prepare_senha->bindParam(':usuarioID', $UsuarioID, PDO::PARAM_INT);
      $prepare_senha->execute();
      if($prepare_senha->rowCount()==1){
        $_SESSION['msg_usuario']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
          Campo Senha em branco, Insira uma Senha!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../edit_usuario.php?id=$UsuarioID");
      }else{
        
        //DADOS DO USUÁRIO
        //Consulta para inserir marcacao de Aluguer
        $sql ="UPDATE `usuarios` SET `Email`=:email,`Senha`=:senha,`Permissao`=:permissao WHERE UsuarioID =:usuarioID";
        //Preparar a consulta
        $preparar = $conexao->prepare($sql);
        if ($preparar==false) {
          $_SESSION['msg_usuario']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
          Erro na Preparação da Consulta! Consulte o Admin do Sistema. 
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          header("location:../edit_usuario.php?id=$UsuarioID");
        }
        //vincular os parametros
        $preparar->bindParam(":email",$Email,PDO::PARAM_STR);
        $preparar->bindParam(":senha",$Senha, PDO::PARAM_STR);
        $preparar->bindParam(":permissao",$Permissao,PDO::PARAM_STR);
        $preparar->bindParam(":usuarioID",$UsuarioID, PDO::PARAM_INT);

        //Executar a consulta
        if ($preparar->execute()) {
          $_SESSION['msg_usuario']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
          Dados do Usuário Actualizado com Sucesso.
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          header("location:../edit_usuario.php?id=$UsuarioID");
        }else {
          $_SESSION['msg_usuario']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
          Erro ao Actualizar Dados do Usuário!
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          header("location:../edit_usuario.php?id=$UsuarioID");
        }
      } 
  }    
}
//Fechar a e consulta e a conexao
$preparar->close();
$conexao->close();
?>