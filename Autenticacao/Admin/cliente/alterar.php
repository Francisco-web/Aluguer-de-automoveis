<?php 
include_once '../../credencias/config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Botão Cancelar- leva o usuario a pagina aluguer.php
if(isset($_POST['cancelar'])){
    header('location: ../cliente.php');
}

//Verficar o metodo que trás os dados
if (isset($_POST['actualizar'])) {
  //Dados do usuario
  $Nome =  strip_tags($_POST['nome']);
  $Provincia =  strip_tags($_POST['provincia']);
  $Municipio =  strip_tags($_POST['municipio']);
  $Bairro =  strip_tags($_POST['bairro']);
  $Telefone =  strip_tags($_POST['telefone']);
  $Situacao =  strip_tags($_POST['situacao']);
  //Dados do Documento
  $Documento =  strip_tags($_POST['documento']);
  $numDocumento=  strip_tags($_POST['numDocumento']);
  $DataValidade =  strip_tags($_POST['dataValidade']);
  $DocumentoID = strip_tags($_POST['DocumentoID']);
  $UsuarioID = strip_tags($_POST['UsuarioID']);
  $ClienteID = strip_tags($_POST['ClienteID']);
  $SituacaoD= 1;
 
  if(empty($Nome)){
    $_SESSION['msg_edit_cl']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Digite o Nome!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_cliente.php?id=$ClienteID");
  }elseif(empty($Documento)){
    $_SESSION['msg_edit_cl']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Seleciona o Documento!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_cliente.php?id=$ClienteID");
  }elseif(empty($numDocumento)){
    $_SESSION['msg_edit_cl']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira o Número do Documento.
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_cliente.php?id=$ClienteID");
  }elseif(empty($DataValidade)){
    $_SESSION['msg_edit_cl']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira a Data de validade!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_cliente.php?id=$ClienteID");
  }else{
    
    //Consulta para alterar Usuario
    $sql ="UPDATE `usuarios` SET `Nome` =:nome,Telefone=:telefone,Provincia=:provincia,Municipio=:municipio,Bairro=:bairro,Situacao=:situacao	 WHERE `UsuarioID` =:usuarioID";
    //Preparar a consulta
    $preparar_alterar_func = $conexao->prepare($sql);
    if ($preparar_alterar_func==false) {
      $_SESSION['msg_edit_cl']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta! Consulte o Admin do sistema.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_cliente.php?id=$ClienteID");
    }
    //vincular os parametros
    $preparar_alterar_func->bindParam(':nome',$Nome,PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':telefone',$Telefone,PDO::PARAM_INT);
    $preparar_alterar_func->bindParam(':provincia',$Provincia,PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':municipio',$Municipio,PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':bairro',$Bairro,PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':situacao',$Situacao,PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':usuarioID',$UsuarioID,PDO::PARAM_INT);
    $preparar_alterar_func->execute();
    //Executar a consulta
    if ($preparar_alterar_func->rowCount()) {
      $_SESSION['msg_edit_cl']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
        Dados do Usuário Actualizado com Sucesso.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_cliente.php?id=$ClienteID.php");
    }else{
      $_SESSION['msg_edit_cl']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
        Erro ao Alterar Dados do Usuário!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_cliente.php?id=$ClienteID");
    }  
      //Consulta para inserir Usuario
    $sql ="UPDATE `documentos` SET `Documento` =:documento, `NumDocumento` =:numDocumento, `dataValidade` =:dataValidade, `SituacaoDoc` =:situacaoDoc WHERE `documentos`.`DocumentoID` =:documentoID";
    //Preparar a consulta
    $preparar_alterar_doc = $conexao->prepare($sql);
    if ($preparar_alterar_doc ==false) {
        $_SESSION['msg_edit_cl']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../edit_cliente.php?id=$ClienteID");
    }
    
    //vincular os parametros
    $preparar_alterar_doc->bindParam(':documento',$Documento,PDO::PARAM_STR);
    $preparar_alterar_doc->bindParam(':numDocumento',$numDocumento,PDO::PARAM_STR);
    $preparar_alterar_doc->bindParam(':dataValidade',$DataValidade,PDO::PARAM_STR);
    $preparar_alterar_doc->bindParam(':situacaoDoc',$SituacaoD,PDO::PARAM_INT);
    $preparar_alterar_doc->bindParam(':documentoID',$DocumentoID,PDO::PARAM_INT);
    $preparar_alterar_doc->execute();
    //Executar a consulta
    if ($preparar_alterar_doc->execute()) {
      $_SESSION['msg_edit_cl']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
        Dados do Usuário Actualizado com Sucesso.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
    }else {
      $_SESSION['msg_edit_cl']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
        Erro ao Atualizar Dados do Documento!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_cliente.php?id=$ClienteID");
    }  
  } 
}

?>