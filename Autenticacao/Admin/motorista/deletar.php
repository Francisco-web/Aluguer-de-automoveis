
<?php
//Conexão com o banco de dados
include_once '../../credencias/config_db.php';
//Inicar Sessão
session_start();
ob_start();

if (isset($_GET['id'])) {
  $UsuarioID= filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
  $sql="SELECT Situacao,Permissao FROM usuarios WHERE UsuarioID=:usuarioID";
  $prepare_verificacao= $conexao->prepare($sql);
  $prepare_verificacao->bindParam(':usuarioID',$UsuarioID,PDO::PARAM_INT);
  $prepare_verificacao->execute();
  $resultado= $prepare_verificacao->fetchAll(PDO::FETCH_ASSOC);
  foreach ($resultado as $dados) {
    $Permissao_db = $dados['Permissao'];
    $Situacao_db = $dados['Situacao'];
  }
  if ($Situacao_db == "Activo") {
    $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Não Pode Apagar um Usuário Activo!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../motorista.php");
  }elseif ($Permissao_db == "Administrador") {
    $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Não Pode Apagar um Usuário Administrador!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../motorista.php");
  }
  //Mudar o Estado do Usuario
  $sql="DELETE FROM `usuarios` WHERE `usuarios`.`UsuarioID` =:usuarioID";
  //Preparar a consulta
  $preparar_apagar_usuario =$conexao->prepare($sql);
  if ($preparar_apagar_usuario==false) {
      $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Erro na Preparação da Consulta, Consulte o Admin do Sistema!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../motorista.php");
  }
  //VInvular os parametros
  $preparar_apagar_usuario->bindParam(':usuarioID',$UsuarioID,PDO::PARAM_INT);
  //Exeucutar a preparação 
  if ($preparar_apagar_usuario->execute()) {
    //mensagem de sucesso
    $_SESSION['msg_func']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
      Motorista Apagado.
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header('location:../motorista.php');
  }else {
    //mensagem de sucesso de erro
    $_SESSION['msg_func']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
      Erro ao Apagar Motorista!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header('../motorista.php');
  }
}

//Fechar a e consulta e a conexao
$preparar->close();
$conexao->close();