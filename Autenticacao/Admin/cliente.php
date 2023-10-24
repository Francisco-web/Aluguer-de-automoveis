<?php
$tituloPagina ="Clientes Cadastrados";
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
                  <h5 class="card-title"> Consultar Lista de Clientes</h5>
                    
                  <table class="table table-bordered border-primary">
                    <thead>
                      <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Nº BI/Passa Porte</th>
                        <th scope="col">Nº Carta de Condução</th>
                        <th scope="col">Email</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Situação</th>
                        <th scope="col">Acção</th>
                      </tr>
                    </thead>
                    <?php
                      //Pesquisar carro
                      if(!empty($_GET['pesquisar'])) {
                        $dados = $_GET['pesquisar'];
                        $EstadoCliente="Apagado";
                        $sql="SELECT cl.UsuarioID,ClienteID,Nome,CartaConducao,EstadoUsuario,Telefone,Endereco,NumDocumento,Documento,Email FROM clientes cl inner join usuarios u on cl.UsuarioID = u.UsuarioID WHERE EstadoCliente != '$EstadoCliente' and Nome like '%$dados%' ORDER BY Nome LIMIT $inicio, $qnt_result_pg";
                      }else {
                        $EstadoCliente="Apagado";
                        $sql="SELECT cl.UsuarioID,ClienteID,Nome,CartaConducao,EstadoUsuario,Telefone,Endereco,NumDocumento,Documento,Email FROM clientes cl inner join usuarios u on cl.UsuarioID = u.UsuarioID WHERE EstadoCliente != '$EstadoCliente' ORDER BY Nome LIMIT $inicio, $qnt_result_pg";
                      }
                      $query = mysqli_query($conexao,$sql);
                      while ($dados=mysqli_fetch_array($query)) :
                        $Documento = $dados['Documento']; 
                        $NumDocumento = $dados['NumDocumento']; 
                        $ClienteID = $dados['ClienteID'];  
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
                        <td><?php echo $Nome;?></td>
                        <td><?php echo $Documento;?></td>
                        <td class="text-primary"><?php echo $NumDocumento;?></td>
                        <td class="text-primary"><?php echo $CartaConducao;?></td>
                        <td><?php echo $Email;?></td>
                        <td><?php echo $Telefone;?></td>
                        <td><?php echo $Estadousuario == 'Activo' ? "<a class='btn btn-warning' disabled href='cliente/disponivel.php?Disponivel=Activo&id=$UsuarioID'><i class='bi-undo'></i> $Estadousuario</a>":"<a class='btn btn-dark' href='cliente/disponivel.php?Disponivel=Inactivo&id=$UsuarioID'><i class='bi-undo'></i>$Estadousuario</a>";?></td>
                        <td> 
                          <div class="btn-group">
                            <a class="btn btn-secondary" href="../imprimir/cliente.php?id=<?php echo $ClienteID;?>"><i   class="bi-eye"></i></a>
                            <a class="btn btn-primary" href="edit_cliente.php?id=<?php echo $ClienteID;?>"><i class="ri-edit-line"></i></a>
                            <a class="btn btn-danger" href="cliente/deletar.php?id=<?php echo $ClienteID;?>" onclick="return confirm('Tens Certeza que quer Apagar Este Registo?')" ><i class="ri-delete-bin-5-line"></i><a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                    <?php endwhile;
                      //Somar todos os registros 
                      $result_pg="SELECT Count(ClienteID) as NumID FROM clientes cl inner join usuarios u on cl.UsuarioID = u.UsuarioID";
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
                          <li class="page-item"><?PHP echo "<a class='page-link' href='cliente.php?pagina=1'>Anterior</a>"?></li>
                          <?php 
                              for($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++){
                              if($pag_ant >= 1){
                          ?>

                          <li class="page-item"><?PHP echo "<a class='page-link' href='cliente.php?pagina=$pag_ant'>$pag_ant</a>"?></li>
                          <?php	}
                          } ?>

                          <li class="page-item"><?PHP echo "<a class='page-link' href='cliente.php?pagina=$pagina'> $pagina</a>";?></li>

                          <?php 
                              for($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++){
                              if($pag_dep <= $quantidade_pg){ 
                          ?>		
                          <li class="page-item"><?PHP echo "<a class='page-link' href='cliente.php?pagina=$pag_dep'>$pag_dep</a>";?></li>
                          <?php	}
                          } ?>
                          <li class="page-item"><?PHP echo "<a class='page-link' href='cliente.php?pagina=$quantidade_pg'>Proximo</a>"?></li>
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
            <h5 class="modal-title">Cadastrar Cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Dados do Cliente</h5>

                <!-- No Labels Form -->
                <form class="row g-3" method="POST" action="cliente/inserir.php" enctype="multipart/form-data">
                  <div class="col-md-6">
                    <input type="text" name="nome" class="form-control" placeholder="Nome" autocomplete="off" required>
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="telefone" class="form-control" placeholder="Telefone" autocomplete="off" required>
                  </div>
                  <div class="col-6">
                    <input type="Email" class="form-control" name="email" placeholder="Email" autocomplete="off" required>
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="cartaConducao" class="form-control" placeholder="Nº Carta de Condução" autocomplete="off" required >
                  </div>
                  <div class="col-md-6">
                    <textarea name="endereco" id="endereco" autocomplete="off" required class="form-control" cols="5" rows="3" placeholder="Endereço"></textarea>
                  </div>
                  <div class="col-md-6">
                    <select name="documento" id="" class="form-control" required>
                      <option value="">Selecionar Documento</option>
                      <option value="B.I">Bilhete de Identidade</option>
                      <option value="Passa Porte">Passa Porte</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                  <input type="text" name="numDocumento" class="form-control" placeholder="Nº Documento" autocomplete="off" required >
                  </div>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="situacao" id="gridRadios1" value="Activo" checked="">
                      <label class="form-check-label" for="gridRadios1">
                        Conta Activa
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="situacao" id="gridRadios2" value="Inactivo">
                      <label class="form-check-label" for="gridRadios2">
                        Conta Inactiva
                      </label>
                    </div>
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
    window.location = 'cliente.php?pesquisar='+pesquisar.value;
}
<?php
//-- ======= Footer ======= -->
include_once 'footer/footer.php';
?>   