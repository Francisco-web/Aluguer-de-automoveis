
<?php 
include_once '../../credencias/config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Verficar o metodo que trás os dados
if (isset($_POST['add'])) {
  $Nome =  strip_tags($_POST['nome']);
  $sobreNome =  strip_tags($_POST['sobreNome']);
  $provincia =  strip_tags($_POST['provincia']);
  $municipio =  strip_tags($_POST['municipio']);
  $bairro =  strip_tags($_POST['bairro']);
  $telefone =  strip_tags($_POST['telefone']);
  $email =  strip_tags($_POST['email']);
  $senha =  strip_tags($_POST['senha']);
  $senha = password_hash($senha,PASSWORD_DEFAULT);
  $situacao = strip_tags($_POST['situacao']);

    if(empty($Nome)){
      $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o Nome!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../funcionario.php");
    }elseif(empty($sobreNome)){
        $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o Sobrenome!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
    }elseif(empty($email)){
        $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o Endereço de Email!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
    }elseif(empty($senha)){
        $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira a Senha!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
    }elseif(empty($situacao)){
        $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Seleciona a Situação do Usuário!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
    }else{
        //verificar se existe um veiculo com este nome
        $sql="SELECT email FROM usuarios";
        $prepare_con = $conexao->prepare($sql);
        $prepare_con->execute();
        $result = $prepare_con->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $dados) {
            $EmailAnterior = $dados['email'];
        }
        
        if($Email == "$EmailAnterior"){
            $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Este Email Já existe!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header("location:../funcionario.php");
        }else{
             
            //Consulta para inserir marcacao de Aluguer
            $sql ="INSERT INTO `usuarios` (`Email`,`Senha`, `Permissao`, `EstadoUsuario`) VALUES (:email,:senha,:permissao,:situacao)";
            //Preparar a consulta
            $preparar = $conexao->prepare($sql);
            if ($preparar==false) {
                $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                Erro na Preparação da Consulta!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../motorista.php");
            }
            //vincular os parametros
            $preparar->bindParam(":email",$email,PDO::PARAM_STR);
            $preparar->bindParam(":senha",$senha, PDO::PARAM_STR);
            $preparar->bindParam(":permissao",$Permissao,PDO::PARAM_STR);
            $preparar->bindParam(":situacao",$situacao, PDO::PARAM_STR);

            //Executar a consulta
            if ($preparar->execute()) {
                $_SESSION['msg_func']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Usuário Cadastrado com Sucesso.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../funcionario.php");
            }else {
                $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Erro ao Cadastrar Usuário!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../motorista.php");
            }
            //Cadastrar Funcionario
            if($Permissao =="Recepcionista"){
                //Obter ID de usuario
                $usuarioID = $conexao->lastInsertId();
                //Consulta para inserir Motorista
                $sql ="INSERT INTO `funcionarios` (`Nome`,`UsuarioID`) VALUES (:nome,:usuarioID)";
                //Preparar a consulta
                $preparar = $conexao->prepare($sql);
                if ($preparar==false) {
                    $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                    Erro na Preparação da Consulta!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    header("location:../funcionario.php");
                }
                //vincular os parametros
                $preparar->bindParam(":nome",$Nome, PDO::PARAM_STR);
                $preparar->bindParam(":usuarioID",$usuarioID, PDO::PARAM_INT);

                //Executar a consulta
                if ($preparar->execute()) {
                    $_SESSION['msg_func']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Funcionário Cadastrado com Sucesso.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    header("location:../funcionario.php");
                }else {
                    $_SESSION['msg_func']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Erro ao Cadastrar Funcionário!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    header("location:../funcionario.php");
                }
            }else{
                //Cadastrar Administrador
                //Obter ID de usuario
                $usuarioID = $conexao->lastInsertId();
                //Consulta para inserir Motorista
                $sql ="INSERT INTO `Administradores` (`Nome`,`UsuarioID`) VALUES (:nome,:usuarioID)";
                //Preparar a consulta
                $preparar = $conexao->prepare($sql);
                if ($preparar==false) {
                    $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                    Erro na Preparação da Consulta!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    header("location:../funcionario.php");
                }
                //vincular os parametros
                $preparar->bindParam(":nome",$Nome, PDO::PARAM_STR);
                $preparar->bindParam(":usuarioID",$usuarioID, PDO::PARAM_INT);

                //Executar a consulta
                if ($preparar->execute()) {
                    $_SESSION['msg_func']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Funcionário Cadastrado com Sucesso.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    header("location:../funcionario.php");
                }else {
                    $_SESSION['msg_func']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Erro ao Cadastrar Funcionário!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    header("location:../funcionario.php");
                }
            }
            
        } 
    }    
}
//Fechar a e consulta e a conexao
$preparar->close();
$conexao->close();
?>