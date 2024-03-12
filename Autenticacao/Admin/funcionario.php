<?php
$tituloPagina ="Funcionários Cadastrados";
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
    if (isset($_SESSION['msg_func'])) {
        echo $_SESSION['msg_func'];
        unset($_SESSION['msg_func']);
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
                        <th scope="col">BI/PassPort</th>
                        <th scope="col">Doc.Nº</th>
                        <th scope="col">Provincia</th>
                        <th scope="col">Município</th>
                        <th scope="col">Bairro</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Permissão</th>
                        <th scope="col">Situação</th>
                        <th scope="col">Acção</th>
                      </tr>
                    </thead>
                    <?php
                      //Pesquisar carro
                      if(!empty($_GET['pesquisar'])) {
                        $dados = $_GET['pesquisar'];
                        $sql="SELECT us.UsuarioID,Telefone,Situacao,Nome,Email,Provincia,Municipio,Bairro,Permissao,dm.Documento,dm.FileDoc,dm.NumDocumento,dm.dataValidade,dm.SituacaoDoc FROM usuarios us inner join documentos dm on us.DocumentoID=dm.DocumentoID WHERE NumDocumento like '%$dados%' AND Permissao ='recepcionista' ORDER BY Nome LIMIT $inicio, $qnt_result_pg";
                      }else {
                        $sql="SELECT us.UsuarioID,Telefone,Situacao,Nome,Email,Provincia,Municipio,Bairro,Permissao,dm.Documento,dm.FileDoc,dm.NumDocumento,dm.dataValidade,dm.SituacaoDoc FROM usuarios us inner join documentos dm on us.DocumentoID=dm.DocumentoID Where Permissao ='recepcionista' ORDER BY Nome LIMIT $inicio, $qnt_result_pg";
                      }
                      $prepare_func = $conexao->prepare($sql);
                      $prepare_func->execute();
                      $result_func=$prepare_func->fetchAll(PDO::FETCH_ASSOC);
                      foreach($result_func as $dados){
                        $Telefone = $dados['Telefone'];  
                        $Nome = $dados['Nome'];
                        $Situacao = $dados['Situacao'];
                        $UsuarioID = $dados['UsuarioID'];
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
                    ?>
                    <tbody>
                      <tr>
                        <td><?php echo $UsuarioID;?></td>
                        <td><?php echo $Nome;?></td>
                        <td><?php echo $Documento;?></td>
                        <td><?php echo $NumDocumento;?></td>
                        <td><?php echo $Provincia;?></td>
                        <td><?php echo $Municipio;?></td>
                        <td><?php echo $Bairro;?></td>
                        <td><?php echo $Telefone;?></td>
                        <td><?php echo $Email;?></td>
                        <td><?php echo $Permissao;?></td>
                        <td><?php echo $Situacao == 'Activo' ? "<a class='btn btn-warning' href='funcionario/disponivel.php?Disponivel=Activo&id=$UsuarioID'><i class='bi-undo'></i> $Situacao</a>":"<a class='btn btn-dark' href='funcionario/disponivel.php?Disponivel=Inactivo&id=$UsuarioID'><i class='bi-undo'></i>$Situacao</a>";?></td>
                        <td> 
                          <div class="btn-group">
                            <a class="btn btn-primary" href="edit_Funcionario.php?id=<?php echo $UsuarioID;?>"><i class="ri-edit-line"></i></a>
                            <a class="btn btn-danger" href="funcionario/deletar.php?id=<?php echo $UsuarioID;?>" onclick="return confirm('Tens Certeza que quer Apagar Este Registo?')" ><i class="ri-delete-bin-5-line"></i><a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                    <?php };
                      //Somar todos os registros 
                      $result_pg="SELECT Count(UsuarioID) as NumID FROM usuarios";
                      $resultado_pg=$conexao->prepare($result_pg);
                      $exec_pg = $resultado_pg->execute();
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
            <h5 class="modal-title">Cadastrar Funcionário</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="card-body">
              
                <!-- No Labels Form -->
                <form class="row g-3" method="POST" action="funcionario/inserir.php" enctype="multipart/form-data">
                  <div class="col-md-6">
                    <input type="text" name="nome" class="form-control" placeholder="Nome" autocomplete="off" >
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="sobreNome" class="form-control" placeholder="Sobrenome" autocomplete="off" >
                  </div>
                  <div class="col-6">
                    <input type="text" class="form-control" name="provincia" placeholder="Província" autocomplete="off">
                  </div>
                  <div class="col-6">
                    <input type="text" class="form-control" name="municipio" placeholder="Município" autocomplete="off">
                  </div>
                  <div class="col-6">
                    <input type="text" class="form-control" name="bairro" placeholder="Bairro" autocomplete="off">
                  </div>
                  <div class="col-6">
                    <input type="number" class="form-control" name="telefone" placeholder="Telefone" autocomplete="off" minlength="12">
                  </div>
                  <div class="col-md-6">
                    <select name="documento" id="" class="form-control" >
                      <option value="">Documento</option>
                      <option value="B.I">B.I</option>
                      <option value="Passa-Porte">Passa-Porte</option>
                    </select>
                  </div>
                  <div class="col-6">
                    <input type="text" class="form-control" name="numDocumento" placeholder="Doc. Nº" autocomplete="off" minlength="6" >
                  </div>
                  <div class="col-6">
                    <input type="date" class="form-control" name="dataValidade" placeholder="Doc. Nº" autocomplete="off" >
                  </div>
                  <div class="col-md-6">
                    <select name="permissao" id="" class="form-control" required>
                      <option value="">Permissão</option>
                      <option value="Administrador">Administrador</option>
                      <option value="Recepcionista">Recepcionista</option>
                    </select>
                  </div>
                  <div class="col-6">
                    <input type="file" class="form-control" name="fileDoc" placeholder="" autocomplete="off" minlength="6" >
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="add_func" class="btn btn-primary">Guardar</button>
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
    window.location = 'funcionario.php?pesquisar='+pesquisar.value;
}
<?php
//-- ======= Footer ======= -->
include_once 'footer/footer.php';
?>   