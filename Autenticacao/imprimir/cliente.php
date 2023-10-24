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
        $this->SetFont('Arial','B',12);
        $this->Cell(0,5,'',0,1,'C');
        $this->Cell(0,5,'FRANCYCARROS, LDA',0,1,'C');
        $this->SetFont('Arial','B',8);
        $this->Cell(0,7,'',0,1,'C');
        $conexao = mysqli_connect("localhost","root","","francycarros");  
        if (isset($_GET['id'])) {
            $id = mysqli_escape_string($conexao,$_GET['id']);
            //Consulta no banco para mostar os dados do motorista
            $EstadoCliente="Apagado";
            $sql="SELECT cl.UsuarioID,ClienteID,Nome,CartaConducao,EstadoUsuario,Telefone,Endereco,NumDocumento,Documento,Email FROM clientes cl inner join usuarios u on cl.UsuarioID = u.UsuarioID WHERE ClienteID = '$id'";
            $query = mysqli_query($conexao,$sql);
            $dados=mysqli_fetch_array($query);
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
          }
        $this->SetFont('Arial','B',12);//tipo de letra do cabeçalho da tabela, B negritado
        $this->SetTextColor(0, 0, 255);
        $this->Cell(22,10,utf8_decode(''),0,1,'C');
        $this->Cell(0,10,utf8_decode($Nome),1,1,'C');
        
        //Corpo do COnteudo a apresentar sobre o veiculo a visualizar
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial','B',14);
        $this->Cell(40,20,(''),0,1,'R');

        $this->SetFont('Arial','B',12);
        $this->Cell(50,10,utf8_decode('Documento'),0,0,'L');
        $this->SetFont('Arial','',12);
        $this->Cell(140,10,utf8_decode($Documento),0,1,'L');

        $this->SetFont('Arial','B',12);
        $this->Cell(50,10,utf8_decode('Nº Documento'),0,0,'L');
        $this->SetFont('Arial','',12);
        $this->Cell(140,10,utf8_decode($NumDocumento),0,1,'L');

        $this->SetFont('Arial','B',12);
        $this->Cell(50,10,utf8_decode('Carta de Condução'),0,0,'L');
        $this->SetFont('Arial','',12);
        $this->Cell(140,10,utf8_decode($CartaConducao),0,1,'L');

        $this->SetFont('Arial','B',12);
        $this->Cell(50,10,('Telefone'),0,0,'L');
        $this->SetFont('Arial','',12);
        $this->Cell(140,10,($Telefone),0,1,'L');

        $this->SetFont('Arial','B',12);
        $this->Cell(50,10,('Email'),0,0,'L');
        $this->SetFont('Arial','',12);
        $this->Cell(140,10,($Email),0,1,'L');

        $this->SetFont('Arial','B',12);
        $this->Cell(50,10,utf8_decode('Endereço'),0,0,'L');
        $this->SetFont('Arial','',12);
        $this->Cell(140,10,($Endereco),0,1,'L');

        $this->SetFont('Arial','B',12);
        $this->Cell(50,10,utf8_decode('Situação'),0,0,'L');
        $this->SetFont('Arial','',12);
        $this->Cell(140,10,($Estadousuario == 'Activo' ? "Activo":"Inactivo"),0,1,'L');
        
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
    }

}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->Output();
?>