
<?php 
include_once '../../credencias/config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Verficar o metodo que trás os dados
if (isset($_POST['add_func'])) {
  $PrimeiroNome =  strip_tags($_POST['nome']);
  $sobreNome =  strip_tags($_POST['sobreNome']);
  $Nome= $PrimeiroNome ." ". $sobreNome; 
  $provincia =  strip_tags($_POST['provincia']);
  $municipio =  strip_tags($_POST['municipio']);
  $bairro =  strip_tags($_POST['bairro']);
  $telefone =  strip_tags($_POST['telefone']);
  //Dados do Documento
  $Documento =  strip_tags($_POST['documento']);
  $numDocumento=  strip_tags($_POST['numDocumento']);
  $DataValidade =  strip_tags($_POST['dataValidade']);
  $FileDoc =  'Copia do Bi';
  $SituacaoD =  1;
  //Dados Motorista
  $Permissao="Motorista";
  $SituacaoMotorista =  strip_tags($_POST['situacaoMotorista']);
  $Imagem= "Fotografia";

    if(empty($PrimeiroNome)){
      $_SESSION['msg_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o Nome!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      header("location:../motorista.php");
    }elseif(empty($sobreNome)){
        $_SESSION['msg_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
        Digite o Sobrenome!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
    }elseif(empty($Documento)){
        $_SESSION['msg_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Seleciona o Documento!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
    }elseif(empty($Permissao)){
        $_SESSION['msg_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Seleciona a Permissão de Acesso!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
    }elseif(empty($numDocumento)){
        $_SESSION['msg_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Insira o Número do Documento.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
    }elseif(empty($DataValidade)){
        $_SESSION['msg_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Insira a Data de validade!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
    }else{
        //verificar se existe um veiculo com este nome
        $sql="SELECT dm.NumDocumento FROM usuarios us inner join documentos dm on us.DocumentoID=dm.DocumentoID WHERE dm.NumDocumento =:numDocumento";
        $prepare_verificar_func = $conexao->prepare($sql);
        $prepare_verificar_func->bindParam(':numDocumento',$numDocumento, PDO::PARAM_STR);
        $prepare_verificar_func->execute();
        
        if($prepare_verificar_func->rowCount()){
        $_SESSION['msg_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Este Usuário Já existe!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../motorista.php");
        }else{
            
            //Consulta para inserir Documento
            $sql ="INSERT INTO `documentos` (`Documento`, `FileDoc`, `NumDocumento`, `dataValidade`, `SituacaoDoc`) VALUES (:documento,:fileDoc,:numDocumento,:dataValidade,:situacaoDoc)";
            //Preparar a consulta
            $preparar_inserir_doc = $conexao->prepare($sql);
            if ($preparar_inserir_doc ==false) {
                $_SESSION['msg_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                Erro na Preparação da Consulta!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../motorista.php");
            }
            
            //vincular os parametros
            $preparar_inserir_doc->bindParam(':documento',$Documento,PDO::PARAM_STR);
            $preparar_inserir_doc->bindParam(':fileDoc',$FileDoc,PDO::PARAM_STR);
            $preparar_inserir_doc->bindParam(':numDocumento',$numDocumento,PDO::PARAM_STR);
            $preparar_inserir_doc->bindParam(':dataValidade',$DataValidade,PDO::PARAM_STR);
            $preparar_inserir_doc->bindParam(':situacaoDoc',$SituacaoD,PDO::PARAM_INT);
            $preparar_inserir_doc->execute();
            //Executar a consulta
            if ($preparar_inserir_doc->rowCount()) {
                //Pega o id do documento para inserir em usuario
                $DocumentoID = $conexao->lastInsertId();
            //Cadastrar Usuario
            $sql ="INSERT INTO `usuarios` (`Nome`, `Telefone`, `Provincia`, `Municipio`, `Bairro`,`Permissao`, `DocumentoID`) VALUES (:nome, :telefone, :provincia, :municipio, :bairro,:permissao, :documentoID)";
            //Preparar a consulta
            $preparar_inserir_func = $conexao->prepare($sql);
            if ($preparar_inserir_func==false) {
                $_SESSION['msg_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                Erro na Preparação da Consulta!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../motorista.php");
            }
            //vincular os parametros
            $preparar_inserir_func->bindParam(':nome',$Nome,PDO::PARAM_STR);
            $preparar_inserir_func->bindParam(':provincia',$provincia, PDO::PARAM_STR);
            $preparar_inserir_func->bindParam(':municipio',$municipio, PDO::PARAM_STR);
            $preparar_inserir_func->bindParam(':bairro',$bairro, PDO::PARAM_STR);
            $preparar_inserir_func->bindParam(':telefone',$telefone, PDO::PARAM_STR);
            $preparar_inserir_func->bindParam(':documentoID',$DocumentoID, PDO::PARAM_INT);
            $preparar_inserir_func->bindParam(':permissao',$Permissao, PDO::PARAM_STR);
            $preparar_inserir_func->execute();
            //Executar a consulta
            if ($preparar_inserir_func->rowCount()) {
                $_SESSION['msg_motorista']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Funcionário Cadastrado com Sucesso.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../motorista.php");
                //Fim Cadastrar Usuario

                //Cadastrar Motorista
                $UsuarioID = $conexao->lastInsertId();
                $sql ="INSERT INTO `motoristas` (`Imagem`,`SituacaoMotorista`,`UsuarioID`) VALUES (:imagem,:situacaoMotorista, :usuarioID)";
                //Preparar a consulta
                $preparar_inserir_motorista = $conexao->prepare($sql);
                if ($preparar_inserir_motorista==false) {
                    $_SESSION['msg_motorista']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                    Erro na Preparação da Consulta!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    header("location:../motorista.php");
                }
                //vincular os parametros
                $preparar_inserir_motorista->bindParam(':imagem',$Imagem,PDO::PARAM_STR);
                $preparar_inserir_motorista->bindParam(':situacaoMotorista',$SituacaoMotorista, PDO::PARAM_STR);
                $preparar_inserir_motorista->bindParam(':usuarioID',$UsuarioID, PDO::PARAM_INT);
                $preparar_inserir_motorista->execute();
                //Executar a consulta
                if ($preparar_inserir_motorista->rowCount()) {
                    $_SESSION['msg_motorista']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        Motorista Cadastrado com Sucesso.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    header("location:../motorista.php");
                }else {
                    $_SESSION['msg_motorista']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Erro ao Cadastrar Motorista!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                    header("location:../motorista.php");
                }
                //FIm cadastrar motorista
            }else {
                $_SESSION['msg_motorista']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Erro ao Cadastrar Usuário!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../motorista.php");
            }
        }else {
            $_SESSION['msg_motorista']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Erro ao Cadastrar Documento!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header("location:../motorista.php");
        }
            
        } 
    }    
}

?>