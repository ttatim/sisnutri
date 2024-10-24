<?php
// Incluir a biblioteca FPDF
require('vendor/fpdf/fpdf.php');
include 'includes/db.php';

// Verificar se o ID da dieta foi fornecido
if (!isset($_GET['id'])) {
    die("ID da dieta não fornecido.");
}

$dieta_id = $_GET['id'];

// Consultar dados da dieta no banco de dados
$stmt = $conn->prepare("SELECT d.alimentos, d.data_dieta, p.nome_completo FROM dietas d
                        JOIN pacientes p ON d.paciente_id = p.id
                        WHERE d.id = ?");
$stmt->bind_param("i", $dieta_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Dieta não encontrada.");
}

$dieta = $result->fetch_assoc();

// Criar um novo PDF usando FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Cabeçalho do PDF
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Plano Alimentar - SISNUTRI', 0, 1, 'C');
$pdf->Ln(10); // Linha vazia

// Detalhes do paciente
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, 'Paciente: ' . $dieta['nome_completo']);
$pdf->Ln(8);
$pdf->Cell(40, 10, 'Data da Dieta: ' . date('d/m/Y H:i', strtotime($dieta['data_dieta'])));
$pdf->Ln(10); // Linha vazia

// Alimentos
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Alimentos Recomendados:', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 10, $dieta['alimentos']);
$pdf->Ln(10); // Linha vazia

// Rodapé
$pdf->SetFont('Arial', 'I', 10);
$pdf->SetY(-30); // Move to 30mm from bottom
$pdf->Cell(0, 10, 'SISNUTRI - Consultorio de Nutricionista', 0, 0, 'C');

// Saída do PDF para o navegador
$pdf->Output('I', 'dieta_' . $dieta_id . '.pdf');
?>
