
<?php 
include_once '../../config_db.php';//inclui a base de dados
session_start();//Sessão iniciada
ob_start();

//Verficar o metodo que trás os dados
if (isset($_POST['add'])) {
  $Modelo = mysqli_escape_string($conexao,$_POST['modelo']);
  $Ano = mysqli_escape_string($conexao,$_POST['ano']);
  $Placa = mysqli_escape_string($conexao,$_POST['placa']);
  $ValorDiario = mysqli_escape_string($conexao,$_POST['valorDiario']);
  $Descricao = mysqli_escape_string($conexao,$_POST['descricao']);
  $Disponivel = 1;
  $Imagem = $_FILES['imagem'];

  if(empty($Modelo)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Digite o Modelo do Veículo!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../veiculo.php");
  }elseif(empty($Ano)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Inisira o Ano do Veículo!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../veiculo.php");
  }elseif(empty($Placa)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Insira a Placa ou Matrícula do Veículo!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../veiculo.php");
  }elseif(empty($ValorDiario)){
      $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
      Inisira o Valor Diário do Veículo!
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    header("location:../veiculo.php");
  }else {
        //verificar se existe um veiculo com este nome
        $sql="SELECT CarroID,Imagem,Modelo,Ano,Placa,Disponivel,ValorDiaria FROM carros  ORDER BY Modelo";
        $query = mysqli_query($conexao,$sql);
        $dados=mysqli_fetch_array($query);
        $PlacaAnterior = $dados['Placa'];

        if($Placa == "$PlacaAnterior"){
            $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
            Este Veículo já está Cadastrado!
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
            header("location:../veiculo.php");
        }else{
            if (empty($Imagem)) {
                $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                Insira a Imagem do Veículo!
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
                header("location:../veiculo.php");
            }else {
                // Verifica se não houve erro durante o upload
                if ($Imagem['error'] === 0) {

                    // Verifica se o tamanho do arquivo
                    if($Imagem ['size'] > 200000) {
                    
                        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                        Imagem muito grande!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        header('location:../veiculo.php');
                    }
                    
                    // Move o arquivo para um diretório de destino
                    $caminhoDestino = '../../imagens/carros/' . $Imagem['name'];
                    move_uploaded_file($Imagem['tmp_name'], $caminhoDestino);
                    
                    // Verifica se o arquivo é uma imagem válida
                    $extensao = strtolower(pathinfo($caminhoDestino, PATHINFO_EXTENSION));
                    $tiposPermitidos = array('jpg', 'jpeg', 'png');
                    if (!in_array($extensao, $tiposPermitidos)) {
                        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                        Formato de arquivo inválido. Apenas imagens JPG, JPEG e PNG são permitidas!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        header('location: ../veiculo.php');
                    
                        // Verifica se o arquivo existe na pasta de destino
                        if (!file_exists($caminhoDestino)) {
                            header('location: ../veiculo.php');
                            $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                            Erro ao mover o arquivo para a pasta de destino!
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";

                        }
                    }
                        
                    // Salva as informações da imagem no banco de dados
                    $Imagem = $Imagem['name'];

                    //Consulta para inserir marcacao de Aluguer
                    $sql ="INSERT INTO `carros` (`Imagem`,`Modelo`, `Ano`, `Placa`,`Descricao`, `Disponivel`, `ValorDiaria`) VALUES (?,?,?,?,?,?)";
                    //Preparar a consulta
                    $preparar = mysqli_prepare($conexao,$sql);
                    if ($preparar==false) {
                        $_SESSION['msg']="<div class='alert alert-info alert-dismissible fade show' role='alert'>
                        Erro na Preparação da Consulta!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        header("location:../veiculo.php");
                    }
                    //vincular os parametros
                    mysqli_stmt_bind_param($preparar,"sssssid",$Imagem,$Modelo,$Ano,$Placa,$Descricao,$Disponivel,$ValorDiario);

                    //Executar a consulta
                    if (mysqli_stmt_execute($preparar)) {
                        $_SESSION['msg']="<div class='alert alert-success alert-dismissible fade show' role='alert'>
                        Veículo Cadastrado com Sucesso.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        header("location:../veiculo.php");
                    }else {
                        $_SESSION['msg']="<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Erro ao Cadastrar Veículo!
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                        header("location:../veiculo.php");
                    }
                }
            }
        } 
    }    
}

?>