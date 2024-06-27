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
  $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
  //Consulta no banco para mostar os dados do motorista
  $sql="SELECT car.CarroID,car.DocumentoID,Imagem,Modelo,Ano,Placa,Disponivel,ValorDiaria,MotorSeguranca,Lugar,Porta,Conforto,Bagageira,SituacaoCarro,Documento,FileDoc,NumDocumento,dataValidade,SituacaoDoc FROM carros car inner join documentos dm on car.DocumentoID=dm.DocumentoID WHERE car.CarroID =:carroID ORDER BY Modelo";
  $prepare_edit_func = $conexao->prepare($sql);
  $prepare_edit_func->bindParam(':carroID',$id,PDO::PARAM_INT);
  $prepare_edit_func->execute();
  $result_edit_func = $prepare_edit_func->fetchAll(PDO::FETCH_ASSOC);
  foreach($result_edit_func as $dados){
    //Dados Carro
    $CarroID = $dados['CarroID'];  
    $Imagem = $dados['Imagem'];
    $Modelo = $dados['Modelo'];
    $Ano = $dados['Ano'];
    $SituacaoCarro = $dados['SituacaoCarro'];
    $Placa = $dados['Placa'];
    $Disponivel = $dados['Disponivel'];
    $ValorDiaria = $dados['ValorDiaria'];
    $MotorSeguranca = $dados['MotorSeguranca'];
    $Lugar = $dados['Lugar'];
    $Porta = $dados['Porta'];  
    $Conforto = $dados['Conforto'];
    $Bagageira = $dados['Bagageira'];
    //Dados Documento
    $DocumentoID = $dados['DocumentoID'];
    $Documento = $dados['Documento'];
    $FileDoc = $dados['FileDoc'];
    $NumDocumento = $dados['NumDocumento'];
    $DataVal = $dados['dataValidade'];
  }
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
    if (isset($_SESSION['msg_carro'])) {
        echo $_SESSION['msg_carro'];
        unset($_SESSION['msg_carro']);
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
                            <img src="../imagens/carros/<?php echo $Imagem;?>" style="width:40%" alt="<?php echo $Modelo;?>" class="form-control">
                            <input type="file" class="col-md-6" name="imagem">
                          </div>
                          <div class="col-md-6">
                            <input type="text" class="form-control" value="<?php echo $CarroID;?>" name="CarroID">
                          </div>
                          <div class="col-md-6">
                            <input type="text" name="modelo" class="form-control" value="<?php echo $Modelo;?>" placeholder="Modelo">
                          </div>
                          <div class="col-md-6">
                            <input type="text" name="ano" class="form-control" value="<?php echo $Ano;?>" placeholder="Ano">
                          </div>
                          <div class="col-md-6">
                            <input type="text" name="placa" readonly class="form-control" value="<?php echo $Placa;?>" placeholder="Placa">
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="porta" value="<?php echo $Porta;?>" placeholder="Portas">
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="lugar" value="<?php echo $Lugar;?>" placeholder="Lugares">
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="bagageira" value="<?php echo $Bagageira;?>" placeholder="Bagageira">
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="motorSeguranca" value="<?php echo $MotorSeguranca;?>" placeholder="Motor e Segurança">
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="conforto" value="<?php echo $Conforto;?>" placeholder="Conforto">
                          </div>
                          <div class="col-6">
                            <input type="number" class="form-control" readonly name="valorDiario" value="<?php echo $ValorDiaria;?>" placeholder="Valor Diário">
                          </div>
                          <div class="col-6">
                            <Select class="form-control" name="situacaoCarro">
                              <option>Estado do Carro</option>
                              <option value="Disponível"<?php 
                                if ($SituacaoCarro=="Disponível") {
                                  echo "selected";
                                }
                              ?>>Disponível</option>
                              <option value="Indisponível"<?php 
                                if ($SituacaoCarro=="Indisponível") {
                                  echo "selected";
                                }
                              ?>>Indisponível</option>
                              <option value="Manutenção"<?php 
                                if ($SituacaoCarro=="Manutenção") {
                                  echo "selected";
                                }
                              ?>>Manutenção</option>
                            </Select>  
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