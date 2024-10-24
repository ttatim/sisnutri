<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

include '../includes/db.php';
require('../fpdf/fpdf.php');

$error = '';
$recibos = [];

// Quando o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_paciente = sanitize($_POST['nome_paciente'], $conn);

    // Buscar recibos pelo nome do paciente
    $query = "SELECT * FROM recibos WHERE nome_paciente LIKE '%$nome_paciente%'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $recibos[] = $row;
        }
    } else {
        $error = "Nenhum recibo encontrado para o paciente: " . $nome_paciente;
    }
}

// Função para sanitizar a entrada
function sanitize($data, $conn) {
    return htmlspecialchars(mysqli_real_escape_string($conn, $data));
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Recibo - SISNUTRI</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .container {
            margin: 50px auto;
            width: 50%;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4169E1;
            color: white;
            border: none;
            text-align: center;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #27408B;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Buscar Recibo para Impressão</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="imprimir_recibo.php" method="post">
            <div class="form-group">
                <label for="nome_paciente">Nome do Paciente</label>
                <input type="text" id="nome_paciente" name="nome_paciente" required>
            </div>
            <button type="submit" class="btn">Buscar Recibos</button>
        </form>

        <?php if (!empty($recibos)): ?>
            <h3>Recibos Encontrados</h3>
            <form action="gerar_pdf.php" method="post" target="_blank">
                <div class="form-group">
                    <label for="recibo_selecionado">Selecione o Recibo</label>
                    <select name="recibo_id" id="recibo_selecionado" required>
                        <?php foreach ($recibos as $recibo): ?>
                            <option value="<?php echo $recibo['id']; ?>">
                                <?php echo $recibo['nome_paciente'] . ' - ' . $recibo['mes_atendimento'] . '/' . $recibo['ano_atendimento']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn">Gerar PDF</button>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>
