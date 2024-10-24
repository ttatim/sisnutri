<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Atendimento - SISNUTRI</title>
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
            text-align: center;
        }
        .container h2 {
            margin-bottom: 20px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: white;
            color: black;
            border: 2px solid #4169E1;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .btn:hover {
            background-color: #4169E1;
            color: white;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Painel de Atendimento - SISNUTRI</h1>
    </div>

    <div class="container">
        <h2>Selecione uma Opção</h2>

        <a href="cadastro_paciente.php" class="btn">Cadastro de Paciente</a>
        <a href="evolucao.php" class="btn">Evolução de Paciente</a>
        <a href="relatorio.php" class="btn">Relatório</a>
    </div>

</body>
</html>
