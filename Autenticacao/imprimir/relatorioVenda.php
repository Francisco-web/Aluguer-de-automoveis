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
    $this->Cell(0,10,utf8_decode('Relatório de Vendas da Repográfia'),0,1,'C');
    $this->Cell(160,10,utf8_decode('Data Doc.'),0,0,'R');
    $this->SetFont('Arial','',10); 
    $data = date('Y.m.d');
    $this->Cell(26,10,utf8_decode($data),0,1,'C');
    $this->Cell(0,5,utf8_decode(''),0,1,'C');
   
    
    $this->SetFont('Arial','B',10);//tipo de letra do cabeçalho da tabela, B negritado
    $this->Cell(55,10,utf8_decode('Artigo'),1,0,'C');
    $this->Cell(17,10,utf8_decode('Qtd.'),1,0,'C');
    $this->Cell(20,10,utf8_decode('Preço.'),1,0,'C');
    $this->Cell(20,10,utf8_decode('Total'),1,0,'C');
    $this->Cell(37,10,utf8_decode('O Repográfo'),1,0,'C');
    $this->Cell(40,10,utf8_decode('Data'),1,1,'C');
    //Incluir a conexão com o banco de dados
    $conexao = mysqli_connect("localhost","root","","repografia");         
    if (isset($_GET['data'])) {
       
    $data = $_GET['data'];
    $sql = "SELECT id_venda,id_check_saida,a.nome as artigo, u.nome as usuario,hv.qtd,hv.total,hv.valor,data_venda,dia FROM tb_historico_vendas hv inner join tb_saida s on hv.id_check_saida = s.id_saida join tb_usuario u on hv.id_usuario = u.id_usuario 
    join tb_entrada e on s.id_entrada = e.id_entrada 
    join tb_artigo a on e.id_artigo = a.id_artigo WHERE date(data_venda) = '$data' ORDER BY a.nome ";
    $resultado = mysqli_query($conexao, $sql);
    $numRegistos = mysqli_num_rows($resultado);
    while($dados = mysqli_fetch_array($resultado)):
       
        $id_venda = $dados['id_venda'];
        $this->Cell(55,10,utf8_decode($dados['artigo']),1,0,'C');
        $this->Cell(17,10,utf8_decode($dados['qtd']),1,0,'C');
        $this->Cell(20,10,number_format($dados['valor'],1,",","."),1,0,'C');
        $this->Cell(20,10,number_format($dados['total'],1,",","."),1,0,'C');
        $this->Cell(37,10,utf8_decode($dados['usuario']),1,0,'C');
        $this->Cell(40,10,$dados['data_venda'],1,1,'C');
        
     endwhile ;
    $sql = "SELECT sum(hv.total) as receita FROM tb_historico_vendas hv inner join tb_saida s on hv.id_check_saida = s.id_saida join tb_usuario u on hv.id_usuario = u.id_usuario 
    join tb_entrada e on s.id_entrada = e.id_entrada 
    join tb_artigo a on e.id_artigo = a.id_artigo WHERE date(data_venda) = '$data'  ";
    $resultado = mysqli_query($conexao, $sql);
    $numRegistos = mysqli_num_rows($resultado);
    $dados = mysqli_fetch_array($resultado);
     $this->Cell(40,10,'Receita :',1,0,'C');
     $this->Cell(40,10, $dados['receita'],1,1,'C');

     //Se for selecionado o repografo
    }elseif(isset($_GET['dado'])) {
       
        $repografo = $_GET['dado'];
        $sql = "SELECT id_venda,id_check_saida,a.nome as artigo, u.nome as usuario,hv.qtd,hv.total,hv.valor,data_venda,dia FROM tb_historico_vendas hv inner join tb_saida s on hv.id_check_saida = s.id_saida join tb_usuario u on hv.id_usuario = u.id_usuario 
        join tb_entrada e on s.id_entrada = e.id_entrada 
        join tb_artigo a on e.id_artigo = a.id_artigo WHERE u.nome = '$repografo' ORDER BY a.nome ";
        $resultado = mysqli_query($conexao, $sql);
        $numRegistos = mysqli_num_rows($resultado);
        while($dados = mysqli_fetch_array($resultado)):
            
            $id_venda = $dados['id_venda'];
            $this->Cell(55,10,utf8_decode($dados['artigo']),1,0,'C');
            $this->Cell(17,10,utf8_decode($dados['qtd']),1,0,'C');
            $this->Cell(20,10,number_format($dados['valor'],1,",","."),1,0,'C');
            $this->Cell(20,10,number_format($dados['total'],1,",","."),1,0,'C');
            $this->Cell(37,10,utf8_decode($dados['usuario']),1,0,'C');
            $this->Cell(40,10,$dados['data_venda'],1,1,'C');
            
            endwhile ;
        $sql = "SELECT sum(hv.total) as receita FROM tb_historico_vendas hv inner join tb_saida s on hv.id_check_saida = s.id_saida join tb_usuario u on hv.id_usuario = u.id_usuario 
        join tb_entrada e on s.id_entrada = e.id_entrada 
        join tb_artigo a on e.id_artigo = a.id_artigo WHERE date(data_venda) = '$data'  ";
        $resultado = mysqli_query($conexao, $sql);
        $numRegistos = mysqli_num_rows($resultado);
        $dados = mysqli_fetch_array($resultado);
            $this->Cell(40,10,'Receita :',1,0,'C');
            $this->Cell(40,10, $dados['receita'],1,1,'C');
    }
    
    $this->Ln(20);
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