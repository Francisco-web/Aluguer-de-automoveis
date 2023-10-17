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
                  <h5 class="card-title"> Contratos de Aluguer</h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">Veículo</th>
                        <th scope="col">Data Levantamento</th>
                        <th scope="col">Data Devolução</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Status Pagam.</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Motorista</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row"><a href="#">#2457</a></th>
                        <td>Brandon Jacob</td>
                        <td><a href="#" class="text-primary">At praesentium minu</a></td>
                        <td>$64</td>
                        <td><span class="badge bg-success">Approved</span></td>
                        <td><a href="#" class="text-primary">At praesentium minu</a></td>
                        <td>$64</td>
                      </tr>
                    </tbody>
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
                <form class="row g-3" method="POST" action="../aluguel/inserir.php">
                  <div class="col-md-4">
                    <select id="inputState" name="IdCliente" class="form-select">
                      <option selected="">Cliente</option>
                      <option>1</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <select id="inputState" name="idVeiculo" class="form-select">
                      <option selected="">Veículo</option>
                      <option>1</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <select id="inputState" name="idMotorista" class="form-select">
                      <option selected="">Motorista</option>
                      <option>Sem Motorista</option>
                      <option>1</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <input type="datetime-local" name="dataLevantamento" class="form-control" placeholder="Data de Levantamento">
                  </div>
                  <div class="col-md-6">
                    <input type="datetime-local" name="dataDevolucao" class="form-control" placeholder="Data de Devolução">
                  </div>
                  <div class="col-6">
                    <input type="text" class="form-control" name="valorAluger" placeholder="Valor do Aluguer">
                  </div>
                  <div class="col-md-4">
                    <select id="inputState" name="statusPagamento" class="form-select">
                      <option selected="">Status Pagamento</option>
                      <option>Pendente</option>
                      <option>Pago</option>
                    </select>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="add_aluguer" class="btn btn-primary">Guardar</button>
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