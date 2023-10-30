
<?php
//Conexão com o banco de dados
include_once '../../config_db.php';
//Inicar Sessão
session_start();
ob_start();

//Mudar Disponibilidade do veiculo

if (isset($_GET['Disponivel']) && isset($_GET['id'])) {
    $TaxaID = $_GET['id'];
    $Disponivel = $_GET['Disponivel'];// verifica o valor da Url(Não)

    if ($Disponivel == 'Antigo') {
        $DisponivelTaxa = 'Actual';//Disponivel = Activo/ indiponivel = Inactivo
    }else{
        $DisponivelUsuario = 'Antigo';//Disponivel = Activo/ indiponivel = Inactivo
        }
        //Consulta para apagar registo de aluguer
        $sql="UPDATE `taxas` SET `EstadoTaxa` = ? WHERE `taxas`.`TaxaID` = ?";
        //Preparar a consulta
        $preparar=mysqli_prepare($conexao,$sql);
        if ($preparar==false) {
            $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Erro na Preparação da Consulta!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../taxa.php");
        }
        //VInvular os parametros
        mysqli_stmt_bind_param($preparar,"si",$DisponivelTaxa,$TaxaID);
        //Exeucutar a preparação 
        if (mysqli_stmt_execute($preparar)) {
            //mensagem de sucesso
            $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
           Estado da Taxa Actualizado com Sucesso.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header('location:../taxa.php');
        }else {
            //mensagem de sucesso de erro
            $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
            Erro: Estado Não Actualizado.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header('../taxa.php');
        }
   
}
//Fechar a e consulta e a conexao
mysqli_stmt_close($preparar);
mysqli_close($conexao);