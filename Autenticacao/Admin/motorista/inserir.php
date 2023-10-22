
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
  $Imagem = $_FILES['imagem'];

  //Dado de Usuario
  $email =  mysqli_escape_string($conexao,$_POST['email']);
  $senha =  mysqli_escape_string($conexao,$_POST['senha']);
  $senha = password_hash($senha,PASSWORD_DEFAULT);
  $Permissao = 'Motorista';
  $EstadoUsuario = 'Activo';

    if(empty($Nome)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o seu Nome!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../motorista.php");
    }elseif(empty($CartaConducao)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
         Insira o Nº da Carta de Condução!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
    }elseif(empty($Telefone)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Insira o Seu Número de Telefone!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
    }elseif(empty($Endereco)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o seu Endereço
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
    }elseif(empty($email)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o seu Endereço de Email!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
    }elseif(empty($senha)){
        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Inisira a Sua Senha!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
    }else{
        //verificar se existe um veiculo com este nome
        $sql="SELECT m.Nome FROM motoristas m inner join usuarios u on m.UsuarioID = u.UsuarioID WHERE EstadoUsuario = 'Activo'";
        $query = mysqli_query($conexao,$sql);
        $dados=mysqli_fetch_array($query);
        $NomeAnterior = $dados['Nome'];

        if($Nome == "$NomeAnterior"){
            $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Este Motorista já está Cadastrado!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header("location:../motorista.php");
        }else{
            if (empty($Imagem)) {
                $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                Insira a Imagem do Veículo!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../motorista.php");
            }else {
                // Verifica se não houve erro durante o upload
                if ($Imagem['error'] === 0) {

                    // Verifica se o tamanho do arquivo
                    if($Imagem ['size'] > 200000) {
                    
                        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                        Imagem muito grande!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        header('location:../motorista.php');
                    }
                    
                    // Move o arquivo para um diretório de destino
                    $caminhoDestino = '../../imagens/usuarios/' . $Imagem['name'];
                    move_uploaded_file($Imagem['tmp_name'], $caminhoDestino);
                    
                    // Verifica se o arquivo é uma imagem válida
                    $extensao = strtolower(pathinfo($caminhoDestino, PATHINFO_EXTENSION));
                    $tiposPermitidos = array('jpg', 'jpeg', 'png');
                    if (!in_array($extensao, $tiposPermitidos)) {
                        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                        Formato de arquivo inválido. Apenas imagens JPG, JPEG e PNG são permitidas!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        header('location: ../motorista.php');
                    
                        // Verifica se o arquivo existe na pasta de destino
                        if (!file_exists($caminhoDestino)) {
                            header('location: ../motorista.php');
                            $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                            Erro ao mover o arquivo para a pasta de destino!
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";

                        }
                    }
                        
                    // Salva as informações da imagem no banco de dados
                    $Imagem = $Imagem['name'];

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
                    $sql ="INSERT INTO `motoristas` (Imagem,`Nome`,`CartaConducao`, `Telefone`, `Endereco`,`UsuarioID`) VALUES (?,?,?,?,?,?)";
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
                    mysqli_stmt_bind_param($preparar,"sssisi",$Imagem,$Nome,$CartaConducao,$Telefone,$Endereco,$usuarioID);

                    //Executar a consulta
                    if (mysqli_stmt_execute($preparar)) {
                        $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        Motorista Cadastrado com Sucesso.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        header("location:../motorista.php");
                    }else {
                        $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Erro ao Cadastrar Motorista!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        header("location:../motorista.php");
                    }
                }
            }
        } 
    }    
}
//Fechar a e consulta e a conexao
mysqli_stmt_close($preparar);
mysqli_close($conexao);
?>