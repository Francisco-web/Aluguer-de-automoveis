
<?php 
include_once '../../config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Verficar o metodo que trás os dados
if (isset($_POST['add'])) {
  $Nome =  mysqli_escape_string($conexao,$_POST['nome']);
  $CartaConducao =  mysqli_escape_string($conexao,$_POST['cartaConducao']);
  $Telefone =  mysqli_escape_string($conexao,$_POST['telefone']);
  $Endereco =  mysqli_escape_string($conexao,$_POST['endereco']);
  $NumDocumento =  mysqli_escape_string($conexao,$_POST['numDocumento']);
  $Documento =  mysqli_escape_string($conexao,$_POST['documento']);

  //Dado de Usuario
  $email =  mysqli_escape_string($conexao,$_POST['email']);
  $senha =  123456; //Senha padrão par aos cliente
  $senha = password_hash($senha,PASSWORD_DEFAULT);
  $Permissao = 'Cliente';
  $EstadoUsuario = mysqli_escape_string($conexao,$_POST['situacao']);

    if(empty($Nome)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o seu Nome!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../cliente.php");
    }elseif(empty($CartaConducao)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira o Nº da Carta de Condução!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../cliente.php");
    }elseif(empty($Telefone)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira o Seu Número de Telefone!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../cliente.php");
    }elseif(empty($Endereco)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o seu Endereço
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../cliente.php");
    }elseif(empty($email)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o seu Endereço de Email!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../cliente.php");
    }elseif(empty($NumDocumento)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira o Número do Documento de identidade!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../cliente.php");
    }elseif(empty($Documento)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Seleciona o Documento de identidade!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../cliente.php");
    }else{
        //verificar se existe um veiculo com este nome
        $sql="SELECT cl.NumDocumento FROM clientes cl inner join usuarios u on cl.UsuarioID = u.UsuarioID ";
        $query = mysqli_query($conexao,$sql);
        $dados=mysqli_fetch_array($query);
        $NumDocumentoAnterior = $dados['NumDocumento'];

        if($NumDocumento == "$NumDocumentoAnterior"){
            $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Este Cliente já está Cadastrado!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header("location:../cliente.php");
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
                header("location:../cliente.php");
            }
            //vincular os parametros
            mysqli_stmt_bind_param($preparar,"ssss",$email,$senha,$Permissao,$EstadoUsuario);

            //Executar a consulta
            if (mysqli_stmt_execute($preparar)) {
                $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Usuário Cadastrado com Sucesso.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../cliente.php");
            }else {
                $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Erro ao Cadastrar Usuário!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../cliente.php");
            }
        
            //Obter ID de usuario
            $usuarioID = mysqli_insert_id($conexao);
            //Consulta para inserir Motorista
            $sql ="INSERT INTO `clientes` (`Nome`,`Telefone`, `Endereco`,`NumDocumento`,`Documento`, `CartaConducao`,`UsuarioID`) VALUES (?,?,?,?,?,?,?)";
            //Preparar a consulta
            $preparar = mysqli_prepare($conexao,$sql);
            if ($preparar==false) {
                $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                Erro na Preparação da Consulta!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../cliente.php");
            }
            //vincular os parametros
            mysqli_stmt_bind_param($preparar,"sissssi",$Nome,$Telefone,$Endereco,$NumDocumento,$Documento,$CartaConducao,$usuarioID);

            //Executar a consulta
            if (mysqli_stmt_execute($preparar)) {
                $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Cliente Cadastrado com Sucesso.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../cliente.php");
            }else {
                $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Erro ao Cadastrar Cliente!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../cliente.php");
            }
             
        } 
    }    
}
//Fechar a e consulta e a conexao
mysqli_stmt_close($preparar);
mysqli_close($conexao);
?>