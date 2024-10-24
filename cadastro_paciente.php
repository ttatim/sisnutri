<?php
// Verificar sessão
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_completo = $_POST['nome_completo'];
    $data_nascimento = $_POST['data_nascimento'];
    $idade = date_diff(date_create($data_nascimento), date_create('today'))->y;
    $cep = $_POST['cep'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $endereco = $_POST['endereco'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];

    $stmt = $conn->prepare("INSERT INTO pacientes (nome_completo, data_nascimento, idade, cep, cidade, estado, endereco, numero, complemento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissssss", $nome_completo, $data_nascimento, $idade, $cep, $cidade, $estado, $endereco, $numero, $complemento);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SISNUTRI - Cadastro de Paciente</title>
</head>
<body>
    <div class="header">
        <img src="img/logo.png" alt="Logo">
        <h1>SISNUTRI</h1>
    </div>
    <div class="container">
        <h2>Cadastro de Paciente</h2>
        <form method="POST">
            <input type="text" name="nome_completo" placeholder="Nome Completo" required>
            <input type="date" name="data_nascimento" placeholder="Data de Nascimento" required>
            <input type="text" name="cep" placeholder="CEP" required id="cep">
            <input type="text" name="cidade" placeholder="Cidade" id="cidade" readonly>
            <input type="text" name="estado" placeholder="Estado" id="estado" readonly>
            <input type="text" name="endereco" placeholder="Endereço" required>
            <input type="text" name="numero" placeholder="Número" required>
            <input type="text" name="complemento" placeholder="Complemento">
            <button type="submit">Cadastrar</button>
        </form>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
