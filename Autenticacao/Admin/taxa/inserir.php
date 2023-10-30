
<?php 
include_once '../../config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Verficar o metodo que trás os dados
if (isset($_POST['add'])) {
  $Taxa = mysqli_escape_string($conexao,$_POST['taxa']);
  $Descricao = mysqli_escape_string($conexao,$_POST['descricao']);
  $ValorTaxa = mysqli_escape_string($conexao,$_POST['valorTaxa']);
  $EstadoTaxa = 'Actual';

  if(empty($Taxa)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Digite Nome da Taxa!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../taxa.php");
  }elseif(empty($Descricao)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Digite a Descrição da Taxa!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../taxa.php");
  }elseif(empty($ValorTaxa)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Insira o Valor da Taxa!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../taxa.php");
  }else {
        //verificar se existe um veiculo com este nome
        $sql="SELECT TaxaID,Nome,EstadoTaxa FROM taxas Where Nome = '$Taxa' and EstadoTaxa ='$EstadoTaxa'";
        $query = mysqli_query($conexao,$sql);
        $dados=mysqli_fetch_array($query);
        $TaxaAnterior = $dados['Nome'];

        if($Taxa == "$TaxaAnterior"){
            $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Esta Taxa já está Cadastrada!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header("location:../taxa.php");
        }else{
        
            //Consulta para inserir marcacao de Aluguer
            $sql ="INSERT INTO `taxas` (`Nome`,`Descricao`, `Valor`, `EstadoTaxa`) VALUES (?,?,?,?)";
            //Preparar a consulta
            $preparar = mysqli_prepare($conexao,$sql);
            if ($preparar==false) {
                $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                Erro na Preparação da Consulta!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../taxa.php");
            }
            //vincular os parametros
            mysqli_stmt_bind_param($preparar,"ssis",$Taxa,$Descricao,$ValorTaxa,$EstadoTaxa);

            //Executar a consulta
            if (mysqli_stmt_execute($preparar)) {
                $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Taxa Registrada com Sucesso.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../taxa.php");
            }else {
                $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                Erro ao Registrar Taxa!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../taxa.php");
            }
             
        } 
    }    
}
//Fechar a e consulta e a conexao
mysqli_stmt_close($preparar);
mysqli_close($conexao);
?>