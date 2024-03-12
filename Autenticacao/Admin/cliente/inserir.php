
<?php 
include_once '../../credencias/config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Verficar o metodo que trás os dados
if (isset($_POST['add'])) {
  $Nome =  strip_tags($_POST['nome']);
  $Telefone =  strip_tags($_POST['telefone']);
  $Provincia = strip_tags($_POST['provincia']);
  $Municipio = strip_tags($_POST['municipio']);
  $Bairro = strip_tags($_POST['bairro']);
  $Email = strip_tags($_POST['email']);
  $Permissao = "Cliente";
  //Dados de Documento
  $NumDocumento = strip_tags($_POST['numDocumento']);
  $DataValidade = strip_tags($_POST['dataValidade']);
  $SituacaoD = 1;
  $FileDoc = "Copia do BI";
  $Documento = strip_tags($_POST['documento']);

    if(empty($Nome)){
      $_SESSION['msg_cliente']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o seu Nome!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../cliente.php");
    }elseif(empty($Telefone)){
        $_SESSION['msg_cliente']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Insira o Seu Número de Telefone!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../cliente.php");
    }elseif(empty($Email)){
        $_SESSION['msg_cliente']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Digite o Email!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../cliente.php");
    }elseif(empty($NumDocumento)){
        $_SESSION['msg_cliente']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Insira o Número do Documento de identidade!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../cliente.php");
    }elseif(empty($Documento)){
        $_SESSION['msg_cliente']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Seleciona o Documento de identidade!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../cliente.php");
    }else{
        //verificar se existe um veiculo com este nome
        $sql="SELECT NumDocumento,email FROM clientes cl inner join usuarios u on  cl.UsuarioID = u.UsuarioID join documentos d on u.DocumentoID = d.DocumentoID ";
        $preparar_verificar_cliente= $conexao->prepare($sql);
        $preparar_verificar_cliente->execute();
        $resultado= $preparar_verificar_cliente->fetchAll(PDO::FETCH_ASSOC);
        foreach($resultado as $dados){
            $NumDocumento_db = $dados['NumDocumento'];
            $Email_db = $dados['email'];
        }

        if($NumDocumento == $NumDocumento_db){
            $_SESSION['msg_cliente']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                Este Cliente já Existe!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header("location:../cliente.php");
        }elseif($Email == $Email_db){
            $_SESSION['msg_cliente']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                Este Email já Existe!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header("location:../cliente.php");
        }else{
            //Consulta para inserir Documento
            $sql ="INSERT INTO `documentos` (`Documento`, `FileDoc`, `NumDocumento`, `dataValidade`, `SituacaoDoc`) VALUES (:documento,:fileDoc,:numDocumento,:dataValidade,:situacaoDoc)";
            //Preparar a consulta
            $preparar_inserir_doc = $conexao->prepare($sql);
            if ($preparar_inserir_doc ==false) {
                $_SESSION['msg_cliente']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                    Erro na Preparação da Consulta! Consulte o Admin do Sistema.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../cliente.php");
            }
            
            //vincular os parametros
            $preparar_inserir_doc->bindParam(':documento',$Documento,PDO::PARAM_STR);
            $preparar_inserir_doc->bindParam(':fileDoc',$FileDoc,PDO::PARAM_STR);
            $preparar_inserir_doc->bindParam(':numDocumento',$NumDocumento,PDO::PARAM_STR);
            $preparar_inserir_doc->bindParam(':dataValidade',$DataValidade,PDO::PARAM_STR);
            $preparar_inserir_doc->bindParam(':situacaoDoc',$SituacaoD,PDO::PARAM_INT);
            $preparar_inserir_doc->execute();
            //Executar a consulta
            if ($preparar_inserir_doc->rowCount()) {
                //Pega o id do documento para inserir em usuario
                $DocumentoID = $conexao->lastInsertId();
                //Consulta para inserir usuario
                $sql ="INSERT INTO `usuarios` (`Nome`, `Email`, `Telefone`, `Provincia`, `Municipio`, `Bairro`,`Permissao`, `DocumentoID`) VALUES (:nome,:email, :telefone, :provincia, :municipio, :bairro,:permissao, :documentoID)";
                //Preparar a consulta
                $preparar_inserir_cliente_usuario = $conexao->prepare($sql);
                if ($preparar_inserir_cliente_usuario==false) {
                    $_SESSION['msg_cliente']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                        Erro na Preparação da Consulta! Consulte o Admin do Sistema. 
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    header("location:../cliente.php");
                }
                //vincular os parametros
                $preparar_inserir_cliente_usuario->bindParam(':nome',$Nome,PDO::PARAM_STR);
                $preparar_inserir_cliente_usuario->bindParam(':telefone',$Telefone,PDO::PARAM_STR);
                $preparar_inserir_cliente_usuario->bindParam(':provincia',$Provincia,PDO::PARAM_STR);
                $preparar_inserir_cliente_usuario->bindParam(':municipio',$Municipio,PDO::PARAM_STR);
                $preparar_inserir_cliente_usuario->bindParam(':email',$Email,PDO::PARAM_STR);
                $preparar_inserir_cliente_usuario->bindParam(':bairro',$Bairro,PDO::PARAM_STR);
                $preparar_inserir_cliente_usuario->bindParam(':documentoID',$DocumentoID,PDO::PARAM_INT);
                $preparar_inserir_cliente_usuario->bindParam(':permissao',$Permissao,PDO::PARAM_STR);

                //Executar a consulta
                if ($preparar_inserir_cliente_usuario->execute()) {
                    
                    $UsuarioID = $conexao->lastInsertId();
                    //Adicionar dados na tabela Cliente
                    $sql ="INSERT INTO `clientes` (`UsuarioID`) VALUES (:usuarioID)";
                    //Preparar a consulta
                    $preparar_inserir_cliente = $conexao->prepare($sql);
                    if ($preparar_inserir_cliente==false) {
                        $_SESSION['msg_cliente']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                            Erro na Preparação da Consulta! Consulte o Admin do Sistema.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        header("location:../cliente.php");
                    }
                    //vincular os parametros
                    $preparar_inserir_cliente->bindParam(':usuarioID',$UsuarioID,PDO::PARAM_INT);

                    //Executar a consulta
                    if ($preparar_inserir_cliente->execute()) {
                        $_SESSION['msg_cliente']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            Cliente Cadastrado com Sucesso.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        header("location:../cliente.php");
                    }else {
                        $_SESSION['msg_cliente']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Erro ao Cadastrar Cliente!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        header("location:../cliente.php");
                    } 
                }else {
                    $_SESSION['msg_cliente']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Erro ao Cadastrar Cliente!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    header("location:../cliente.php");
                }    
                    
            }else {
                $_SESSION['msg_cliente']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Erro ao Cadastrar Cliente!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../cliente.php");
            }
        
        } 
    }    
}
//Fechar a e consulta e a conexao
$preparar_inserir_cliente->close();
$preparar_inserir_cliente->close();
?>