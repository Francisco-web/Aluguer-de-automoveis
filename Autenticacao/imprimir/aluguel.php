<?php


require('fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('../assets/img/logo.png',20,6,10);
        
        // Move to the right
        $this->Cell(80);
        // Arial bold 15
        $this->SetFont('Arial','B',16);
        // Title
        $this->Cell(0,10,'',0,1,'R');
        $this->Cell(0,10,'',0, 1,'R');
        // Arial bold 15
        $this->SetFont('Arial','B',12);
        $this->Cell(0,5,'FRANCYCARROS, LDA',0,1,'C');
        $this->SetFont('Arial','B',8);
        $this->Cell(0,7,'',0,1,'C');
        $this->Cell(0,5,'MORRO BENTO, AVENIDA 21 DE JANEIRO',0,1,'L');
        $this->Cell(0,5,'GAMEK A DIREITA, RUA DA FARMHOUSE',0,1,'L');
        $this->Cell(0,5,utf8_decode('TELEF: 222 401 231/ FAX: 222 442 751/ MÓVEL: 926 774 627'),0,1,'L');
        $this->Cell(0,5,utf8_decode('CONTRIBUINT Nº 5403075232'),0,1,'L');
        $this->Cell(0,5,utf8_decode(''),0,1,'C');
        $conexao = mysqli_connect("localhost","root","","francycarros");  
        if (isset($_GET['id'])) {
            $id = 17;
            
        $sql="SELECT alu.AluguelID,ValorDiaria,Lugar,Porta,cr.Imagem,Ano,Placa,Disponivel,Conforto,Bagageira,MotorSeguranca,Modelo,LocalRetirada,DataRetirada,LocalDevolucao,DataDevolucao,valorTotal,cl.Nome as cliente,mt.Nome as motorista FROM alugueis alu inner join carros cr on alu.CarroID = cr.CarroID join clientes cl on alu.ClienteID = cl.ClienteID join motoristas mt on alu.MotoristaID = mt.MotoristaID WHERE alu.AluguelID = '$id'";
        $query = mysqli_query($conexao,$sql);
        $dados = mysqli_fetch_array($query);
        $Imagem = $dados['Imagem'];
        $Modelo = $dados['Modelo'];
        $Ano = $dados['Ano'];
        $Placa = $dados['Placa'];
        $Disponivel = $dados['Disponivel'];
        $ValorDiaria = $dados['ValorDiaria'];
        $Lugar = $dados['Lugar'];
        $Bagageira = $dados['Bagageira'];
        $Conforto = $dados['Conforto'];
        $Porta = $dados['Porta'];
        $MotorSeguranca = $dados['MotorSeguranca'];
        }
        $this->SetFont('Arial','B',12);//tipo de letra do cabeçalho da tabela, B negritado
        $this->SetTextColor(0, 0, 255);
        $this->Cell(22,10,utf8_decode(''),0,1,'C');
        $this->Cell(0,10,utf8_decode($Modelo),1,1,'C');
        
        //Corpo do COnteudo a apresentar sobre o veiculo a visualizar
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0,63,(''),1,1,'C');
        $this->Image('../imagens/carros/'.$Imagem,60,88,90);

        $this->SetTextColor(0, 0, 255);
        $this->Cell(50,10,('Ano'),1,0,'L');
        $this->SetTextColor(0, 0, 0);
        $this->Cell(140,10,($Ano),1,1,'L');

        $this->SetTextColor(0, 0, 255);
        $this->Cell(50,10,('Placa'),1,0,'L');
        $this->SetTextColor(0, 0, 0);
        $this->Cell(140,10,($Placa),1,1,'L');

        $this->SetTextColor(0, 0, 255);
        $this->Cell(50,10,('Lugares'),1,0,'L');
        $this->SetTextColor(0, 0, 0);
        $this->Cell(140,10,($Lugar),1,1,'L');

        $this->SetTextColor(0, 0, 255);
        $this->Cell(50,10,('Portas'),1,0,'L');
        $this->SetTextColor(0, 0, 0);
        $this->Cell(140,10,($Porta),1,1,'L');

        $this->SetTextColor(0, 0, 255);
        $this->Cell(50,10,('Bagageira'),1,0,'L');
        $this->SetTextColor(0, 0, 0);
        $this->Cell(140,10,utf8_decode($Bagageira),1,1,'L');

        $this->SetTextColor(0, 0, 255);
        $this->Cell(50,10,utf8_decode('Motor e Segurança'),1,0,'L');
        $this->SetTextColor(0, 0, 0);
        $this->Cell(140,10,utf8_decode($MotorSeguranca),1,1,'L');
        
        $this->SetTextColor(0, 0, 255);
        $this->Cell(50,10,('Conforto'),1,0,'L');
        $this->SetTextColor(0, 0, 0);
        $this->Cell(140,10,utf8_decode($Conforto),1,1,'L');

        $this->SetTextColor(0, 0, 255);
        $this->Cell(50,10,(''),0,1,'L');
        $this->Cell(50,10,utf8_decode('Valor Diária'),1,0,'L');
        $this->SetTextColor(0, 0, 0);
        $this->Cell(140,10,number_format($ValorDiaria,2,",","."),1,1,'L');

        $this->SetTextColor(0, 0, 255);
        $this->Cell(50,10,utf8_decode('Disponível'),1,0,'L');
        $this->SetTextColor(0, 0, 0);
        $this->Cell(140,10,($Disponivel == 1 ? "Sim":"Não"),1,1,'L');
        
        $this->Ln(20);
    }
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','',10);
        $this->Cell(28,10,utf8_decode('FrancyCarros,Lda'),0,0,'L');
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