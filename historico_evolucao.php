<?php
// Verificar sessão
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include 'includes/db.php';

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
    <title>SISNUTRI - Histórico de Evoluções</title>
</head>
<body>
    <div class="header">
        <img src="img/logo.png" alt="Logo">
        <h1>SISNUTRI</h1>
    </div>

    <div class="container">
        <h2>Histórico de Evoluções</h2>
        <div class="form-container">
            <form>
                <label for="paciente_id">Selecione o Paciente:</label>
                <select name="paciente_id" onchange="location = this.value;">
                    <option value="">-- Selecione o Paciente --</option>
                    <?php while ($paciente = $pacientes->fetch_assoc()) { ?>
                        <option value="historico_evolucao.php?paciente_id=<?php echo $paciente['id']; ?>"
                            <?php if (isset($_GET['paciente_id']) && $_GET['paciente_id'] == $paciente['id']) echo 'selected'; ?>>
                            <?php echo $paciente['nome_completo']; ?>
                        </option>
                    <?php } ?>
                </select>
            </form>
        </div>

        <?php if (!empty($historico_evolucoes)) { ?>
        <div class="historico-container">
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
</body>
</html>
