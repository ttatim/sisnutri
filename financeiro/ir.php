<?php
session_start();

include '../includes/db.php'; // Arquivo de conexão ao banco de dados
require '../vendor/autoload.php'; // Autoload do Composer (PHPMailer e PhpSpreadsheet)

// Configurações de e-mail
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$admin_email = 'admin@dominio.com'; // Coloque aqui o e-mail do administrador do sistema

// Verificar se estamos no dia 10 de janeiro e se o ano é maior que o ano dos recibos
$currentYear = date('Y');
$currentMonth = date('F');
$currentDay = date('d');

if ($currentMonth == 'January' && $currentDay == '10') {
    // Verificar se há recibos do ano anterior
    $query = "SELECT MAX(ano_atendimento) AS ultimo_ano FROM recibos";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    
    if ($currentYear > $row['ultimo_ano']) {
        $ano_anterior = $currentYear - 1;

        // Buscar recibos do ano anterior
        $query = "SELECT nome_paciente, cpf_paciente, valor_pago FROM recibos WHERE ano_atendimento = $ano_anterior";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Criar um novo arquivo Excel
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Nome do Paciente');
            $sheet->setCellValue('B1', 'CPF do Paciente');
            $sheet->setCellValue('C1', 'Valor Pago');

            $rowCount = 2; // Começar a preencher na segunda linha
            while ($recibo = $result->fetch_assoc()) {
                $sheet->setCellValue('A' . $rowCount, $recibo['nome_paciente']);
                $sheet->setCellValue('B' . $rowCount, $recibo['cpf_paciente']);
                $sheet->setCellValue('C' . $rowCount, number_format($recibo['valor_pago'], 2, ',', '.'));
                $rowCount++;
            }

            // Nome do arquivo
            $nome_arquivo = 'imposto_renda_' . $currentYear . '.xlsx';
            $diretorio = '../imposto_renda/'; // Verifique se o diretório existe e tem permissão de gravação
            $caminho_arquivo = $diretorio . $nome_arquivo;

            // Salvar o arquivo Excel no diretório especificado
            $writer = new Xlsx($spreadsheet);
            $writer->save($caminho_arquivo);

            // Enviar o e-mail com o link para download
            $mail = new PHPMailer(true);

            try {
                // Configurações do servidor de e-mail
                $mail->isSMTP();
                $mail->Host = 'smtp.dominio.com'; // Seu servidor SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'seu-email@dominio.com'; // Seu usuário SMTP
                $mail->Password = 'sua-senha'; // Sua senha SMTP
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Informações do remetente
                $mail->setFrom('seu-email@dominio.com', 'SISNUTRI');
                $mail->addAddress($admin_email);

                // Conteúdo do e-mail
                $mail->isHTML(true);
                $mail->Subject = 'Relatório de Recibos para Imposto de Renda';
                $mail->Body = 'Segue o link para download do relatório de recibos do ano anterior:<br>' .
                              '<a href="http://seu-dominio.com/imposto_renda/' . $nome_arquivo . '">Download do Relatório</a>';

                // Enviar o e-mail
                $mail->send();
                echo 'E-mail enviado com sucesso!';
            } catch (Exception $e) {
                echo 'Erro ao enviar e-mail: ' . $mail->ErrorInfo;
            }
        } else {
            echo "Nenhum recibo encontrado para o ano anterior.";
        }
    } else {
        echo "O ano atual é menor ou igual ao ano dos recibos registrados.";
    }
} else {
    echo "O script só pode ser executado no dia 10 de janeiro.";
}
?>
