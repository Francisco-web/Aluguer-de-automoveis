
<?php
//Conexão com o banco de dados
include_once '../../credencias/config_db.php';
//Inicar Sessão
session_start();
ob_start();

//Mudar Situacao do Usuario
if (isset($_GET['Disponivel']) && isset($_GET['id'])) {

    $UsuarioID = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    $sql="SELECT Situacao,Permissao FROM usuarios WHERE UsuarioID=:usuarioID";
    $prepare_verificacao= $conexao->prepare($sql);
    $prepare_verificacao->bindParam(':usuarioID',$UsuarioID,PDO::PARAM_INT);
    $prepare_verificacao->execute();
    $resultado= $prepare_verificacao->fetchAll(PDO::FETCH_ASSOC);
    foreach ($resultado as $dados) {
      $Situacao_db = $dados['Situacao'];
      $Permissao_db = $dados['Permissao'];
    }

    if ($Situacao_db == 'Inactivo') {
        $Situacao = 'Activo';//Disponivel = Activo/ indiponivel = Inactivo
    }else{
        $Situacao = 'Inactivo';//Disponivel = Activo/ indiponivel = Inactivo
    }
    if ($Permissao_db == "Administrador") {
        $_SESSION['msg_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Não Pode Alterar a Situação do Administrador.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
    }else {
        
        //Actualizar Estado do Usuário
        $sql="UPDATE `usuarios` SET `Situacao` =:situacao WHERE `usuarios`.`UsuarioID` =:usuarioID";
        //Preparar a consulta
        $preparar=$conexao->prepare($sql);
        if ($preparar==false) {
            $_SESSION['msg_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Erro na Preparação da Consulta, Consulte o Admin do Sistema.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header("location:../motorista.php");
        }
        //VInvular os parametros
        $preparar->bindParam(':situacao',$Situacao,PDO::PARAM_STR);
        $preparar->bindParam(':usuarioID',$UsuarioID,PDO::PARAM_INT);
        //Exeucutar a preparação 
        if ($preparar->execute()) {
            //mensagem de sucesso
            $_SESSION['msg_motorista']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
            Situação do Usuário Actualizada com Sucesso.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header('location:../motorista.php');
        }else {
            //mensagem de sucesso de erro
            $_SESSION['msg_motorista']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Erro ao Actualizar a Situação do Usuário.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header('../motorista.php');
        }
    }
}
//Mudar SItuacao do motorista
if (isset($_GET['Situacao']) && isset($_GET['id'])) {

    $SituacaoMotorista = strip_tags($_GET['Situacao']);
    $UsuarioID= strip_tags($_GET['id']);

    if ($SituacaoMotorista=="Indisponível") {
        $SituacaoMotorista_Actualizada="Disponível";
    }else{
        $SituacaoMotorista_Actualizada="Indisponível";
    }

    //Actualizar Estado do Motorista
    $sql ="UPDATE `motoristas` SET `SituacaoMotorista`=:situacaoMotorista WHERE UsuarioID =:usuarioID";
    //Preparar a consulta
    $preparar_atualizar_estado_motorista=$conexao->prepare($sql);
    if ($preparar_atualizar_estado_motorista==false) {
        $_SESSION['msg_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Erro na Preparação da Consulta, Consulte o Admin do Sistema.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
    }
    //VInvular os parametros
    $preparar_atualizar_estado_motorista->bindParam(':situacaoMotorista',$SituacaoMotorista_Actualizada, PDO::PARAM_STR);
    $preparar_atualizar_estado_motorista->bindParam(":usuarioID",$UsuarioID,PDO::PARAM_INT);
    //Exeucutar a preparação 
    if ($preparar_atualizar_estado_motorista->execute()) {
        //mensagem de sucesso
        $_SESSION['msg_motorista']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
        Situação do Motorista Actualizada com Sucesso.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header('location:../motorista.php');
    }else {
        //mensagem de sucesso de erro
        $_SESSION['msg_motorista']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
            Erro ao Actualizar a Situação do Motorista.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header('../motorista.php');
    }

}
//Fechar a e consulta e a conexao
$preparar->close();
$conexao->close();