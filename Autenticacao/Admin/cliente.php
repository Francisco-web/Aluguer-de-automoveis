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
    if (isset($_SESSION['msg_cliente'])) {
        echo $_SESSION['msg_cliente'];
        unset($_SESSION['msg_cliente']);
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

                <div class="card-title">
                  <a class="btn btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal" href="#" ><i class="bi bi-plus"></i>Novo</a>
                </div>

                <div class="card-body">
                  <h5 class=""></h5>
                  <table class="table table-bordered border-primary">
                    <thead>
                      <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Doc. Nº</th>
                        <th scope="col">Município</th>
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
                        $sql="SELECT cl.ClienteID,cl.UsuarioID,Nome,Situacao,Telefone,Bairro,Provincia,Municipio,NumDocumento,Documento,Email,Permissao FROM clientes cl inner join usuarios u on cl.UsuarioID = u.UsuarioID join documentos d on u.DocumentoID = d.DocumentoID WHERE Nome like '%$dados%' or cl.UsuarioID like '%$dados%' ORDER BY Nome LIMIT $inicio, $qnt_result_pg";
                      }else {
                        $EstadoCliente="Apagado";
                        $sql="SELECT cl.ClienteID,cl.UsuarioID,Nome,Situacao,Telefone,Bairro,Provincia,Municipio,NumDocumento,Documento,Email,Permissao FROM clientes cl inner join usuarios u on cl.UsuarioID = u.UsuarioID join documentos d on u.DocumentoID = d.DocumentoID  ORDER BY Nome LIMIT $inicio, $qnt_result_pg";
                      }
                      $prepare_cliente = $conexao->prepare($sql);
                      $prepare_cliente->execute();
                      $resultado = $prepare_cliente->fetchAll(PDO::FETCH_ASSOC);
                      foreach($resultado as $dados){
                        $Documento = $dados['Documento']; 
                        $NumDocumento = $dados['NumDocumento']; 
                        $Nome = $dados['Nome'];
                        $Telefone = $dados['Telefone'];
                        $ClienteID = $dados['ClienteID'];
                        $UsuarioID = $dados['UsuarioID'];
                        $Email = $dados['Email'];
                        $Municipio = $dados['Municipio'];
                        $Situacao = $dados['Situacao'];
                    ?>
                    <tbody>
                      <tr>
                        <td><?php echo $UsuarioID;?></td>
                        <td><?php echo $Nome;?></td>
                        <td><?php echo $Documento;?></td>
                        <td><?php echo $NumDocumento;?></td>
                        <td><?php echo $Municipio;?></td>
                        <td><?php echo $Email;?></td>
                        <td><?php echo $Telefone;?></td>
                        <td><?php echo $Situacao == 'Activo' ? "<a class='btn btn-warning' href='cliente/disponivel.php?Disponivel=Activo&id=$UsuarioID'><i class='bi-undo'></i> $Situacao</a>":"<a class='btn btn-dark' href='cliente/disponivel.php?Disponivel=Inactivo&id=$UsuarioID'><i class='bi-undo'></i>$Situacao</a>";?></td>
                        <td> 
                          <div class="btn-group">
                            <a class="btn btn-primary" href="edit_cliente.php?id=<?php echo $ClienteID;?>"><i class="ri-edit-line"></i></a>
                            <a class="btn btn-danger" href="cliente/deletar.php?id=<?php echo $UsuarioID;?>"onclick="return confirm('Tens Certeza que quer Apagar Este Registo?')" ><i class="ri-delete-bin-5-line"></i><a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                    <?php };
                      //Somar todos os registros 
                      $result_pg="SELECT Count(ClienteID) as NumID FROM clientes cl inner join usuarios u on cl.UsuarioID = u.UsuarioID join documentos d on u.DocumentoID = d.DocumentoID";
                      $resultado_pg = $conexao->prepare($result_pg);
                      $resultado_pg->execute();
                      $row_pg = $resultado_pg->fetch();
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

                <!-- No Labels Form -->
                <form class="row g-3" method="POST" action="cliente/inserir.php" enctype="multipart/form-data">
                  <div class="col-md-6">
                    <input type="text" name="nome" class="form-control" placeholder="Nome" autocomplete="off" required>
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="provincia" class="form-control" placeholder="Província" autocomplete="off" required >
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="municipio" class="form-control" placeholder="Município" autocomplete="off" required >
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="bairro" class="form-control" placeholder="Bairro" autocomplete="off" required>
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="telefone" class="form-control" placeholder="Telefone" minlength="9" autocomplete="off" required>
                  </div>
                  <div class="col-6">
                    <input type="Email" class="form-control" name="email" placeholder="Email" autocomplete="off" required>
                  </div>
                  <div class="col-md-6">
                    <select name="documento" id="" class="form-control" required>
                      <option value="">Selecionar Documento</option>
                      <option value="B.I">Bilhete de Identidade</option>
                      <option value="Passa Porte">Passa Porte</option>
                      <option value="Carta de Condução">Carta de Condução</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                  <input type="text" name="numDocumento" class="form-control" placeholder="Nº Documento" minlength="14" autocomplete="off" required >
                  </div>
                  <div class="col-md-6">
                  <input type="date" name="dataValidade" class="form-control" placeholder="Data de validade" autocomplete="off" required >
                  </div>
                  <div class="col-md-6">
                  <input type="file" name="filedoc" class="form-control" placeholder="Ficheiro BI" autocomplete="off">
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