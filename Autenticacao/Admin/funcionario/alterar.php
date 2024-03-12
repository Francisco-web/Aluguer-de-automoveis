<?php 
include_once '../../credencias/config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Botão Cancelar- leva o usuario a pagina aluguer.php
if(isset($_POST['cancelar'])){
    header('location: ../funcionario.php');
}

//Verficar o metodo que trás os dados
if (isset($_POST['actualizar'])) {
  //Dados do usuario
  $Nome =  strip_tags($_POST['nome']);
  $provincia =  strip_tags($_POST['provincia']);
  $municipio =  strip_tags($_POST['municipio']);
  $bairro =  strip_tags($_POST['bairro']);
  $telefone =  strip_tags($_POST['telefone']);
  //Dados do Documento
  $Documento =  strip_tags($_POST['documento']);
  $numDocumento=  strip_tags($_POST['numDoc']);
  $DataValidade =  strip_tags($_POST['dataValidade']);
  $DocumentoID = strip_tags($_POST['DocumentoID']);
  $UsuarioID = strip_tags($_POST['UsuarioID']);
  $SituacaoD= 1;
 
  if(empty($Nome)){
    $_SESSION['msg_edit_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Digite o Nome!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_funcionario.php?id=$UsuarioID");
  }elseif(empty($Documento)){
    $_SESSION['msg_edit_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Seleciona o Documento!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_funcionario.php?id=$UsuarioID");
  }elseif(empty($numDocumento)){
    $_SESSION['msg_edit_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira o Número do Documento.
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_funcionario.php?id=$UsuarioID");
  }elseif(empty($DataValidade)){
    $_SESSION['msg_edit_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira a Data de validade!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_funcionario.php?id=$UsuarioID");
  }else{
    
    //Consulta para alterar Usuario
    $sql ="UPDATE `usuarios` SET `Nome` =:nome, `Telefone` =:telefone, `Provincia` =:provincia, `Municipio` =:municipio, `Bairro` =:bairro, `DocumentoID` =:documentoID WHERE `usuarios`.`UsuarioID` =:usuarioID";
    //Preparar a consulta
    $preparar_alterar_func = $conexao->prepare($sql);
    if ($preparar_alterar_func==false) {
      $_SESSION['msg_edit_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta! Consulte o Admin do sistema.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_funcionario.php?id=$UsuarioID");
    }
    //vincular os parametros
    $preparar_alterar_func->bindParam(':nome',$Nome,PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':provincia',$provincia, PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':municipio',$municipio, PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':bairro',$bairro, PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':telefone',$telefone, PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':documentoID',$DocumentoID, PDO::PARAM_INT);
    $preparar_alterar_func->bindParam(':usuarioID',$UsuarioID, PDO::PARAM_INT);
    $preparar_alterar_func->execute();
    //Executar a consulta
    if ($preparar_alterar_func->rowCount()) {
      $_SESSION['msg_edit_func']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
        Dados do Usuário Alterado com Sucesso.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_funcionario.php?id=$UsuarioID.php");
    }else{
      $_SESSION['msg_edit_func']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
        Erro ao Alterar Dados do Usuário!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_funcionario.php?id=$UsuarioID");
    }  
    //Consulta para inserir Usuario
    $sql ="UPDATE `documentos` SET `Documento` =:documento, `NumDocumento` =:numDocumento, `dataValidade` =:dataValidade, `SituacaoDoc` =:situacaoDoc WHERE `documentos`.`DocumentoID` =:documentoID";
    //Preparar a consulta
    $preparar_alterar_doc = $conexao->prepare($sql);
    if ($preparar_alterar_doc ==false) {
        $_SESSION['msg_edit_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../edit_funcionario.php?id=$UsuarioID");
    }
    
    //vincular os parametros
    $preparar_alterar_doc->bindParam(":documento",$Documento,PDO::PARAM_STR);
    $preparar_alterar_doc->bindParam(":numDocumento",$numDocumento,PDO::PARAM_STR);
    $preparar_alterar_doc->bindParam(":dataValidade",$DataValidade,PDO::PARAM_STR);
    $preparar_alterar_doc->bindParam(":situacaoDoc",$SituacaoD,PDO::PARAM_INT);
    $preparar_alterar_doc->bindParam(":documentoID",$DocumentoID,PDO::PARAM_INT);
    $preparar_alterar_doc->execute();
    //Executar a consulta
    if ($preparar_alterar_doc->execute()) {
      $_SESSION['msg_edit_func']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
      Dados do Usuário Alterado com Sucesso.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_funcionario.php?id=$UsuarioID.php");
    }else {
      $_SESSION['msg_edit_func']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
        Erro ao Alterar Dados do Usuário!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_funcionario.php?id=$UsuarioID");
    }  
  } 

}

?>