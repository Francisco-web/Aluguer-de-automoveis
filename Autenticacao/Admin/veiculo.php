<?php
$pagina ="Veículos Cadastrados";
// ======= Head ======= -->
include_once 'head/head.php';

// ======= Header ======= -->
include_once 'header/header.php';

//-- ======= Sidebar ======= -->
include_once 'sidebar/sidebar.php';
 
//-- ======= main ======= -->
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
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                  <a class="btn btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal" href="#" ><i class="bi bi-plus"></i>Novo</a>
                </div>

                <div class="card-body">
                  <h5 class="card-title"> Consultar Veículos</h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>idVeiculo
imagem
marca
modelo
ano
cor
placa
lugares
portas
arCondicionado
combustivel
travoes
numCilindros
cilindrada
airBags
numVelocidade

                        <th scope="col">Imagem</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">Ano</th>
                        <th scope="col">Cor</th>
                        <th scope="col">Placa</th>
                        <th scope="col">Lugares</th>
                        <th scope="col">Acção</th>
                      </tr>
                    </thead>
                    <?php
                        $sql="SELECT marca,modelo,dataInicio,dataFim,statusPagamento,valorAluguel,ur.nome as motorista,us.nome as cliente FROM aluguer alu inner join veiculo vl on alu.idVeiculo = vl.idVeiculo join cliente cl on alu.idCliente = cl.idCliente join motorista mt on alu.idMotorista = mt.idMotorista join usuario us on cl.idUsuario = us.idUsuario join usuario ur on mt.idUsuario = ur.idUsuario  ORDER BY marca DESC";
                        $query = mysqli_query($conexao,$sql);
                        while ($dados=mysqli_fetch_array($query)) :
                            $veiculo = $dados['marca']."".$dados['modelo'];
                            $dataLevantamento = $dados['dataInicio'];
                            $dataDevolucao = $dados['dataFim'];
                            $valorAluguer = $dados['valorAluguel'];
                            $statusPagamento = $dados['statusPagamento'];
                            $Cliente = $dados['cliente'];
                            $motorista = $dados['motorista'];
                    ?>
                    <tbody>
                      <tr>
                        <th scope="row"><a href="#"><?php echo $veiculo;?></a></th>
                        <td><?php echo $dataLevantamento;?></td>
                        <td><a href="#" class="text-primary"><?php echo $dataDevolucao;?></a></td>
                        <td><?php echo $valorAluguer;?></td>
                        <td><span class="badge bg-success"><?php echo $statusPagamento;?></span></td>
                        <td><a href="#" class="text-primary"><?php echo $Cliente;?></a></td>
                        <td><?php echo $motorista;?></td>
                        <td> 
                            <span class="badge bg-secondary"><i class="bi bi-exclamation-octagon me-1"></i> Alterar</span> 
                            <span class="badge bg-danger"><i class="bi bi-exclamation-octagon me-1"></i> Apagar</span>
                        </td>
                       
                      </tr>
                    </tbody>
                    <?php endwhile;?>
                  </table>

                </div>

              </div>
            </div><!-- End Recent Sales -->
          </div>
        </div><!-- End Left side columns -->

      </div>
    </section>
    <!-- Modal Novo Aluguer -->
    <div class="modal fade" id="largeModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Adicionar Aluguer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Dados do Aluguer de Veículo</h5>

                <!-- No Labels Form -->
                <form class="row g-3" method="POST" action="aluguel/inserir.php">
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
                      <option value="<?php echo $idCliente;?>"><?php echo $nome;?></option>
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
                      <option value="<?php echo $idVeiculo;?>"><?php echo $marca." ".$modelo;?></option>
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
                      <option value="<?php echo $idMotorista;?>"><?php echo $nome;?></option>
                      <?php endwhile?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <input type="datetime-local" name="dataLevantamento" class="form-control" placeholder="Data de Levantamento">
                    *Data de Levantamento.
                  </div>
                  <div class="col-md-6">
                    <input type="datetime-local" name="dataDevolucao" class="form-control" placeholder="Data de Devolução">
                    *Data de Devolução.
                  </div>
                  <div class="col-6">
                    <input type="number" class="form-control" name="valorAluger" placeholder="Valor do Aluguer">
                  </div>
                  <div class="col-md-4">
                    <select id="inputState" name="statusPagamento" class="form-select">
                      <option value="">Status Pagamento</option>
                      <option>Pendente</option>
                      <option>Pago</option>
                    </select>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="add" class="btn btn-primary">Guardar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div><!-- End Extra Large Modal-->
</main><!-- End #main -->
 
<?php
//-- ======= Footer ======= -->
include_once 'footer/footer.php';
?>   