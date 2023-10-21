<?php


require('fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    $this->Image('../img/logo/logo.png',20,6,60);
    // Arial bold 15
    $this->SetFont('Arial','',10);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(0,10,'',0,1,'C');
    $this->Cell(0,15,'',0, 1,'C');
    $this->Cell(90,10,'PROPRIEDADE DA FARMHOUSE, LDA',0,1,'L');
    $this->Cell(0,10,'MORRO BENTO, AVENIDA 21 DE JANEIRO, GAMEK A DIREITA, RUA DA FARMHOUSE',0,1,'L');
    $this->Cell(0,10,utf8_decode('Telef: 222 401 231/ Fax: 222 442 751/ Móvel: 926 774 627'),0,1,'L');
    $this->Cell(0,10,utf8_decode('Contribuinte Nº 5403075232'),0,1,'L');
      // Arial bold 10 negritado B
    $this->SetFont('Arial','B',12);
    $this->Cell(0,10,utf8_decode('Relatório de Solicitações de Artigo a Repográfia'),0,1,'C');
    $this->Cell(160,10,utf8_decode('Data Doc.'),0,0,'R');
    $this->SetFont('Arial','',10); 
    $data = date('Y.m.d');
    $this->Cell(26,10,utf8_decode($data),0,1,'C');
    $this->Cell(0,5,utf8_decode(''),0,1,'C');
   
    
    $this->SetFont('Arial','B',10);//tipo de letra do cabeçalho da tabela, B negritado
    $this->Cell(15,10,utf8_decode('ID'),1,0,'C');
    $this->Cell(50,10,utf8_decode('Artigo'),1,0,'C');
    $this->Cell(17,10,utf8_decode('Qtd.'),1,0,'C');
    $this->Cell(30,10,utf8_decode('Dept.'),1,0,'C');
    $this->Cell(32,10,utf8_decode('Solicitante'),1,0,'C');
    $this->Cell(20,10,utf8_decode('Estado'),1,0,'C');
    $this->Cell(22,10,utf8_decode('Data'),1,1,'C');
    //Incluir a conexão com o banco de dados
    $conexao = mysqli_connect("localhost","root","","repografia");         
    //COnsulta ao Banco de dados
    $estado = 0;//cancelado ou apagado
    //$estadoAceite = 1;//Aceite
    //$estadoAtivo = 2;//Activo
    //$estadoDevolvido = 4;//Devolvido
    

        if(isset($_GET['sol'])){
        $solicitante = $_GET['sol'];
        $sql = "SELECT id,descricao,date(dataSolicitada)as dataRegisto,e.id_entrada as entrada,dataAsolicitar,quantindade,estadoSolicitacao,u.nome as usuario,categoria_usuario,a.nome as artigo FROM tb_solicitacao s inner join tb_saida sa on s.idSaida = sa.id_saida 
        join tb_nivel_acesso n on s.idNivel_acesso = n.id_nivel_acesso join tb_usuario u on n.id_usuario = u.id_usuario 
        join tb_categoria_usuario cu on n.id_categoria_usuario = cu.id_categoria_usuario join tb_entrada e on sa.id_entrada = e.id_entrada 
        join tb_artigo a on e.id_artigo = a.id_artigo 
        WHERE estadoSolicitacao > $estado and u.nome = '$solicitante' order by dataSolicitada DESC";
        $resultado = mysqli_query($conexao, $sql);
        $numRegistos = mysqli_num_rows($resultado);
        while($dados = mysqli_fetch_array($resultado)):
        
        $this->Cell(15,10,utf8_decode($dados['id']),1,0,'C');
        $this->Cell(50,10,utf8_decode($dados['artigo']),1,0,'C');
        $this->Cell(17,10,utf8_decode($dados['quantindade']),1,0,'C');
        $this->Cell(30,10,utf8_decode($dados['categoria_usuario']),1,0,'C');
        $this->Cell(32,10,utf8_decode($dados['usuario']),1,0,'C');
        $estado = $dados['estadoSolicitacao'];
        if($estado == 2){
            $estado = "Pendente";
        }elseif($estado == 1){
            $estado = "Aceite";
        }elseif($estado == 4){
            $estado = "Devolvido";
        }
        $this->Cell(20,10,utf8_decode($estado),1,0,'C');
        $this->Cell(22,10,utf8_decode($dados['dataRegisto']),1,1,'C');

        endwhile;
        $this->Ln(20);
    }

    if(isset($_GET['data'])){
        $dataRegisto = $_GET['data'];
        $sql = "SELECT id,descricao,date(dataSolicitada)as dataRegisto,e.id_entrada as entrada,dataAsolicitar,quantindade,estadoSolicitacao,u.nome as usuario,categoria_usuario,a.nome as artigo FROM tb_solicitacao s inner join tb_saida sa on s.idSaida = sa.id_saida 
        join tb_nivel_acesso n on s.idNivel_acesso = n.id_nivel_acesso join tb_usuario u on n.id_usuario = u.id_usuario 
        join tb_categoria_usuario cu on n.id_categoria_usuario = cu.id_categoria_usuario join tb_entrada e on sa.id_entrada = e.id_entrada 
        join tb_artigo a on e.id_artigo = a.id_artigo 
        WHERE estadoSolicitacao > $estado and date(dataSolicitada) = '$dataRegisto' order by dataSolicitada DESC";
        $resultado = mysqli_query($conexao, $sql);
        $numRegistos = mysqli_num_rows($resultado);
        while($dados = mysqli_fetch_array($resultado)):
        
        $this->Cell(15,10,utf8_decode($dados['id']),1,0,'C');
        $this->Cell(50,10,utf8_decode($dados['artigo']),1,0,'C');
        $this->Cell(17,10,utf8_decode($dados['quantindade']),1,0,'C');
        $this->Cell(30,10,utf8_decode($dados['categoria_usuario']),1,0,'C');
        $this->Cell(32,10,utf8_decode($dados['usuario']),1,0,'C');
        $estado = $dados['estadoSolicitacao'];
        if($estado == 2){
            $estado = "Pendente";
        }elseif($estado == 1){
            $estado = "Aceite";
        }elseif($estado == 4){
            $estado = "Devolvido";
        }
        $this->Cell(20,10,utf8_decode($estado),1,0,'C');
        $this->Cell(22,10,utf8_decode($dados['dataRegisto']),1,1,'C');

        endwhile;
        $this->Ln(20);
    }
}
// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','',10);
    // Page number
    $this->Cell(0,10,utf8_decode('Página').$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->Output();
?>