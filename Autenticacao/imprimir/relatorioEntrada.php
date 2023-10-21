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
    $this->Cell(0,10,utf8_decode('Entrada de Artigos no Stocks da Repográfia'),0,1,'C');
    $this->Cell(168,10,utf8_decode('Data Doc.'),0,0,'R');
    $this->SetFont('Arial','',10);
    $data = date('Y.m.d');
    $this->Cell(0,10,utf8_decode($data),0,1,'C');
    $this->Cell(0,5,utf8_decode(''),0,1,'C');
   
    
    $this->SetFont('Arial','B',10);//tipo de letra do cabeçalho da tabela, B negritado
    $this->Cell(15,10,utf8_decode('ID'),1,0,'C');
    $this->Cell(50,10,utf8_decode('Artigo'),1,0,'C');
    $this->Cell(10,10,utf8_decode('Un.'),1,0,'C');
    $this->Cell(17,10,utf8_decode('Qtd.'),1,0,'C');
    $this->Cell(17,10,utf8_decode('Preço'),1,0,'C');
    $this->Cell(53,10,utf8_decode('Descrição'),1,0,'C');
    $this->Cell(28,10,utf8_decode('Valor Liquido'),1,1,'C');
    //Incluir a conexão com o banco de dados
    $conexao = mysqli_connect("localhost","root","","repografia");         
    $estadoDesactivo = 7;
    $data = $_GET['data'];
    $sql = "SELECT a.id_artigo,e.id_entrada,unidade,a.nome as artigo,descricao_artigo,tipo_artigo,qtd_entrada_fix,qtd_entrada_mov,dataRegisto,estado_entrada,u.nome as usuario,valor_fix FROM tb_entrada e inner join tb_artigo a on e.id_artigo = a.id_artigo join tb_tipo_artigo ta on a.id_tipo_artigo = ta.id_tipo_artigo join tb_usuario u on e.id_usuario = u.id_usuario 
    WHERE estado_entrada < '$estadoDesactivo' and date(dataRegisto)= '$data' ORDER BY a.nome ";
    $resultado = mysqli_query($conexao, $sql);
    $numRegistos = mysqli_num_rows($resultado);
    while($dados = mysqli_fetch_array($resultado)):
    
    $this->Cell(15,10,utf8_decode($dados['id_artigo']),1,0,'C');
    $this->Cell(50,10,utf8_decode($dados['artigo']),1,0,'C');
    $this->Cell(10,10,utf8_decode($dados['unidade']),1,0,'C');
    $this->Cell(17,10,utf8_decode($dados['qtd_entrada_fix']),1,0,'C');
    $this->Cell(17,10,utf8_decode($dados['valor_fix']),1,0,'C');
    $this->Cell(53,10,utf8_decode($dados['descricao_artigo']),1,0,'C');
    $valorLiquido = $dados['qtd_entrada_fix'] * $dados['valor_fix']; 
    $this->Cell(28,10,utf8_decode($valorLiquido),1,1,'C');
    
    endwhile;
    //mostrar total
    $sql = "SELECT SUM(qtd_entrada_fix * valor_fix) as Receita, a.id_artigo,e.id_entrada,unidade,a.nome as artigo,descricao_artigo,tipo_artigo,qtd_entrada_fix,qtd_entrada_mov,dataRegisto,estado_entrada,u.nome as usuario,valor_fix FROM tb_entrada e inner join tb_artigo a on e.id_artigo = a.id_artigo join tb_tipo_artigo ta on a.id_tipo_artigo = ta.id_tipo_artigo join tb_usuario u on e.id_usuario = u.id_usuario 
    WHERE estado_entrada < '$estadoDesactivo' and date(dataRegisto)= '$data' ORDER BY a.nome ";
    $resultado = mysqli_query($conexao, $sql);
    $dados = mysqli_fetch_array($resultado);
    $this->Cell(0,2,utf8_decode(''),0,1,'C');
    $this->Cell(145,10,utf8_decode('Num. Artigos'),0,0,'R'); 
    $this->Cell(38,10,utf8_decode($numRegistos),0,1,'R');
    $this->Cell(145,10,utf8_decode('TOTAL'),0,0,'R'); 
    $this->Cell(38,10,number_format($dados['Receita'],2,",","."),0,0,'R');
    $this->Cell(7,10,utf8_decode('Kz'),0,0,'L'); 
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