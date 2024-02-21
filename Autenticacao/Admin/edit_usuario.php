<?php
$pagina ="Actualizar Dados do Usuário";
// ======= Head ======= -->
include_once 'head/head.php';

// ======= Header ======= -->
include_once 'header/header.php';

//-- ======= Sidebar ======= -->
include_once 'sidebar/sidebar.php';
 
//-- ======= Consulta ao anco de Dados ======= -->
if (isset($_GET['id'])) {
  $id = 1;
  //Consulta no banco para mostar os dados do motorista
  $sql="SELECT UsuarioID,Permissao,Situacao,Email,Senha FROM usuarios WHERE UsuarioID=$id limit 1";
  $prepare = $conexao->prepare($sql);
  $prepare->execute();
  $resultado=$prepare->fetchAll(PDO::FETCH_ASSOC);
  foreach($resultado as $dados){
    $ID = $dados['UsuarioID'];
    $Situacao = $dados['Situacao'];
    $Email = $dados['Email'];
    $Senha = $dados['Senha'];
    $Permissao = $dados['Permissao'];
  }
  
}
?>  18

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
    if (isset($_SESSION['msg_alterar_usuario'])) {
        echo $_SESSION['msg_alterar_usuario'];
        unset($_SESSION['msg_alterar_usuario']);
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
                        <form class="row g-3" method="POST" action="usuario/alterar.php">
                          
                          <input type="hidden" class="form-control" value="<?php echo $id;?>" name="UsuarioID">
                          
                          <div class="col-6">
                            <input type="email" class="form-control" name="email" placeholder="Email" autocomplete="off" value="<?php echo $Email;?>" required>
                          </div>
                          <div class="col-md-6">
                            <input type="text" name="senha_atualizada" class="form-control" placeholder="Senha" autocomplete="off" minlength="6" >
                          </div>
                          <div class="col-md-6">
                            <select name="permissao" id="" class="form-control" required>
                              <option value="">Permissão</option>
                              <option value="Administrador" <?php if ($Permissao == "Administrador") {
                                echo"Selected";
                              }?> >Administrador</option>
                              <option value="Recepcionista" <?php if ($Permissao=="Recepcionista") {
                                echo "Selected";
                              }?> >Recepcionista</option>
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