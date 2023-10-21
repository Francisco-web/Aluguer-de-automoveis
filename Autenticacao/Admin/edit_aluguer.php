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
  //Consulta no banco para listar os itens do aluguer
  $sql="SELECT alu.AluguelID,Modelo,LocalRetirada,DataRetirada,LocalDevolucao,DataDevolucao,valorTotal,EstadoAluguel,cl.Nome as cliente,mt.Nome as motorista FROM alugueis alu inner join carros cr on alu.CarroID = cr.CarroID join clientes cl on alu.ClienteID = cl.ClienteID join motoristas mt on alu.MotoristaID = alu.MotoristaID ORDER BY alu.AluguelID DESC";
  $query = mysqli_query($conexao,$sql);
  $dados=mysqli_fetch_array($query);
  $veiculo = $dados['Modelo'];
  $localRetirada = $dados['LocalRetirada'];
  $dataRetirada = $dados['DataRetirada'];
  $localDevolucao = $dados['LocalDevolucao'];
  $dataDevolucao = $dados['DataDevolucao'];
  $valorAluguer = $dados['valorTotal'];
  $EstadoAluguel = $dados['EstadoAluguel'];
  $Cliente = $dados['cliente'];
  $motorista = $dados['motorista'];
  $idAluguer = $dados['AluguelID'];
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
                                $sql="SELECT ClienteID,Nome FROM clientes cl ORDER BY Nome DESC";
                                $query = mysqli_query($conexao,$sql);
                                while ($dados=mysqli_fetch_array($query)):
                                    $idCliente = $dados['ClienteID'];
                                    $nome = $dados['Nome'];
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
                                $sql="SELECT CarroID,Modelo FROM carros ORDER BY Modelo DESC";
                                $query = mysqli_query($conexao,$sql);
                                while ($dados=mysqli_fetch_array($query)):
                                    $idVeiculo = $dados['CarroID'];
                                    $modelo = $dados['Modelo'];
                              ?>
                              <option value="<?php echo $idVeiculo;?>"
                              <?php 
                              //Selcecionar modelo correspondente ao dados do banco de dados
                                if($modelo == $veiculo){echo"Selected";}?>
                              ><?php echo $modelo;?></option>
                              <?php endwhile?>
                            </select>
                          </div>
                          <div class="col-md-4">
                            <select id="inputState" name="idMotorista" class="form-select">
                              <option selected="">Motorista</option>
                              <option value="Sem Motorista">Sem Motorista</option>
                              <?php 
                                $sql="SELECT MotoristaID,Nome FROM motoristas ORDER BY Nome DESC";
                                $query = mysqli_query($conexao,$sql);
                                while ($dados=mysqli_fetch_array($query)):
                                    $idMotorista = $dados['MotoristaID'];
                                    $nome = $dados['Nome'];
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
                            <input type="text" name="dataLevantamento" class="form-control" placeholder="Data de Levantamento" value="<?php echo $localRetirada?>">
                            *Local de Levantamento.
                          </div>
                          <div class="col-md-6">
                            <input type="datetime-local" name="dataLevantamento" class="form-control" placeholder="Data de Levantamento" value="<?php echo $dataRetirada?>">
                            *Data de Levantamento.
                          </div>
                          <div class="col-md-6">
                            <input type="datetime-local" name="dataDevolucao" class="form-control" placeholder="Data de Devolução" value="<?php echo $localDevolucao;?>">
                            *Local de Devolução.
                          </div>
                          <div class="col-md-6">
                            <input type="datetime-local" name="dataDevolucao" class="form-control" placeholder="Data de Devolução" value="<?php echo $dataDevolucao;?>">
                            *Data de Devolução.
                          </div>
                          
                          <div class="col-md-4">
                            <select id="inputState" name="idVeiculo" class="form-select">
                              <option value="">Situação</option>
                              <?php 
                                $sql="SELECT EstadoAluguel FROM alugueis ORDER BY EstadoALuguel ";
                                $query = mysqli_query($conexao,$sql);
                                $dados=mysqli_fetch_array($query);
                                  $NovoEstado = $dados['EstadoAluguel'];
                              ?>
                             <option value="Activo"<?php if ($NovoEstado=='Activo') {
                                    echo "Selected";
                                } ?>>Activo
                              </option>

                              <option <?php if ($NovoEstado=='Inactivo') {
                                echo "Selected";
                                } ?>>Inactivo
                              </option>
                            </select>
                          </div>
                          <p><strong>Dados de Pagamentos</strong></p>
                          <div class="col-6">
                            <input type="number" class="form-control" name="valorAluger" placeholder="Valor do Aluguer" value="<?php echo $valorAluguer;?>" readonly>
                            *Valor Pago
                          </div>
                          <div class="col-md-4">
                            <select id="inputState" name="taxa" class="form-select">
                              <option selected="">Taxas</option>
                              <?php 
                                $sql="SELECT TaxaID,Nome FROM taxas ORDER BY Nome DESC";
                                $query = mysqli_query($conexao,$sql);
                                while ($dados=mysqli_fetch_array($query)):
                                    $idTaxa = $dados['TaxaID'];
                                    $nome = $dados['Nome'];
                              ?>
                              <option value="<?php echo $idTaxa;?>"
                              
                              ><?php echo $nome;?></option>
                              <?php endwhile?>
                            </select>
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