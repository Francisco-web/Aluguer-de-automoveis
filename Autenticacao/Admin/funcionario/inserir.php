
<?php 
include_once '../../credencias/config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Verficar o metodo que trás os dados
if (isset($_POST['add_func'])) {
  $PrimeiroNome =  strip_tags($_POST['nome']);
  $Permissao =  strip_tags($_POST['permissao']);
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

    if(empty($PrimeiroNome)){
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
    }elseif(empty($Documento)){
        $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Seleciona o Documento!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
    }elseif(empty($Permissao)){
        $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Seleciona a Permissão de Acesso!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
    }elseif(empty($NumDoc)){
        $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Insira o Número do Documento.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
    }elseif(empty($DataValidade)){
        $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Insira a Data de validade!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
    }else{
        //verificar se existe um veiculo com este nome
        $sql="SELECT dm.NumDocumento FROM usuarios us inner join documentos dm on us.DocumentoID=dm.DocumentoID WHERE dm.NumDocumento =:numDocumento";
        $prepare_verificar_func = $conexao->prepare($sql);
        $prepare_verificar_func->bindParam(':numDocumento',$numDocumento, PDO::PARAM_STR);
        $prepare_verificar_func->execute();
        
        if($prepare_verificar_func->rowCount()){
        $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Este Usuário Já existe!
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        header("location:../funcionario.php");
        }else{
            
            //Consulta para inserir Documento
            $sql ="INSERT INTO `documentos` (`Documento`, `FileDoc`, `NumDocumento`, `dataValidade`, `SituacaoDoc`) VALUES (:documento,:fileDoc,:numDocumento,:dataValidade,:situacaoDoc)";
            //Preparar a consulta
            $preparar_inserir_doc = $conexao->prepare($sql);
            if ($preparar_inserir_doc ==false) {
                $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                Erro na Preparação da Consulta!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../funcionario.php");
            }
            
            //vincular os parametros
            $preparar_inserir_doc->bindParam(':documento',$Documento,PDO::PARAM_STR);
            $preparar_inserir_doc->bindParam(':fileDoc',$FileDoc,PDO::PARAM_STR);
            $preparar_inserir_doc->bindParam(':numDocumento',$NumDoc,PDO::PARAM_STR);
            $preparar_inserir_doc->bindParam(':dataValidade',$DataValidade,PDO::PARAM_STR);
            $preparar_inserir_doc->bindParam(':situacaoDoc',$SituacaoD,PDO::PARAM_INT);
            $preparar_inserir_doc->execute();
            //Executar a consulta
            if ($preparar_inserir_doc->rowCount()) {
                //Pega o id do documento para inserir em usuario
                $DocumentoID = $conexao->lastInsertId();
            //Consulta para inserir Usuario
            $sql ="INSERT INTO `usuarios` (`Nome`, `Telefone`, `Provincia`, `Municipio`, `Bairro`,`Permissao`, `DocumentoID`) VALUES (:nome, :telefone, :provincia, :municipio, :bairro,:permissao, :documentoID)";
            //Preparar a consulta
            $preparar_inserir_func = $conexao->prepare($sql);
            if ($preparar_inserir_func==false) {
                $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                Erro na Preparação da Consulta!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../funcionario.php");
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
                $_SESSION['msg_func']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Funcionário Cadastrado com Sucesso.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../funcionario.php");
            }
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

?>