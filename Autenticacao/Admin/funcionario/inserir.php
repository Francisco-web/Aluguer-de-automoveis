
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
  //Dados do Documento
  $Documento =  strip_tags($_POST['documento']);
  $NumDoc=  strip_tags($_POST['numDoc']);
  $DataValidade =  $_POST['dataValidade'];
  $FileDoc =  'dcfsdc';
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
    }elseif(empty($Documento)){
        $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Seleciona o Documento!
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
        $sql="SELECT dm.NumDocumento FROM usuarios us inner join documentos dm on us.DocumentoID=dm.DocumentoID";
        $prepare_con = $conexao->prepare($sql);
        $prepare_con->execute();
        $result = $prepare_con->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $dados) {
            $numDoc_db = $dados['NumDocumento'];
        }
        
        if($NumDoc == "$numDoc_db"){
            $_SESSION['msg_func']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Este Usuário Já existe!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header("location:../funcionario.php");
        }else{

            //Consulta para inserir Usuario
            $sql ="INSERT INTO `documentos` (`DocumentoID`, `Documento`, `FileDoc`, `NumDocumento`, `dataValidade`, `SituacaoDoc`) VALUES (NULL, '', NULL, '', '', NULL)";
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
            $preparar->bindParam(":documento",$Documento,PDO::PARAM_STR);
            $preparar->bindParam(":numDocumento",$NumDoc, PDO::PARAM_STR);
            $preparar->bindParam(":dataValidade",$DataValidade, PDO::PARAM_STR);
            $preparar->bindParam(":fileDoc",$NumDoc, PDO::PARAM_STR);
            $preparar->bindParam(":dataValidade",$DataValidade, PDO::PARAM_STR);

            //Executar a consulta
            if ($preparar->execute()) {
                $_SESSION['msg_func']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Documento Cadastrado com Sucesso.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../funcionario.php");
            }else {
                $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Erro ao Cadastrar Documento!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../funcionario.php");
            }

            $DocumentoID = $conexao->lastInsertId();
            //Consulta para inserir Usuario
            $sql ="INSERT INTO `usuarios` (`Nome`, `Telefone`, `Provincia`, `Municipio`, `Bairro`, `DocumentoID`) VALUES (':nome :sobrenome', ':telefone', ':provincia', ':municipio', ':bairro', ':documentoID')";
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
            $preparar->bindParam(":nome",$Nome,PDO::PARAM_STR);
            $preparar->bindParam(":sobrenome",$sobreNome, PDO::PARAM_STR);
            $preparar->bindParam(":provincia",$provincia, PDO::PARAM_STR);
            $preparar->bindParam(":municipio",$municipio, PDO::PARAM_STR);
            $preparar->bindParam(":bairro",$bairro, PDO::PARAM_STR);
            $preparar->bindParam(":telefone",$telefone, PDO::PARAM_STR);
            $preparar->bindParam(":documentoID",$DocumentoID, PDO::PARAM_STR);

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
                header("location:../funcionario.php");
            }
            
        } 
    }    
}
//Fechar a e consulta e a conexao
$preparar->close();
$conexao->close();
?>