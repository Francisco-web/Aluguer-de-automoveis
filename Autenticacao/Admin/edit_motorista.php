<?php
$pagina ="Actualizar Dados do Motorista";
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
  $sql="SELECT us.UsuarioID,dm.DocumentoID,mot.MotoristaID,Telefone,SituacaoMotorista,Situacao,Nome,Email,Provincia,Municipio,Bairro,Permissao,dm.Documento,dm.FileDoc,dm.NumDocumento,dm.dataValidade,dm.SituacaoDoc FROM usuarios us inner join documentos dm on us.DocumentoID=dm.DocumentoID join motoristas mot on us.UsuarioID=mot.UsuarioID WHERE us.UsuarioID = :id LIMIT 1";
  $prepare_edit_func = $conexao->prepare($sql);
  $prepare_edit_func->bindParam(':id',$id,PDO::PARAM_INT);
  $prepare_edit_func->execute();
  $result_edit_func = $prepare_edit_func->fetchAll(PDO::FETCH_ASSOC);
  foreach($result_edit_func as $dados){
    $Telefone = $dados['Telefone'];  
    $Nome = $dados['Nome'];
    $Situacao = $dados['Situacao'];
    $UsuarioID = $dados['UsuarioID'];
    $MotoristaID = $dados['MotoristaID'];
    $SituacaoMotorista = $dados['SituacaoMotorista'];
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
    if (isset($_SESSION['msg_edit_motorista'])) {
        echo $_SESSION['msg_edit_motorista'];
        unset($_SESSION['msg_edit_motorista']);
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
                        <form class="row g-3" method="POST" action="motorista/alterar.php">
                          <div class="col-md-6">
                            <input type="hidden" class="form-control" value="<?php echo $DocumentoID;?>" name="documentoID">
                          </div>  
                          <div class="col-md-6">
                            <input type="hidden" class="form-control" value="<?php echo $MotoristaID;?>" name="motoristaID">
                          </div>  
                          <div class="col-md-6">
                          <input type="hidden" class="form-control" value="<?php echo $UsuarioID;?>" name="usuarioID">
                          </div> 
                          <div class="col-md-6">
                            <img src="../imagens/usuarios/<?php echo $Imagem;?>" width="200px" height="200px"  alt="fotografia">
                          </div>
                          <p><strong>Dados Pessoais</strong></p>
                          <div class="col-md-6">
                            <input type="text" name="nome" class="form-control" placeholder="Nome" autocomplete="off" minlength="4" value="<?php echo $Nome;?>"  required>
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
                          <div class="col-md-6">
                            <input type="text" name="numeroDocumento" class="form-control" placeholder="Nº Carta de Condução" autocomplete="off" value="<?php echo $NumDocumento;?>" required >
                          </div>
                          <div class="col-md-6">
                            <input type="date" name="dataValidadeDocumento" class="form-control" placeholder="Telefone" autocomplete="off" value="<?php echo $DataVal;?>" required>
                          </div>
                          <p><strong>Endereço</strong></p>
                          <div class="col-md-6">
                            <input name="provincia" id="provincia" autocomplete="off" required class="form-control" placeholder="Província" value="<?php echo $Provincia;?>">
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="municipio" placeholder="Município" autocomplete="off" value="<?php echo $Municipio;?>" required>
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="bairro" placeholder="Bairro" autocomplete="off" value="<?php echo $Bairro;?>"required>
                          </div>
                          <div class="col-6">
                            <input type="email" class="form-control" name="email" placeholder="Email" autocomplete="off" value="<?php echo $Email;?>" required>
                          </div>
                          <div class="col-md-6">
                            <input type="text" name="telefone" class="form-control" placeholder="Telefone" autocomplete="off" minlength="9" maxlength="9" value="<?php echo $Telefone;?>" required>
                          </div>
                          <div class="col-md-6">
                            <select name="situacaoMotorista" id="" class="form-control" required>
                              <option value="">Situação Motorista</option>
                              <option value="Disponível"<?php
                                if ($SituacaoMotorista=="Disponível") {
                                  echo"selected";
                                }
                              ?>>Disponível</option>
                              <option value="Indisponível"<?php
                                if ($SituacaoMotorista=="Indisponível") {
                                  echo"selected";
                                }
                              ?>>Indisponível</option>
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