<?php 
include_once '../../credencias/config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Botão Cancelar- leva o usuario a pagina aluguer.php
if(isset($_POST['cancelar'])){
    header('location: ../motorista.php');
}

//Verficar o metodo que trás os dados
if (isset($_POST['actualizar'])) {
  //Dados do usuario
  $Nome =  strip_tags($_POST['nome']);
  $Provincia =  strip_tags($_POST['provincia']);
  $Municipio =  strip_tags($_POST['municipio']);
  $Bairro =  strip_tags($_POST['bairro']);
  $Telefone =  strip_tags($_POST['telefone']);
  $Email =  strip_tags($_POST['email']);
  //Dados Motorista
  $MotoristaID = strip_tags($_POST['motoristaID']);
  $SituacaoMotorista =  strip_tags($_POST['situacaoMotorista']);
  //Dados do Documento
  $Documento =  strip_tags($_POST['documento']);
  $numDocumento=  strip_tags($_POST['numeroDocumento']);
  $DataValidade =  strip_tags($_POST['dataValidadeDocumento']);
  $DocumentoID = strip_tags($_POST['documentoID']);
  $UsuarioID = strip_tags($_POST['usuarioID']);
  $SituacaoD= 1;
 
  if(empty($Nome)){
    $_SESSION['msg_edit_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Digite o Nome!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_motorista.php?id=$UsuarioID");
  }elseif(empty($Documento)){
    $_SESSION['msg_edit_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Seleciona o Documento!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_motorista.php?id=$UsuarioID");
  }elseif(empty($numDocumento)){
    $_SESSION['msg_edit_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira o Número do Documento.
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_motorista.php?id=$UsuarioID");
  }elseif(empty($DataValidade)){
    $_SESSION['msg_edit_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira a Data de validade!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../edit_motorista.php?id=$UsuarioID");
  }else{
    
    //Consulta para alterar Usuario
    $sql ="UPDATE `usuarios` SET `Nome` =:nome,`Email` =:email,`Telefone` =:telefone, `Provincia` =:provincia, `Municipio` =:municipio, `Bairro` =:bairro, `DocumentoID` =:documentoID WHERE `usuarios`.`UsuarioID` =:usuarioID";
    //Preparar a consulta
    $preparar_alterar_func = $conexao->prepare($sql);
    if ($preparar_alterar_func==false) {
      $_SESSION['msg_edit_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta! Consulte o Admin do sistema.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_motorista.php?id=$UsuarioID");
    }
    //vincular os parametros
    $preparar_alterar_func->bindParam(':nome',$Nome,PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':email',$Email,PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':provincia',$Provincia, PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':municipio',$Municipio, PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':bairro',$Bairro, PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':telefone',$Telefone, PDO::PARAM_STR);
    $preparar_alterar_func->bindParam(':documentoID',$DocumentoID, PDO::PARAM_INT);
    $preparar_alterar_func->bindParam(':usuarioID',$UsuarioID, PDO::PARAM_INT);
    $preparar_alterar_func->execute();
    //Executar a consulta
    if ($preparar_alterar_func->rowCount()) {
      $_SESSION['msg_edit_motorista']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
        Dados do Usuário Alterado com Sucesso.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_motorista.php?id=$UsuarioID.php");
    }else{
      $_SESSION['msg_edit_motorista']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
        Erro ao Alterar Dados do Motorista!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_motorista.php?id=$UsuarioID");
    }  
    //Consulta para inserir Usuario
    $sql ="UPDATE `documentos` SET `Documento` =:documento, `NumDocumento` =:numDocumento, `dataValidade` =:dataValidade, `SituacaoDoc` =:situacaoDoc WHERE `documentos`.`DocumentoID` =:documentoID";
    //Preparar a consulta
    $preparar_alterar_doc = $conexao->prepare($sql);
    if ($preparar_alterar_doc ==false) {
        $_SESSION['msg_edit_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../edit_motorista.php?id=$UsuarioID");
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
      $_SESSION['msg_edit_motorista']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
      Dados do Motorista Alterado com Sucesso.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_motorista.php?id=$UsuarioID.php");
    }else {
      $_SESSION['msg_edit_motorista']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
        Erro ao Alterar Dados do Motorista!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_motorista.php?id=$UsuarioID");
    }

    //Actualizar dados do Motorista
    $sql ="UPDATE `motoristas` SET `SituacaoMotorista`=:situacaoMotorista WHERE `motoristas`.`MotoristaID` =:motoristaID";
    //Preparar a consulta
    $preparar_alterar_motorista = $conexao->prepare($sql);
    if ($preparar_alterar_motorista ==false) {
        $_SESSION['msg_edit_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../edit_motorista.php?id=$UsuarioID");
    }
    
    //vincular os parametros
    $preparar_alterar_motorista->bindParam(':situacaoMotorista',$SituacaoMotorista, PDO::PARAM_STR);
    $preparar_alterar_motorista->bindParam(":motoristaID",$MotoristaID,PDO::PARAM_INT);
    $preparar_alterar_motorista->execute();
    //Executar a consulta
    if ($preparar_alterar_motorista->execute()) {
      $_SESSION['msg_edit_motorista']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
      Dados do Motorista Alterado com Sucesso.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_motorista.php?id=$UsuarioID.php");
    }else {
      $_SESSION['msg_edit_motorista']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
        Erro ao Alterar Dados do Motorista!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../edit_motorista.php?id=$UsuarioID");
    }  
  } 

}

?>