<?php 
include_once '../../config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Botão Cancelar- leva o usuario a pagina aluguer.php
if(isset($_POST['cancelar'])){
    header('location: ../motorista.php');
}
//Verficar o metodo que trás os dados
if (isset($_POST['actualizar'])) {
  $Nome =  mysqli_escape_string($conexao,$_POST['nome']);
  $CartaConducao =  mysqli_escape_string($conexao,$_POST['cartaConducao']);
  $Telefone =  mysqli_escape_string($conexao,$_POST['telefone']);
  $Endereco =  mysqli_escape_string($conexao,$_POST['endereco']);
  $EstadoMotorista = 'Activo';
  $MotoristaID = mysqli_escape_string($conexao,$_POST['MotoristaID']);

  //Dado de Usuario
  $Email =  mysqli_escape_string($conexao,$_POST['email']);
  $Senha =  mysqli_escape_string($conexao,$_POST['senha']);
  $Senha = password_hash($Senha,PASSWORD_DEFAULT);
  $UsuarioID = mysqli_escape_string($conexao,$_POST['UsuarioID']);
 
  if(empty($Nome)){
    $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
    Digite o seu Nome!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../motorista.php");
  }elseif(empty($CartaConducao)){
    $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
    Insira o Nº da Carta de Condução!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../motorista.php");
  }elseif(empty($Telefone)){
    $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
    Insira o Seu Número de Telefone!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../motorista.php");
  }elseif(empty($Endereco)){
    $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
    Digite o seu Endereço
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../motorista.php");
  }elseif(empty($Email)){
    $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
    Digite o seu Endereço de Email!
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../motorista.php");
  }else{
    //verificar se existe um veiculo com este nome
    $sql="SELECT m.CartaConducao FROM motoristas m inner join usuarios u on m.UsuarioID = u.UsuarioID WHERE EstadoMotorista = 'Activo' and MotoristaID != $MotoristaID";
    $query = mysqli_query($conexao,$sql);
    $dados=mysqli_fetch_array($query);
    $CartaConducaoAnterior = $dados['CartaConducao'];

    if($CartaConducao == "$CartaConducaoAnterior"){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Esta Carta de Condução já está Registrada!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../motorista.php");
    }else{
        

      //Consulta para inserir marcacao de Aluguer
      $sql ="UPDATE `motoristas` SET `Nome` = ?, `CartaConducao` = ?, `Telefone` = ?, `Endereco` = ? WHERE `motoristas`.`MotoristaID` = ?";
      //Preparar a consulta
      $preparar = mysqli_prepare($conexao,$sql);
      if ($preparar==false) {
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
      }
      //vincular os parametros
      mysqli_stmt_bind_param($preparar,"ssssi",$Nome,$CartaConducao,$Telefone,$Endereco,$MotoristaID);

      //Executar a consulta
      if (mysqli_stmt_execute($preparar)) {
          $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
          Dados do Motorista Actualizados com Sucesso.
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          header("location:../motorista.php");
      }else {
          $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
          Erro ao Actualizar Dados do Motorista!
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          header("location:../motorista.php");
      }

      //DADOS DO USUÁRIO
      //Consulta para inserir marcacao de Aluguer
      $sql ="UPDATE `usuarios` SET `Email` = ?, `Senha` = ? WHERE `usuarios`.`UsuarioID` = ?";
      //Preparar a consulta
      $preparar = mysqli_prepare($conexao,$sql);
      if ($preparar==false) {
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
      }
      //vincular os parametros
      mysqli_stmt_bind_param($preparar,"ssi",$Nome,$Senha,$UsuarioID);

      //Executar a consulta
      if (mysqli_stmt_execute($preparar)) {
        $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
        Dados do Usuário Actualizados com Sucesso.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
      }else {
        $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
        Erro ao Actualizar Dados do Usuário!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
      }
          
    } 
  }    

}
//Fechar a e consulta e a conexao
mysqli_stmt_close($preparar);
mysqli_close($conexao);
?>