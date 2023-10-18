<?php
$pagina ="Actualizar Dados de Aluguer de Veículos";
// ======= Head ======= -->
include_once 'head/head.php';

// ======= Header ======= -->
include_once 'header/header.php';

//-- ======= Sidebar ======= -->
include_once 'sidebar/sidebar.php';
 
//-- ======= Consulta ao anco de Dados ======= -->
if (isset($_GET['id'])) {
  $id = mysqli_escape_string($conexao,$_GET['id']);

$sql="SELECT alu.idAluguel,marca,modelo,dataInicio,dataFim,statusPagamento,valorAluguel,ur.nome as motorista,us.nome as cliente FROM aluguer alu inner join veiculo vl on alu.idVeiculo = vl.idVeiculo join cliente cl on alu.idCliente = cl.idCliente join motorista mt on alu.idMotorista = mt.idMotorista join usuario us on cl.idUsuario = us.idUsuario join usuario ur on mt.idUsuario = ur.idUsuario WHERE alu.idAluguel = '$id' ORDER BY idAluguel DESC";
$query = mysqli_query($conexao,$sql);
$dados=mysqli_fetch_array($query);
$veiculo = $dados['marca']."".$dados['modelo'];
$dataLevantamento = $dados['dataInicio'];
$dataDevolucao = $dados['dataFim'];
$valorAluguer = $dados['valorAluguel'];
$statusPagamento = $dados['statusPagamento'];
$Cliente = $dados['cliente'];
$motorista = $dados['motorista'];
$idAluguer = $dados['idAluguel'];
}
?> 

<!-- main -->
<main id="main" class="main">

    <div class="pagetitle">
      <h1><?php echo $pagina;?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item active"><?php echo $pagina;?></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <?php
    //SESSAO para mostrar mesagem
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
    <section class="section dashboard">
      <div class="row">
        
        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
            <!-- Recent Sales -->
            <div class="col-12">
              <!-- Fomrulario para Actualizar Registo de Aluguer -->
                <div class="modal-content">
                  <div class="modal-body">
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title"></h5>

                        <!-- No Labels Form -->
                        <form class="row g-3" method="POST" action="aluguel/alterar.php">
                          <input type="hidden" class="form-control" value="<?php echo $idAluguer;?>" name="idAluguer">
                          <div class="col-md-4">
                            <select id="inputState" name="idCliente" class="form-select">
                              <option value="">Cliente</option>
                              <?php 
                                $sql="SELECT idCliente,nome FROM cliente cl inner join usuario us on cl.idUsuario = us.idUsuario ORDER BY idCliente DESC";
                                $query = mysqli_query($conexao,$sql);
                                while ($dados=mysqli_fetch_array($query)):
                                    $idCliente = $dados['idCliente'];
                                    $nome = $dados['nome'];
                              ?>
                              <option value="<?php echo $idCliente;?>"
                              <?php 
                              //Selcecionar cliente correspondente ao dados do banco de dados
                              if($nome == $Cliente){echo"Selected";};?>
                              ><?php echo $nome;?></option>
                              <?php endwhile?>
                            </select>
                          </div>
                          <div class="col-md-4">
                            <select id="inputState" name="idVeiculo" class="form-select">
                              <option value="">Veículo</option>
                              <?php 
                                $sql="SELECT idVeiculo,marca,modelo FROM veiculo ORDER BY idVeiculo DESC";
                                $query = mysqli_query($conexao,$sql);
                                while ($dados=mysqli_fetch_array($query)):
                                    $idVeiculo = $dados['idVeiculo'];
                                    $marca = $dados['marca'];
                                    $modelo = $dados['modelo'];
                              ?>
                              <option value="<?php echo $idVeiculo;?>"
                              <?php 
                              //Selcecionar cliente correspondente ao dados do banco de dados
                                if($marca == $dados['marca'] && $modelo == $dados['modelo']){echo"Selected";}?>
                              ><?php echo $marca." ".$modelo;?></option>
                              <?php endwhile?>
                            </select>
                          </div>
                          <div class="col-md-4">
                            <select id="inputState" name="idMotorista" class="form-select">
                              <option selected="">Motorista</option>
                              <option value="Sem Motorista">Sem Motorista</option>
                              <?php 
                                $sql="SELECT idMotorista,nome FROM motorista mt inner join usuario us on mt.idUsuario = us.idUsuario ORDER BY idMotorista DESC";
                                $query = mysqli_query($conexao,$sql);
                                while ($dados=mysqli_fetch_array($query)):
                                    $idMotorista = $dados['idMotorista'];
                                    $nome = $dados['nome'];
                              ?>
                              <option value="<?php echo $idMotorista;?>"
                              <?php
                              //Selcecionar cliente correspondente ao dados do banco de dados
                                  if($nome == $motorista){echo"Selected";}
                              ?>
                              ><?php echo $nome;?></option>
                              <?php endwhile?>
                            </select>
                          </div>
                          <div class="col-md-6">
                            <input type="datetime-local" name="dataLevantamento" class="form-control" placeholder="Data de Levantamento" value="<?php echo $dataLevantamento;?>">
                            *Data de Levantamento.
                          </div>
                          <div class="col-md-6">
                            <input type="datetime-local" name="dataDevolucao" class="form-control" placeholder="Data de Devolução" value="<?php echo $dataDevolucao;?>">
                            *Data de Devolução.
                          </div>
                          <div class="col-6">
                            <input type="number" class="form-control" name="valorAluger" placeholder="Valor do Aluguer" value="<?php echo $valorAluguer;?>" readonly>
                            *Valor Pago
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="statusPagamento" placeholder="status Pagamento" value="<?php echo $statusPagamento;?>" readonly>
                            *Status Pagamento
                          </div>
                          <div class="text-center">
                            <button type="submit" name="cancelar" class="btn btn-secondary" >Cancelar</button>
                            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  
                </div>
                <!-- End Extra Large Modal-->
            </div><!-- End Recent Sales -->
          </div>
        </div><!-- End Left side columns -->

      </div>
    </section>
   
</main><!-- End #main -->
 
<?php
//-- ======= Footer ======= -->
include_once 'footer/footer.php';
?>   