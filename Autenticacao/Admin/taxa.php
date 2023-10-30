<?php
$tituloPagina ="Taxa Registradas";
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
$qnt_result_pg = 9;
//calcular o inicio visualização
$inicio = ($qnt_result_pg * $pagina) - $qnt_result_pg;

?> 

<!-- main -->
<main id="main" class="main">

    <div class="pagetitle">
      <h1><?php echo $tituloPagina;?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item active"><?php echo $tituloPagina;?></li>
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
                  <h5 class="card-title"> Consultar Taxas</h5>
                    
                  <table class="table table-bordered border-primary">
                    <thead>
                      <tr>
                        <th scope="col">Taxa</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Acção</th>
                      </tr>
                    </thead>
                    <?php
                      //Pesquisar carro
                      if(!empty($_GET['pesquisar'])) {
                        $dados = $_GET['pesquisar'];
                        $sql="SELECT Nome,Descricao,Valor,EstadoTaxa FROM taxas WHERE Nome like '%$dados%' ORDER BY EstadoTaxa LIMIT $inicio, $qnt_result_pg";
                      }else {
                        $EstadoTaxa = 'Antigo';
                        $sql="SELECT * FROM taxas WHERE EstadoTaxa != '$EstadoTaxa' ORDER BY EstadoTaxa Desc LIMIT $inicio, $qnt_result_pg";
                      }
                      $query = mysqli_query($conexao,$sql);
                      while ($dados=mysqli_fetch_array($query)) :
                        $TaxaID = $dados['TaxaID'];  
                        $Taxa = $dados['Nome'];
                        $valorTaxa = $dados['Valor'];
                        $Descricao = $dados['Descricao'];
                        $EstadoTaxa = $dados['EstadoTaxa'];
                    ?>
                    <tbody>
                      <tr>
                        <td><a href="#" class="text-primary"><?php echo $Taxa;?></a></td>
                        <td><span class="badge bg-success"><?php echo number_format($valorTaxa,2,",",".");?></span></td>
                        <td><?php echo $EstadoTaxa == 'Actual' ? "<a class='btn btn-warning' disabled href='taxa/disponivel.php?Disponivel=Actual&id=$TaxaID'><i class='bi-undo'></i>Actual</a>":"<a class='btn btn-dark' href='taxa/disponivel.php?Disponivel=Antigo&id=$TaxaID'><i class='bi-undo'></i>Antigo</a>";?></td>
                        <td><?php echo $Descricao;?></td>
                        <td> 
                          <div class="btn-group">
                            <a class="btn btn-primary" href="edit_veiculo.php?id=<?php echo $TaxaID;?>"><i class="ri-edit-line"></i></a>
                            <a class="btn btn-danger" href="carro/deletar.php?id=<?php echo $TaxaID;?>" onclick="return confirm('Tens Certeza que quer Apagar Este Registo?')" ><i class="ri-delete-bin-5-line"></i><a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                    <?php endwhile;
                      //Somar todos os registros 
                      $EstadoCarro ="Apagado";
                      $result_pg="SELECT Count(TaxaID) as NumID FROM taxas";
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
                          <li class="page-item"><?PHP echo "<a class='page-link' href='taxa.php?pagina=1'>Anterior</a>"?></li>
                          <?php 
                              for($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++){
                              if($pag_ant >= 1){
                          ?>

                          <li class="page-item"><?PHP echo "<a class='page-link' href='taxa.php?pagina=$pag_ant'>$pag_ant</a>"?></li>
                          <?php	}
                          } ?>

                          <li class="page-item"><?PHP echo "<a class='page-link' href='taxa.php?pagina=$pagina'> $pagina</a>";?></li>

                          <?php 
                              for($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++){
                              if($pag_dep <= $quantidade_pg){ 
                          ?>		
                          <li class="page-item"><?PHP echo "<a class='page-link' href='taxa.php?pagina=$pag_dep'>$pag_dep</a>";?></li>
                          <?php	}
                          } ?>
                          <li class="page-item"><?PHP echo "<a class='page-link' href='taxa.php?pagina=$quantidade_pg'>Proximo</a>"?></li>
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
    <!-- Modal Novo Aluguer -->
    <div class="modal fade" id="largeModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Registrar Taxa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Dados da Taxa</h5>

                <!-- No Labels Form -->
                <form class="row g-3" method="POST" action="taxa/inserir.php" enctype="multipart/form-data">
                  <div class="col-md-6">
                    <input type="text" name="taxa" minlength="4" class="form-control" placeholder="Taxa" autocomplete="off" >
                  </div>
                  <div class="col-md-6">
                    <input type="number" name="valorTaxa" class="form-control" minlength="" placeholder="Valor" autocomplete="off" >
                  </div>
                  <div class="col-6">
                    <textarea name="descricao" id="" class="form-control" placeholder="Descrição" autocomplete="off"cols="30" rows="10"></textarea>
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

<script>
//Pesquisar com js
var pesquisar = document.getElementById('pesquisar');
pesquisar.addEventListener("Keydown", function(event){
if (event.Key === "Enter") {
    searchData();
}
});

function searchData(){
    window.location = 'veiculo.php?pesquisar='+pesquisar.value;
}
<?php
//-- ======= Footer ======= -->
include_once 'footer/footer.php';
?>   