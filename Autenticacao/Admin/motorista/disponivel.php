
<?php
//Conexão com o banco de dados
include_once '../../config_db.php';
//Inicar Sessão
session_start();
ob_start();

//Mudar Disponibilidade do veiculo

if (isset($_GET['Disponivel']) && isset($_GET['id'])) {
    $UsuarioID = $_GET['id'];
    $Disponivel = $_GET['Disponivel'];// verifica o valor da Url(Não)

    if ($Disponivel == 'Não') {
        $DisponivelUsuario = 'Activo';//Disponivel = Activo/ indiponivel = Inactivo
    }else{
        $DisponivelUsuario = 'Inactivo';//Disponivel = Activo/ indiponivel = Inactivo
        }
        //Consulta para apagar registo de aluguer
        $sql="UPDATE `usuarios` SET `EstadoUsuario` = ? WHERE `usuarios`.`UsuarioID` = ?";
        //Preparar a consulta
        $preparar=mysqli_prepare($conexao,$sql);
        if ($preparar==false) {
            $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Erro na Preparação da Consulta!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
        }
        //VInvular os parametros
        mysqli_stmt_bind_param($preparar,"si",$DisponivelUsuario,$UsuarioID);
        //Exeucutar a preparação 
        if (mysqli_stmt_execute($preparar)) {
            //mensagem de sucesso
            $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
            Situação do Motorista Actualizada com Sucesso.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header('location:../motorista.php');
        }else {
            //mensagem de sucesso de erro
            $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
            Erro: Situação Não Actualizada.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header('../motorista.php');
        }
   
}
//Fechar a e consulta e a conexao
mysqli_stmt_close($preparar);
mysqli_close($conexao);