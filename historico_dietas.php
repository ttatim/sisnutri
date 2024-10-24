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

// Recuperar histórico de dietas do paciente selecionado
$historico_dietas = [];
if (isset($_GET['paciente_id'])) {
    $paciente_id = $_GET['paciente_id'];
    $stmt = $conn->prepare("SELECT * FROM dietas WHERE paciente_id = ? ORDER BY data_dieta DESC");
    $stmt->bind_param("i", $paciente_id);
    $stmt->execute();
    $historico_dietas = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SISNUTRI - Histórico de Dietas</title>
</head>
<body>
    <div class="header">
        <img src="img/logo.png" alt="Logo">
        <h1>SISNUTRI</h1>
    </div>

    <div class="container">
        <h2>Histórico de Dietas</h2>
        <div class="form-container">
            <form>
                <label for="paciente_id">Selecione o Paciente:</label>
                <select name="paciente_id" onchange="location = this.value;">
                    <option value="">-- Selecione o Paciente --</option>
                    <?php while ($paciente = $pacientes->fetch_assoc()) { ?>
                        <option value="historico_dietas.php?paciente_id=<?php echo $paciente['id']; ?>"
                            <?php if (isset($_GET['paciente_id']) && $_GET['paciente_id'] == $paciente['id']) echo 'selected'; ?>>
                            <?php echo $paciente['nome_completo']; ?>
                        </option>
                    <?php } ?>
                </select>
            </form>
        </div>

        <?php if (!empty($historico_dietas)) { ?>
        <div class="historico-container">
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Alimentos</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($dieta = $historico_dietas->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($dieta['data_dieta'])); ?></td>
                        <td><?php echo $dieta['alimentos']; ?></td>
                        <td><a href="gerar_pdf.php?id=<?php echo $dieta['id']; ?>" target="_blank">Gerar PDF</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
</body>
</html>
