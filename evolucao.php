<?php
// Verificar sessão
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include 'includes/db.php';

// Inserção de evolução
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paciente_id = $_POST['paciente_id'];
    $observacao = $_POST['observacao'];
    $data_evolucao = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO evolucoes (paciente_id, data_evolucao, observacao) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $paciente_id, $data_evolucao, $observacao);
    $stmt->execute();
}

// Recuperar pacientes para o select
$pacientes = $conn->query("SELECT id, nome_completo FROM pacientes ORDER BY nome_completo ASC");

// Recuperar histórico de evoluções do paciente selecionado
$historico_evolucoes = [];
if (isset($_GET['paciente_id'])) {
    $paciente_id = $_GET['paciente_id'];
    $stmt = $conn->prepare("SELECT * FROM evolucoes WHERE paciente_id = ? ORDER BY data_evolucao DESC");
    $stmt->bind_param("i", $paciente_id);
    $stmt->execute();
    $historico_evolucoes = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SISNUTRI - Evolução do Paciente</title>
</head>
<body>
    <div class="header">
        <img src="img/logo.png" alt="Logo">
        <h1>SISNUTRI</h1>
    </div>

    <div class="container">
        <h2>Evolução do Paciente</h2>
        <div class="form-container">
            <form method="POST">
                <label for="paciente_id">Selecione o Paciente:</label>
                <select name="paciente_id" required onchange="location = this.value;">
                    <option value="">-- Selecione o Paciente --</option>
                    <?php while ($paciente = $pacientes->fetch_assoc()) { ?>
                        <option value="evolucao.php?paciente_id=<?php echo $paciente['id']; ?>"
                            <?php if (isset($_GET['paciente_id']) && $_GET['paciente_id'] == $paciente['id']) echo 'selected'; ?>>
                            <?php echo $paciente['nome_completo']; ?>
                        </option>
                    <?php } ?>
                </select>

                <?php if (isset($_GET['paciente_id'])) { ?>
                <label for="observacao">Observação da Evolução:</label>
                <textarea name="observacao" required></textarea>

                <button type="submit">Salvar Evolução</button>
                <?php } ?>
            </form>
        </div>

        <?php if (!empty($historico_evolucoes)) { ?>
        <div class="historico-container">
            <h3>Histórico de Evoluções</h3>
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Observação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($evolucao = $historico_evolucoes->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($evolucao['data_evolucao'])); ?></td>
                        <td><?php echo $evolucao['observacao']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>

    <style>
        .form-container {
            float: left;
            width: 50%;
        }

        .historico-container {
            float: right;
            width: 45%;
            background-color: #f4f4f4;
            padding: 10px;
            border: 1px solid #ccc;
            max-height: 400px;
            overflow-y: auto;
        }

        textarea {
            width: 100%;
            height: 100px;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: royalblue;
            color: white;
        }
    </style>
</body>
</html>
