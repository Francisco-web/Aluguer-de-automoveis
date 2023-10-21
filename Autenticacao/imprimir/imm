
<?PHP
require "fpdf.php";
$connect = mysqli_connect('localhost', 'root','');
mysqli_select_db($connect,'gestc');

$pdf = new FPDF('p','mm','A4');
$pdf->AddPage();

//Titulo da pagina
$pdf->settitle('comprovativo de pagamento');

//cell (nome da imagem, posição x , posição y, largura opcional, altura opcional)
$pdf->image('../assets/img/logotipo.png' ,95,17,20,17);

//cell(width, height, text, border, em line, {align})

$pdf->cell(100 ,15,'',0,1); //fim da linha

//selecionar font to Arial, bold, 14pt
$pdf->setfont('times', 'B', 'font/times.php');

$pdf->cell(0 ,7,'',0,1,'C');
$pdf->cell(0 ,7,'',0,1,'C');

$pdf->cell(0 ,7,'Colegio do Ensino Primario e do I Ciclo',0,0,'C');
$pdf->cell(0 ,7,'',0,1); 
 //fim da linha

$pdf->cell(0,7,'AUTO ESCOLA',0,1,'C');
$pdf->cell(0,7,'Contribuinte N 2402107049',0,1,'C');
$pdf->cell(0,7,'Diario N 16/III Serie 28/02/002',0,1,'C');
$pdf->cell(0,7,'Alvara N 56/0011',0,1,'C');
$pdf->cell(0,7,'',0,1); //fim da linha

//Mostrar os dados vindo da db
if (isset($_GET['id'])& isset($_GET['matricula'])& isset($_GET['us'])) {
                                        
    $id_pag= mysqli_escape_string($connect, $_GET['id']);
    $matricula = mysqli_escape_string($connect, $_GET['matricula']);
    $sec = mysqli_escape_string($connect, $_GET['us']);

$resultado = mysqli_query($connect,"SELECT * FROM aluno_pag a 
inner join matricula m on a.id_matricula = m.id_matricula join turma t on m.id_turma = t.id_turma join classe c on t.classe = c.id_classe 
join pagamento p on a.id_pag = p.id_pag join inscricao i on m.id_inscricao = i.id_inscricao 
JOIN usuario u on i.id_usuario = u.id_usuario where id_aluno_pag = $id_pag and num_matricula = $matricula ");
while($dados = mysqli_fetch_array($resultado)){

$pdf->cell(0,7,'',0,1,'C');
$pdf->cell(0,7,'Recibo de Pagamento',0,1,'C');
$pdf->cell(20,7,'Recibo N:',1,0,'L');
$pdf->cell(20,7,$dados['num_recibo'],1,1,'C');
$pdf->cell(40,7,'Registado em',1,0,'C');
$pdf->cell(40,7,$dados['data_pag'],1,0,'C');
$pdf->cell(0,7,$dados['nome_usuario'],1,1,'C');
$pdf->cell(0,7,$dados['num_matricula'],1,1,'C');
$pdf->cell(0,7,'',0,1,'R');// fim da linha

//fonte do corpo
$pdf->setfont('times', '', 'font/times.php');

$pdf->cell(15 ,7,$dados['nome_classe'],0,0,'L');
$pdf->cell(33 ,7,'',0,0,'C');

$pdf->cell(15 ,7,'Turma:',0,0,'L');
$pdf->cell(33,7,$dados['nome_turma'],0,0,'L');
$pdf->cell(15 ,7,'Turno:',0,0,'L');
$pdf->cell(33,7,$dados['periodo'],0,0,'L');
$pdf->cell(15 ,7,'Sala:',0,0,'L');
$pdf->cell(33,7,$dados['num_sala'],0,1,'L');
$pdf->cell(33,7,'',0,1,'L');

$pdf->cell(63,7,'Emolumento',0,0,'L');
$pdf->cell(45 ,7,'',0,0,'L');
$pdf->cell(63 ,7,'Valor',0,1,'R');
$pdf->cell(63 ,7,'-----------------------------',0,0,'L');
$pdf->cell(45 ,7,'',0,0,'L');
$pdf->cell(63 ,7,'-----------------------------',0,1,'R');
$pdf->cell(63 ,7,$dados['tipo_p'],0,0,'L');
$pdf->cell(45 ,7,$dados['mes_pag'],0,0,'L');
$pdf->cell(63 ,7,$dados['valor_pag'],0,1,'R');
$pdf->cell(63 ,7,'-----------------------------',0,0,'L');
$pdf->cell(45 ,7,'',0,0,'L');
$pdf->cell(63 ,7,'-----------------------------',0,1,'R');
$pdf->cell(63 ,7,'Total(Kwanza)',0,0,'L');
$pdf->cell(45 ,7,'',0,0,'L');
$pdf->cell(63 ,7,$dados['valor_pag']++,0,1,'R');
$pdf->cell(63 ,7,'',0,1,'R');

$pdf->cell(57 ,7,'Nao Sera Efectuado reembolsos',0,1,'L');
$pdf->cell(25,7,'Impresso em:',0,0,'L');
$pdf->cell(0 ,7,$dados['data_pag'],0,1,'L'); 
}
}
if (isset($_GET['id'])& isset($_GET['matricula'])& isset($_GET['us'])) {
                                        
    $id_pag= mysqli_escape_string($connect, $_GET['id']);
    $matricula = mysqli_escape_string($connect, $_GET['matricula']);
    $sec = mysqli_escape_string($connect, $_GET['us']);

$resultado = mysqli_query($connect,"SELECT * FROM aluno_pag a 
inner join secretaria s on a.id_secretaria = s.id_secretaria JOIN usuario u on s.id_usuario = u.id_usuario where id_aluno_pag = $id_pag ");
while($dados = mysqli_fetch_array($resultado)){

$pdf->cell(27 ,7,'Registado por:',0,0,'L');
$pdf->cell(63 ,7,$dados['nome_usuario'],0,1,'L');
$pdf->cell(0 ,7,'',0,1,'L'); 
}}

$pdf->cell(0 ,7,'-- Processado por computador --',0,1,'C');// fim da linha
$pdf->cell(35 ,7,'',0,1);
$pdf->cell(65 ,7,'',0,0);

//footer- rodape
$pdf->footer(-15);
$pdf->SetFont('courier','','font/courier.php');
$pdf->Cell(0,0,'',0,0,'C');

$pdf->AliasNbPages();
$pdf->Output();

?>