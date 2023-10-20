<?php
$pagina ="Aluguer de Veículos";
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

                <div class="card-body">
                  <h5 class="card-title"> Contratos de Aluguer</h5>

                  <table class="table table-bordered border-primary">
                    <thead>
                      <tr>
                        <th scope="col">Veículo</th>
                        <th scope="col">Local Levantamento</th>
                        <th scope="col">Data Levantamento</th>
                        <th scope="col">Local Devolução</th>
                        <th scope="col">Data Devolução</th>
                        <th scope="col">Valor Pago</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Motorista</th>
                        <th scope="col">Acção</th>
                      </tr>
                    </thead>
                    <?php
                      $sql="SELECT alu.AluguelID,Modelo,LocalRetirada,DataRetirada,LocalDevolucao,DataDevolucao,valorTotal,cl.Nome as cliente,mt.Nome as motorista FROM alugueis alu inner join carros cr on alu.CarroID = cr.CarroID join clientes cl on alu.ClienteID = cl.ClienteID join motoristas mt on alu.MotoristaID = alu.MotoristaID ORDER BY alu.AluguelID DESC";
                      $query = mysqli_query($conexao,$sql);
                      while ($dados=mysqli_fetch_array($query)) :
                          $veiculo = $dados['Modelo'];
                          $localRetirada = $dados['LocalRetirada'];
                          $dataRetirada = $dados['DataRetirada'];
                          $localDevolucao = $dados['LocalDevolucao'];
                          $dataDevolucao = $dados['DataDevolucao'];
                          $valorAluguer = $dados['valorTotal'];
                          $Cliente = $dados['cliente'];
                          $motorista = $dados['motorista'];
                          $idAluguer = $dados['AluguelID'];
                    ?>
                    <tbody>
                      <tr>
                        <th><a href="#"><?php echo $veiculo;?></a></th>
                        <td><?php echo $localRetirada;?></td>
                        <td class="text-primary"><?php echo $dataRetirada;?></td>
                        <td><?php echo $localDevolucao;?></td>
                        <td class="text-primary"><?php echo $dataDevolucao;?></a></td>
                         <td><?php echo $valorAluguer;?></td>
                        <td><?php echo $Cliente;?></td>
                        <td><?php echo $motorista;?></td>
                        <td> 
                          <div class="btn-group">
                            <a class="btn btn-secondary" href="edit_aluguer.php?id=<?php echo $idAluguer;?>"><i   class="bi-eye"></i></a>
                            <a class="btn btn-primary" href="edit_aluguer.php?id=<?php echo $idAluguer;?>"><i class="ri-edit-line"></i></a>
                            <a class="btn btn-danger" href="aluguel/deletar.php?id=<?php echo $idAluguer;?>" onclick="return confirm('Tens Certeza que quer Apagar Este Registo?')" ><i class="ri-delete-bin-5-line"></i><a>
                          </div>
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
</main><!-- End #main -->
 
<?php
//-- ======= Footer ======= -->
include_once 'footer/footer.php';
?>   