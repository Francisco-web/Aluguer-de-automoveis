<?php
require_once 'config_db.php';
session_start();
ob_start();

//var_dump($dados);
if (isset($_POST['entrar_login']) && $conexao != null) {
$SituacaoUsuario='Activo';
$email = $_POST['email'];
$senha =   $_POST['senha'];

$query_user = "SELECT * FROM usuarios WHERE Email =:email and Situacao=:situacaoUsuario LIMIT 1";

$result_user = $conexao->prepare($query_user);
$result_user->bindParam(':email', $email, PDO::PARAM_STR);
$result_user->bindParam(':situacaoUsuario', $SituacaoUsuario, PDO::PARAM_STR);
$result_user->execute();


    if (($result_user) AND ($result_user->rowCount() != 0)) {
        $row_user = $result_user->fetch(PDO::FETCH_ASSOC);
        $_SESSION['Permissao'] = $row_user['Permissao'];
        $_SESSION['UsuarioID'] = $row_user['UsuarioID'];
        $pass_db = $row_user['Senha'];
        //var_dump($type_user);
        if(!password_verify($senha, $pass_db)){
            $_SESSION['msg_login'] = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Email ou Senha Invalida!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>"; 
            header("location:../pages-login.php");
        }else{
            switch ($_SESSION['Permissao'] ) {
                case 'Administrador':
                    header("location:../Admin/index.php");
                    break;
                case 'Cliente':
                    header("location:../Cliente/index.php");
                    break;
                case 'Motorista':
                    header("location:../Motorista/index.php");
                    break;
                case 'Recepcionista':
                    header("location:../Recepcionista/index.php");
                    break;
                default:
                    $_SESSION['msg_login'] = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Email ou Senha Invalida!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>"; 
                    header("location:../pages-login.php");
                    break;
            }
        }
        
    }else {
        $_SESSION['msg_login'] = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
            Email ou Senha Invalida. Consulte o Admin!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>"; 
        header("location:../pages-login.php");
    }
}
?>