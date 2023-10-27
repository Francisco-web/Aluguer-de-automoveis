
<?php
//Conexão com o banco de dados
include_once '../../config_db.php';
//Inicar Sessão
session_start();
ob_start();

//Mudar Disponibilidade do veiculo

if (isset($_GET['Disponivel']) && isset($_GET['id'])) {
    $CarroID = $_GET['id'];
    $Disponivel = $_GET['Disponivel'];// verifica o valor da Url(Não)
    $DisponivelCarro = 1;//Disponivel = 1/ indiponivel = 0

    if ($Disponivel != 'Não') {
        //Mensagem de Erro
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Não Pode Mudar a Disponiblidade do Veiculo!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../veiculo.php");
    }else{
        //Consulta para apagar registo de aluguer
        $sql="UPDATE `carros` SET `Disponivel` = ? WHERE `carros`.`CarroID` = ?";
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
        mysqli_stmt_bind_param($preparar,"ii",$DisponivelCarro,$CarroID);
        //Exeucutar a preparação 
        if (mysqli_stmt_execute($preparar)) {
            //mensagem de sucesso
            $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
            Veículo Disponivel para Aluguer.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header('location:../veiculo.php');
        }else {
            //mensagem de sucesso de erro
            $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
            Erro: Veículo Não Disponível.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header('../veiculo.php');
        }
    }
   
}
//Fechar a e consulta e a conexao
mysqli_stmt_close($preparar);
mysqli_close($conexao);