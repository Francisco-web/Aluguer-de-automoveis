<?php
$TituloPagina ="Aluguer de Veículos";
// ======= Head ======= -->
include_once 'head/head.php';

// ======= Header ======= -->
include_once 'header/header.php';

//-- ======= Sidebar ======= -->
include_once 'sidebar/sidebar.php';
 
//-- ======= main ======= -->
//Receber o número da página
$pagina_atual = filter_input(INPUT_GET,'pagina', FILTER_SANITIZE_NUMBER_INT);		
$pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;
		
//Setar a quantidade de itens por pagina
$qnt_result_pg = 6;
//calcular o inicio visualização
$inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

?> 

<!-- main -->
<main id="main" class="main">

    <div class="pagetitle">
      <h1><?php echo $TituloPagina;?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item active"><?php echo $TituloPagina;?></li>
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
                    if(!empty($_GET['pesquisar'])) {
                      $dados = $_GET['pesquisar'];
                      $EstadoFuncionario="Apagado";
                      $sql="SELECT alu.AluguelID,Modelo,LocalRetirada,DataRetirada,LocalDevolucao,DataDevolucao,valorTotal,cl.Nome as cliente,mt.Nome as motorista FROM alugueis alu inner join carros cr on alu.CarroID = cr.CarroID join clientes cl on alu.ClienteID = cl.ClienteID join motoristas mt on alu.MotoristaID = alu.MotoristaID WHERE EstadoAluguel != '$EstadoFuncionario' and placa ='$dados' ORDER BY alu.AluguelID DESC LIMIT $inicio, $qnt_result_pg";
                    }else {
                      $EstadoFuncionario="Apagado";
                      $sql="SELECT alu.AluguelID,Modelo,LocalRetirada,DataRetirada,LocalDevolucao,DataDevolucao,valorTotal,cl.Nome as cliente,mt.Nome as motorista FROM alugueis alu inner join carros cr on alu.CarroID = cr.CarroID join clientes cl on alu.ClienteID = cl.ClienteID join motoristas mt on alu.MotoristaID = alu.MotoristaID WHERE EstadoAluguel != '$EstadoFuncionario' ORDER BY alu.AluguelID DESC LIMIT $inicio, $qnt_result_pg";
                    }
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
                            <a class="btn btn-secondary" href="../imprimir/cliente.php?id=<?php echo $idAluguer;?>"><i   class="bi-eye"></i></a>
                            <a class="btn btn-primary" href="edit_aluguer.php?id=<?php echo $idAluguer;?>"><i class="ri-edit-line"></i></a>
                            <a class="btn btn-danger" href="aluguel/deletar.php?id=<?php echo $idAluguer;?>" onclick="return confirm('Tens Certeza que quer Apagar Este Registo?')" ><i class="ri-delete-bin-5-line"></i><a>
                          </div>
                        </td>
                       
                      </tr>
                    </tbody>
                    <?php endwhile;
                      //Somar todos os registros 
                      $result_pg="SELECT Count(AluguelID) as NumID FROM alugueis ";
                      $resultado_pg = mysqli_query($conexao, $result_pg);
                      $row_pg = mysqli_fetch_assoc($resultado_pg);
                      //echo $row_pg['num_result'];
                      //Quantidade de pagina 
                      $quantidade_pg = ceil($row_pg['NumID'] / $qnt_result_pg);
                    ?>
                  </table>

                </div>
                <!--paginação start-->
                <section class="panel">
                  <div class="panel-body">
                      <nav aria-label="Page navigation example">
                        <ul class="pagination">
                          <?php	
                            //selecionador de página a visualizar
                            //Limitar os link antes depois
                            $max_links = 3; 
                          ?>
                          <li class="page-item"><?PHP echo "<a class='page-link' href='aluguer.php?pagina=1'>Anterior</a>"?></li>
                          <?php 
                              for($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++){
                              if($pag_ant >= 1){
                          ?>

                          <li class="page-item"><?PHP echo "<a class='page-link' href='aluguer.php?pagina=$pag_ant'>$pag_ant</a>"?></li>
                          <?php	}
                          } ?>

                          <li class="page-item"><?PHP echo "<a class='page-link' href='aluguer.php?pagina=$pagina'> $pagina</a>";?></li>

                          <?php 
                              for($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++){
                              if($pag_dep <= $quantidade_pg){ 
                          ?>		
                          <li class="page-item"><?PHP echo "<a class='page-link' href='aluguer.php?pagina=$pag_dep'>$pag_dep</a>";?></li>
                          <?php	}
                          } ?>
                          <li class="page-item"><?PHP echo "<a class='page-link' href='aluguer.php?pagina=$quantidade_pg'>Proximo</a>"?></li>
                        </ul>
                      </nav> 
                  </div>
                </section>
                <!--paginação end-->
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