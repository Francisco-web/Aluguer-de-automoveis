
<?php 
include_once '../../config_db.php';//inclui a base de dados
session_start();//Sessão iniciada

//Verficar o metodo que trás os dados
if (isset($_POST['add'])) {
    $veiculo = mysqli_escape_string($conexao,$_POST['idVeiculo']);
    $dataLevantamento = mysqli_escape_string($conexao,$_POST['dataLevantamento']);
    $dataDevolucao = mysqli_escape_string($conexao,$_POST['dataDevolucao']);
    $valorAluguer = mysqli_escape_string($conexao,$_POST['valorAluger']);
    $statusPagamento = mysqli_escape_string($conexao,$_POST['statusPagamento']);
    $Cliente = mysqli_escape_string($conexao,$_POST['idCliente']);
    $motorista = mysqli_escape_string($conexao,$_POST['idMotorista']);
    $atendidoPor= 3;

    if(empty($veiculo)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Selecione o Veículo!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../aluguer.php");
    }elseif(empty($dataLevantamento)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Inisra a Data de Levantamento do Veículo!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../aluguer.php");
    }elseif(empty($dataDevolucao)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira a Data de Devolução do Veículo!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../aluguer.php");
    }elseif(empty($valorAluguer)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Inisra o Valor do Aluguer do Veículo!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../aluguer.php");
    }elseif(empty($Cliente)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Selecione o Cliente!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../aluguer.php");
    }else {
        //Consultapara inserir marcacao de Aluguer
        $sql ="INSERT INTO `aluguer` (`idCliente`, `idVeiculo`, `idMotorista`, `dataInicio`, `dataFim`, `valorAluguel`, `statusPagamento`, `atendidoPor`) VALUES (?,?,?,?,?,?,?,?)";
        //Preparar a consulta
        $preparar = mysqli_prepare($conexao,$sql);
        if ($preparar==false) {
            $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Erro na Preparação da Consulta!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          header("location:../aluguer.php");
        }
        //vincular os parametros
        mysqli_stmt_bind_param($preparar,"iiissssi",$Cliente,$veiculo,$motorista,$dataLevantamento,$dataLevantamento,$valorAluguer,$statusPagamento,$atendidoPor);
        //Executar a consulta
        if (mysqli_stmt_execute($preparar)) {
            $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
            Aluguer de Veículo Feito com Sucesso.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          header("location:../aluguer.php");
        }else {
            $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
            Erro no Aluguer de Veículo!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
          header("location:../aluguer.php");
        }
    }
}

?>