
<?php 
include_once '../../config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Verficar o metodo que trás os dados
if (isset($_POST['add'])) {
  $Nome =  mysqli_escape_string($conexao,$_POST['nome']);
  $Permissao =  mysqli_escape_string($conexao,$_POST['permissao']);
  //Dado de Usuario
  $email =  mysqli_escape_string($conexao,$_POST['email']);
  $senha =  mysqli_escape_string($conexao,$_POST['senha']);
  $senha = password_hash($senha,PASSWORD_DEFAULT);
  $EstadoUsuario = 'Activo';

    if(empty($Nome)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o Nome!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../funcionario.php");
    }elseif(empty($email)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o Endereço de Email!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
    }elseif(empty($senha)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira a Senha!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
    }else{
        //verificar se existe um veiculo com este nome
        $sql="SELECT f.Nome FROM funcionarios f inner join usuarios u on f.UsuarioID = u.UsuarioID WHERE EstadoUsuario = 'Activo'";
        $query = mysqli_query($conexao,$sql);
        $dados=mysqli_fetch_array($query);
        $NomeAnterior = $dados['Nome'];

        if($Nome == "$NomeAnterior"){
            $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Este Funcionário já está Cadastrado!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header("location:../funcionario.php");
        }else{
             
            //Consulta para inserir marcacao de Aluguer
            $sql ="INSERT INTO `usuarios` (`Email`,`Senha`, `Permissao`, `EstadoUsuario`) VALUES (?,?,?,?)";
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
            mysqli_stmt_bind_param($preparar,"ssss",$email,$senha,$Permissao,$EstadoUsuario);

            //Executar a consulta
            if (mysqli_stmt_execute($preparar)) {
                $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Usuário Cadastrado com Sucesso.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../motorista.php");
            }else {
                $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Erro ao Cadastrar Usuário!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../motorista.php");
            }
        
            //Obter ID de usuario
            $usuarioID = mysqli_insert_id($conexao);
            //Consulta para inserir Motorista
            $sql ="INSERT INTO `funcionarios` (`Nome`,`UsuarioID`) VALUES (?,?)";
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
            mysqli_stmt_bind_param($preparar,"si",$Nome,$usuarioID);

            //Executar a consulta
            if (mysqli_stmt_execute($preparar)) {
                $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Funcionário Cadastrado com Sucesso.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../funcionario.php");
            }else {
                $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Erro ao Cadastrar Funcionário!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../funcionario.php");
            }
        } 
    }    
}
//Fechar a e consulta e a conexao
mysqli_stmt_close($preparar);
mysqli_close($conexao);
?>