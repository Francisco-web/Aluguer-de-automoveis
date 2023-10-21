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
                    
                  <table class="table table-bordered border-primary">
                    <thead>
                      <tr>
                        <th scope="col">Imagem</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">Ano</th>
                        <th scope="col">Placa</th>
                        <th scope="col">Disponivel</th>
                        <th scope="col">Valor Diário</th>
                        <th scope="col">Acção</th>
                      </tr>
                    </thead>
                    <?php
                        $sql="SELECT CarroID,Imagem,Modelo,Ano,Placa,Disponivel,ValorDiaria,MotorSeguranca,Lugar,Porta,Conforto,Bagageira FROM carros WHERE estadoCarro !='Apagado' ORDER BY Modelo";
                        $query = mysqli_query($conexao,$sql);
                        while ($dados=mysqli_fetch_array($query)) :
                          $CarroID = $dados['CarroID'];  
                          $Imagem = $dados['Imagem'];
                          $Modelo = $dados['Modelo'];
                          $Ano = $dados['Ano'];
                          $Placa = $dados['Placa'];
                          $Diponivel = $dados['Disponivel'];
                          $ValorDiaria = $dados['ValorDiaria'];
                          $Lugar = $dados['Lugar'];
                          $Bagageira = $dados['Bagageira'];
                          $Conforto = $dados['Conforto'];
                          $Porta = $dados['Porta'];
                          $MotorSeguranca = $dados['MotorSeguranca'];
                    ?>
                    <tbody>
                      <tr>
                        <th><img src="../imagens/carros/<?php echo $Imagem;?>" width="100" alt="<?php echo $Modelo;?>"></th>
                        <td><?php echo $Modelo;?></td>
                        <td><a href="#" class="text-primary"><?php echo $Ano;?></a></td>
                        <td><span class="badge bg-success"><?php echo $Placa;?></span></td>
                        <td><?php echo $Diponivel == 1 ? "Sim":"Não";?></td>
                        <td><?php echo $ValorDiaria;?></td>
                        <td> 
                          <div class="btn-group">
                            <a class="btn btn-secondary" href="../imprimir/carro.php?id=<?php echo $CarroID;?>"><i   class="bi-eye"></i></a>
                            <a class="btn btn-primary" href="edit_veiculo.php?id=<?php echo $CarroID;?>"><i class="ri-edit-line"></i></a>
                            <a class="btn btn-danger" href="carro/deletar.php?id=<?php echo $CarroID;?>" onclick="return confirm('Tens Certeza que quer Apagar Este Registo?')" ><i class="ri-delete-bin-5-line"></i><a>
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
    <!-- Modal Novo Aluguer -->
    <div class="modal fade" id="largeModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Cadastrar Veículo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Dados do Veículo</h5>

                <!-- No Labels Form -->
                <form class="row g-3" method="POST" action="carro/inserir.php" enctype="multipart/form-data">
                  <div class="col-md-6">
                    <input type="text" name="modelo" class="form-control" placeholder="Modelo">
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="ano" class="form-control" placeholder="Ano">
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="placa" class="form-control" placeholder="Placa">
                  </div>
                  <div class="col-6">
                    <input type="number" class="form-control" name="valorDiario" placeholder="Valor Diário">
                  </div>
                  <div class="col-6">
                    <input type="text" class="form-control" name="porta" placeholder="Portas">
                  </div>
                  <div class="col-6">
                    <input type="text" class="form-control" name="lugar" placeholder="Lugares">
                  </div>
                  <div class="col-6">
                    <input type="text" class="form-control" name="bagageira" placeholder="Bagageira">
                  </div>
                  <div class="col-6">
                    <input type="text" class="form-control" name="motorSeguranca" placeholder="Motor e Segurança">
                  </div>
                  <div class="col-6">
                    <input type="text" class="form-control" name="conforto" placeholder="Conforto">
                  </div>
                  <div class="col-md-6">
                    <input type="file" name="imagem" class="form-control" placeholder="Imagem">
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