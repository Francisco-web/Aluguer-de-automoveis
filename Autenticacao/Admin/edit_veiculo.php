<?php
$pagina ="Actualizar Dados do Veículos";
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
  $sql="SELECT CarroID,Imagem,Modelo,Ano,Placa,descricao,Disponivel,ValorDiaria FROM carros WHERE CarroID = $id ORDER BY Modelo";
  $query = mysqli_query($conexao,$sql);
  $dados=mysqli_fetch_array($query);
  $CarroID = $dados['CarroID'];  
  $Imagem = $dados['Imagem'];
  $Modelo = $dados['Modelo'];
  $Ano = $dados['Ano'];
  $Placa = $dados['Placa'];
  $Diponivel = $dados['Disponivel'];
  $ValorDiaria = $dados['ValorDiaria'];
  $Descricao = $dados['descricao'];
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
                        <form class="row g-3" method="POST" action="carro/alterar.php">
                          <div class="col-md-6">
                            <img src="../imagens/carros/<?php echo $Imagem;?>" style="width:40%" alt="<?php echo $Modelo;?>">
                          </div>
                          <input type="text" class="form-control" value="<?php echo $CarroID;?>" name="CarroID">
                          <div class="col-md-6">
                            <input type="text" name="modelo" class="form-control" value="<?php echo $Modelo;?>" placeholder="Modelo">
                          </div>
                          <div class="col-md-6">
                            <input type="text" name="ano" class="form-control" value="<?php echo $Ano;?>" placeholder="Ano">
                          </div>
                          <div class="col-md-6">
                            <input type="text" name="placa" class="form-control" value="<?php echo $Placa;?>" placeholder="Placa">
                          </div>
                          <div class="col-6">
                            <input type="number" class="form-control" name="valorDiario" value="<?php echo $ValorDiaria;?>" placeholder="Valor Diário">
                          </div>
                          <div class="col-md-6">
                            <input type="text" name="descricao" class="form-control" value="<?php echo $Descricao;?>" placeholder="Descrição">
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