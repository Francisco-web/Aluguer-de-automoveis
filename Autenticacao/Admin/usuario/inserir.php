
<?php 
include_once '../../credencias/config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Verficar o metodo que trás os dados
if (isset($_POST['add'])) {
  $usuarioID =  strip_tags($_POST['usuarioId']);
  $email =  strip_tags($_POST['email']);
  $senha =  strip_tags($_POST['senha']);
  $senha = password_hash($senha,PASSWORD_DEFAULT);
  $situacao = strip_tags($_POST['situacao']);
  $Permissao = strip_tags($_POST['permissao']);

    if(empty($usuarioID)){
      $_SESSION['msg_usuario']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira o ID ou B.I do usuário!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../usuario.php");
    }elseif(empty($email)){
        $_SESSION['msg_usuario']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o Endereço de Email!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../usuario.php");
    }elseif(empty($senha)){
        $_SESSION['msg_usuario']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira a Senha!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../usuario.php");
    }elseif(empty($situacao)){
        $_SESSION['msg_usuario']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Seleciona a Situação do Usuário!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../usuario.php");
    }else{
        //verificar se existe um veiculo com este nome
        $sql="SELECT email,Permissao,Senha FROM usuarios WHERE UsuarioID = $usuarioID";
        $prepare_con = $conexao->prepare($sql);
        $prepare_con->execute();
        $result = $prepare_con->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $dados) {
            $Email_db = $dados['email'];
            $Permissao_db = $dados['email'];
            $Senha_db = $dados['email'];
        }
        if (!$prepare_con->rowCount()) {
            $_SESSION['msg_usuario']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                Este Funcionário Não Existe!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header("location:../usuario.php");
        }elseif($Email_db != null || $Permissao_db != null || $senha_db !=null){
            $_SESSION['msg_usuario']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Este Usuário já Existe!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header("location:../usuario.php");
        }else{
             
            //Consulta para inserir marcacao de Aluguer
            $sql ="UPDATE `usuarios` SET `Email`=:email,`Senha`=:senha, `Permissao`=:permissao, `Situacao`=:situacao WHERE UsuarioID =:usuarioID";
            //Preparar a consulta
            $preparar = $conexao->prepare($sql);
            if ($preparar==false) {
                $_SESSION['msg_usuario']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                Erro na Preparação da Consulta! Consulte o Admin do Sistema. 
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../usuario.php");
            }
            //vincular os parametros
            $preparar->bindParam(":email",$email,PDO::PARAM_STR);
            $preparar->bindParam(":senha",$senha, PDO::PARAM_STR);
            $preparar->bindParam(":permissao",$Permissao,PDO::PARAM_STR);
            $preparar->bindParam(":situacao",$situacao, PDO::PARAM_STR);
            $preparar->bindParam(":usuarioID",$usuarioID, PDO::PARAM_INT);

            //Executar a consulta
            if ($preparar->execute()) {
                $_SESSION['msg_usuario']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Usuário Adicionado com Sucesso.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../usuario.php");
            }else {
                $_SESSION['msg_usuario']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Erro ao Adicionar Usuário!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../usuario.php");
            }
                            
        } 
    }    
}
//Fechar a e consulta e a conexao
$preparar->close();
$conexao->close();
?>