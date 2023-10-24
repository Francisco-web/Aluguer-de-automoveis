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
  $id = mysqli_escape_string($conexao,$_GET['id']);
  //Consulta no banco para mostar os dados do motorista
  $sql="SELECT FuncionarioID,EstadoUsuario,fc.UsuarioID,Nome,Email FROM funcionarios fc inner join usuarios u on fc.UsuarioID = u.UsuarioID ORDER BY Nome ";
  $query = mysqli_query($conexao,$sql);
  $dados=mysqli_fetch_array($query);
  $FuncionarioID = $dados['FuncionarioID'];  
  $Nome = $dados['Nome'];
  $Estadousuario = $dados['EstadoUsuario'];
  $UsuarioID = $dados['UsuarioID'];
  $Email = $dados['Email'];
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
                        <form class="row g-3" method="POST" action="funcionario/alterar.php">
                          <input type="hidden" class="form-control" value="<?php echo $FuncionarioID;?>" name="FuncionarioID">
                          <input type="hidden" class="form-control" value="<?php echo $UsuarioID;?>" name="UsuarioID">
                          <div class="col-md-6">
                            <input type="text" name="nome" class="form-control" placeholder="Nome" autocomplete="off" minlength="4" value="<?php echo $Nome;?>"  required>
                          </div>
                          <div class="col-6">
                            <input type="email" class="form-control" name="email" placeholder="Email" autocomplete="off" value="<?php echo $Email;?>" required>
                          </div>
                          <div class="text-center">
                            <button type="submit" name="cancelar" class="btn btn-secondary" >Cancelar</button>
                            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
                          <button type="submit" name="redefinir_senha" class="btn btn-warning">Restaurar Senha</button>
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