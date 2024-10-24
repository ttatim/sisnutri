<?php
session_start();

// Verificar se o usuário está logado e se é administrador
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'administrador') {
    header('Location: ../login.php');
    exit();
}

include '../includes/db.php';

// Verificar se a ação foi passada via GET
if (!isset($_GET['acao'])) {
    die("Ação não definida.");
}

$acao = $_GET['acao'];

// Consultar todos os usuários
$query = "SELECT * FROM usuarios";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "Nenhum usuário encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Usuários - SISNUTRI</title>
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
            width: 80%;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #4169E1;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #27408B;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>SISNUTRI - Listar Usuários</h1>
    </div>

    <div class="container">
        <h2>Usuários Cadastrados</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome Completo</th>
                    <th>Função</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nome_completo']; ?></td>
                        <td><?php echo ucfirst($row['role']); ?></td>
                        <td><?php echo $row['ativo'] ? 'Ativo' : 'Inativo'; ?></td>
                        <td>
                            <?php if ($acao == 'editar'): ?>
                                <a href="editar_usuario.php?id=<?php echo $row['id']; ?>" class="btn">Editar</a>
                            <?php elseif ($acao == 'excluir'): ?>
                                <a href="excluir_usuario.php?id=<?php echo $row['id']; ?>" class="btn" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                            <?php elseif ($acao == 'inativar'): ?>
                                <?php if ($row['ativo']): ?>
                                    <a href="inativar_usuario.php?id=<?php echo $row['id']; ?>" class="btn" onclick="return confirm('Tem certeza que deseja inativar este usuário?')">Inativar</a>
                                <?php else: ?>
                                    <span class="btn" style="background-color: grey; cursor: not-allowed;">Inativo</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="painel.php" class="btn">Voltar ao Painel</a>
    </div>

</body>
</html>
