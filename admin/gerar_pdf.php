<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

include '../includes/db.php';
require('../vendor/fpdf/fpdf.php');

// Verificar se o ID do recibo foi enviado
if (!isset($_POST['recibo_id'])) {
    die("Recibo não selecionado.");
}

$recibo_id = $_POST['recibo_id'];

// Buscar as informações do recibo selecionado
$query = "SELECT * FROM recibos WHERE id = $recibo_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die("Recibo não encontrado.");
}

$recibo = $result->fetch_assoc();

// Criar o PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Cabeçalho
$pdf->Cell(0, 10, 'Recibo de Atendimento', 0, 1, 'C');
$pdf->Ln(10);

// Informações do Profissional
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Nome do Profissional: ', 0, 0);
$pdf->Cell(50, 10, $recibo['nome_profissional'], 0, 1);
$pdf->Cell(50, 10, 'CPF do Profissional: ', 0, 0);
$pdf->Cell(50, 10, $recibo['cpf_profissional'], 0, 1);
$pdf->Ln(10);

// Informações do Paciente
$pdf->Cell(50, 10, 'Nome do Paciente: ', 0, 0);
$pdf->Cell(50, 10, $recibo['nome_paciente'], 0, 1);
$pdf->Cell(50, 10, 'CPF do Paciente: ', 0, 0);
$pdf->Cell(50, 10, $recibo['cpf_paciente'], 0, 1);
$pdf->Ln(10);

// Informações do Atendimento
$pdf->Cell(50, 10, 'Valor Pago: ', 0, 0);
$pdf->Cell(50, 10, 'R$ ' . number_format($recibo['valor_pago'], 2, ',', '.'), 0, 1);
$pdf->Cell(50, 10, 'Dias de Atendimento: ', 0, 0);
$pdf->Cell(50, 10, $recibo['dias_atendimento'], 0, 1);
$pdf->Cell(50, 10, 'Data de Atendimento: ', 0, 0);
$pdf->Cell(50, 10, $recibo['mes_atendimento'] . '/' . $recibo['ano_atendimento'], 0, 1);

$pdf->Ln(20);
$pdf->Cell(0, 10, 'Assinatura do Profissional', 0, 1, 'C');

// Gera e abre o PDF
$pdf->Output();
?>
