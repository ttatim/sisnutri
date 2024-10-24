<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

include '../includes/db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receber e sanitizar os dados do formulário
    $nome_paciente = sanitize($_POST['nome_paciente'], $conn);
    $cpf_paciente = sanitize($_POST['cpf_paciente'], $conn);
    $nome_profissional = sanitize($_POST['nome_profissional'], $conn);
    $cpf_profissional = sanitize($_POST['cpf_profissional'], $conn);
    $valor_pago = sanitize($_POST['valor_pago'], $conn);
    $dias_atendimento = sanitize($_POST['dias_atendimento'], $conn);
    $mes_atendimento = sanitize($_POST['mes_atendimento'], $conn);
    $ano_atendimento = sanitize($_POST['ano_atendimento'], $conn);

    // Validar se os campos foram preenchidos
    if (empty($nome_paciente) || empty($cpf_paciente) || empty($nome_profissional) || empty($cpf_profissional) || empty($valor_pago) || empty($dias_atendimento)) {
        $error = "Todos os campos são obrigatórios.";
    } else {
        // Inserir os dados no banco de dados
        $query = "INSERT INTO recibos (nome_paciente, cpf_paciente, nome_profissional, cpf_profissional, valor_pago, dias_atendimento, mes_atendimento, ano_atendimento)
                  VALUES ('$nome_paciente', '$cpf_paciente', '$nome_profissional', '$cpf_profissional', '$valor_pago', '$dias_atendimento', '$mes_atendimento', '$ano_atendimento')";
        
        if ($conn->query($query)) {
            $success = "Recibo cadastrado com sucesso!";
        } else {
            $error = "Erro ao cadastrar recibo: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Recibo - SISNUTRI</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4169E1;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
        }
        .container {
            margin: 50px auto;
            width: 50%;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        input[type="decimal"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4169E1;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .btn:hover {
            background-color: #27408B;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>SISNUTRI - Cadastrar Recibo</h1>
    </div>

    <div class="container">
        <h2>Cadastro de Recibo</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="cadastrar_recibo.php" method="post">
            <div class="form-group">
                <label for="nome_paciente">Nome do Paciente</label>
                <input type="text" id="nome_paciente" name="nome_paciente" required>
            </div>
            <div class="form-group">
                <label for="cpf_paciente">CPF do Paciente</label>
                <input type="text" id="cpf_paciente" name="cpf_paciente" required>
            </div>
            <div class="form-group">
                <label for="nome_profissional">Nome do Profissional</label>
                <input type="text" id="nome_profissional" name="nome_profissional" required>
            </div>
            <div class="form-group">
                <label for="cpf_profissional">CPF do Profissional</label>
                <input type="text" id="cpf_profissional" name="cpf_profissional" required>
            </div>
            <div class="form-group">
                <label for="valor_pago">Valor Pago pelo Atendimento</label>
                <input type="number" step="0.01" id="valor_pago" name="valor_pago" required>
            </div>
            <div class="form-group">
                <label for="dias_atendimento">Dias de Atendimento</label>
                <input type="number" id="dias_atendimento" name="dias_atendimento" required>
            </div>
            <div class="form-group">
                <label for="mes_atendimento">Mês do Atendimento</label>
                <select id="mes_atendimento" name="mes_atendimento" readonly>
                    <?php
                    $mes_atual = date('m');
                    echo "<option value='$mes_atual'>$mes_atual</option>";
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="ano_atendimento">Ano do Atendimento</label>
                <select id="ano_atendimento" name="ano_atendimento" readonly>
                    <?php
                    $ano_atual = date('Y');
                    echo "<option value='$ano_atual'>$ano_atual</option>";
                    ?>
                </select>
            </div>
            <button type="submit" class="btn">Cadastrar Recibo</button>
        </form>

    </div>

</body>
</html>
