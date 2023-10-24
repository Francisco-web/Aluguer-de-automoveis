<?php
$tituloPagina ="Motoristas Cadastrados";
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
                  <h5 class="card-title"> Consultar Lista de Motorista</h5>
                    
                  <table class="table table-bordered border-primary">
                    <thead>
                      <tr>
                        <th scope="col">Imagem</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Nº Carta de Condução</th>
                        <th scope="col">Email</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Endereco</th>
                        <th scope="col">Situação</th>
                        <th scope="col">Acção</th>
                      </tr>
                    </thead>
                    <?php
                      //Pesquisar carro
                      if(!empty($_GET['pesquisar'])) {
                        $dados = $_GET['pesquisar'];
                        $EstadoUsuario="Activo";
                        $sql="SELECT Imagem,MotoristaID,Nome,CartaConducao,EstadoUsuario,Telefone,Endereco FROM motoristas m inner join usuarios u on m.UsuarioID = u.UsuarioID WHERE EstadoMotorista='$EstadoMotorista' and Nome like '%$dados%' ORDER BY Nome LIMIT $inicio, $qnt_result_pg";
                      }else {
                        $EstadoMotorista="Activo";
                        $sql="SELECT Imagem,MotoristaID,Nome,CartaConducao,Email,EstadoUsuario,Telefone,Endereco,m.UsuarioID FROM motoristas m inner join usuarios u on m.UsuarioID = u.UsuarioID WHERE EstadoMotorista ='$EstadoMotorista' ORDER BY Nome LIMIT $inicio, $qnt_result_pg";
                      }
                      $query = mysqli_query($conexao,$sql);
                      while ($dados=mysqli_fetch_array($query)) :
                        $Imagem = $dados['Imagem']; 
                        $MotoristaID = $dados['MotoristaID'];  
                        $Nome = $dados['Nome'];
                        $CartaConducao = $dados['CartaConducao'];
                        $Telefone = $dados['Telefone'];
                        $Estadousuario = $dados['EstadoUsuario'];
                        $Endereco = $dados['Endereco'];
                        $UsuarioID = $dados['UsuarioID'];
                        $Email = $dados['Email'];
                    ?>
                    <tbody>
                      <tr>
                        <th><img src="../imagens/usuarios/<?php echo $Imagem;?>" width="100" height="80" alt="<?php echo $Nome;?>"></th>
                        <td><?php echo $Nome;?></td>
                        <td class="text-primary"><?php echo $CartaConducao;?></td>
                        <td><?php echo $Email;?></td>
                        <td><?php echo $Telefone;?></td>
                        <td><?php echo $Endereco;?></td>
                        <td><?php echo $Estadousuario == 'Activo' ? "<a class='btn btn-warning' disabled href='motorista/disponivel.php?Disponivel=Activo&id=$UsuarioID'><i class='bi-undo'></i> $Estadousuario</a>":"<a class='btn btn-dark' href='motorista/disponivel.php?Disponivel=Inactivo&id=$UsuarioID'><i class='bi-undo'></i>$Estadousuario</a>";?></td>
                        <td> 
                          <div class="btn-group">
                            <a class="btn btn-secondary" href="../imprimir/motorista.php?id=<?php echo $MotoristaID;?>"><i   class="bi-eye"></i></a>
                            <a class="btn btn-primary" href="edit_motorista.php?id=<?php echo $MotoristaID;?>"><i class="ri-edit-line"></i></a>
                            <a class="btn btn-danger" href="motorista/deletar.php?id=<?php echo $MotoristaID;?>" onclick="return confirm('Tens Certeza que quer Apagar Este Registo?')" ><i class="ri-delete-bin-5-line"></i><a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                    <?php endwhile;
                      //Somar todos os registros 
                      $EstadoMotorista ="Apagado";
                      $result_pg="SELECT Count(MotoristaID) as NumID FROM motoristas m inner join usuarios u on m.UsuarioID = u.UsuarioID WHERE EstadoMotorista !='$EstadoMotorista'";
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
                          <li class="page-item"><?PHP echo "<a class='page-link' href='motorista.php?pagina=1'>Anterior</a>"?></li>
                          <?php 
                              for($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++){
                              if($pag_ant >= 1){
                          ?>

                          <li class="page-item"><?PHP echo "<a class='page-link' href='motorista.php?pagina=$pag_ant'>$pag_ant</a>"?></li>
                          <?php	}
                          } ?>

                          <li class="page-item"><?PHP echo "<a class='page-link' href='motorista.php?pagina=$pagina'> $pagina</a>";?></li>

                          <?php 
                              for($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++){
                              if($pag_dep <= $quantidade_pg){ 
                          ?>		
                          <li class="page-item"><?PHP echo "<a class='page-link' href='motorista.php?pagina=$pag_dep'>$pag_dep</a>";?></li>
                          <?php	}
                          } ?>
                          <li class="page-item"><?PHP echo "<a class='page-link' href='motorista.php?pagina=$quantidade_pg'>Proximo</a>"?></li>
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
            <h5 class="modal-title">Cadastrar Motorista</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Dados do Motorista</h5>

                <!-- No Labels Form -->
                <form class="row g-3" method="POST" action="motorista/inserir.php" enctype="multipart/form-data">
                  <p><strong>Dados Pessoais</strong></p>
                  <div class="col-md-6">
                    <input type="text" name="nome" class="form-control" placeholder="Nome" autocomplete="off" required>
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="telefone" class="form-control" placeholder="Telefone" autocomplete="off" required>
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="cartaConducao" class="form-control" placeholder="Nº Carta de Condução" autocomplete="off" required >
                  </div>
                  <div class="col-md-6">
                    <textarea name="endereco" id="endereco" autocomplete="off" required class="form-control" cols="5" rows="3" placeholder="Endereço"></textarea>
                  </div>
                  <p><strong>Dados de Usuário</strong></p>
                  <div class="col-6">
                    <input type="Email" class="form-control" name="email" placeholder="Email" autocomplete="off" required>
                  </div>
                  <div class="col-6">
                    <input type="text" class="form-control" name="senha" minlenth="6" placeholder="Senha" autocomplete="off" required>
                  </div>
                  <div class="col-md-6">
                    <input type="file" name="imagem" class="form-control" placeholder="Imagem" required>
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
    window.location = 'motorista.php?pesquisar='+pesquisar.value;
}
<?php
//-- ======= Footer ======= -->
include_once 'footer/footer.php';
?>   