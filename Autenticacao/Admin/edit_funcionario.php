<?php
$pagina ="Actualizar Dados do Funcionário";
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
  $sql="SELECT us.UsuarioID,us.DocumentoID,Telefone,Situacao,Nome,Email,Provincia,Municipio,Bairro,Permissao,dm.Documento,dm.FileDoc,dm.NumDocumento,dm.dataValidade,dm.SituacaoDoc FROM usuarios us inner join documentos dm on us.DocumentoID=dm.DocumentoID WHERE UsuarioID = :id LIMIT 1";
  $prepare_edit_func = $conexao->prepare($sql);
  $prepare_edit_func->bindParam(':id',$id,PDO::PARAM_INT);
  $prepare_edit_func->execute();
  $result_edit_func = $prepare_edit_func->fetchAll(PDO::FETCH_ASSOC);
  foreach($result_edit_func as $dados){
    $Telefone = $dados['Telefone'];  
    $Nome = $dados['Nome'];
    $Situacao = $dados['Situacao'];
    $UsuarioID = $dados['UsuarioID'];
    $DocumentoID = $dados['DocumentoID'];
    $Email = $dados['Email'];
    $Permissao = $dados['Permissao'];
    $SituacaDoc = $dados['SituacaoDoc'];  
    $Provincia = $dados['Provincia'];
    $Municipio = $dados['Municipio'];
    $Bairro = $dados['Bairro'];
    $Documento = $dados['Documento'];
    $FileDoc = $dados['FileDoc'];
    $Email = $dados['Email'];
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
    if (isset($_SESSION['msg_edit_func'])) {
        echo $_SESSION['msg_edit_func'];
        unset($_SESSION['msg_edit_func']);
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
                        <form class="row g-3" method="POST" action="funcionario/alterar.php">
                          <div class="col-md-6">
                            <input type="hidden" name="UsuarioID" class="form-control" placeholder="Usuario ID" autocomplete="off"  value="<?php echo $UsuarioID;?>"  required>
                          </div>
                          <div class="col-md-6">
                            <input type="hidden" name="DocumentoID" class="form-control" placeholder="Documento ID" autocomplete="off" value="<?php echo $DocumentoID;?>"  required>
                          </div>
                          <div class="col-md-6">
                            <input type="text" name="nome" class="form-control" placeholder="Nome" autocomplete="off" minlength="4" value="<?php echo $Nome;?>"  required>
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="provincia" placeholder="Província" autocomplete="off" value="<?php echo $Provincia;?>">
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="municipio" placeholder="Município"  value="<?php echo $Municipio;?>" autocomplete="off">
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="bairro" placeholder="Bairro" autocomplete="off" value="<?php echo $Bairro;?>">
                          </div>
                          <div class="col-6">
                            <input type="number" class="form-control" name="telefone" placeholder="Telefone" autocomplete="off" minlength="13" maxlength="13" value="<?php echo $Telefone;?>">
                          </div>
                          <div class="col-md-6">
                            <select name="documento" id="" class="form-control" >
                              <option value="">Documento</option>
                              <option value="B.I"<?php if ($Documento == "B.I") {
                                echo"Selected";
                              };?>>B.I</option>
                              <option value="Passa-Porte"<?php if ($Documento=="Passa-Porte") {
                                echo"Selected";
                              };?>>Passa-Porte</option>
                            </select>
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="numDoc" placeholder="Doc. Nº" autocomplete="off" minlength="6" value="<?php echo $NumDocumento;?>">
                          </div>
                          <div class="col-6">
                            <input type="date" class="form-control" name="dataValidade" placeholder="Doc. Nº" autocomplete="off" value="<?php echo $DataVal;?>">
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