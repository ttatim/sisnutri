<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'administrador') {
    header('Location: ../login.php');
    exit();
}

include '../includes/db.php';

// Verificar se o ID do usuário foi fornecido
if (!isset($_GET['id'])) {
    die("ID do usuário não fornecido.");
}

$usuario_id = $_GET['id'];

// Consultar dados do usuário no banco de dados
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Usuário não encontrado.");
}

$usuario = $result->fetch_assoc();

// Atualizar os dados se o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_completo = $_POST['nome_completo'];
    $cep = $_POST['cep'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $endereco = $_POST['endereco'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $telefone = $_POST['telefone'];
    $role = $_POST['role'];

    // Atualizar a senha somente se o campo for preenchido
    if (!empty($_POST['senha'])) {
        $senha = md5($_POST['senha']);
        $stmt = $conn->prepare("UPDATE usuarios SET nome_completo = ?, senha = ?, cep = ?, cidade = ?, estado = ?, endereco = ?, numero = ?, complemento = ?, telefone = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssssssssi", $nome_completo, $senha, $cep, $cidade, $estado, $endereco, $numero, $complemento, $telefone, $role, $usuario_id);
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET nome_completo = ?, cep = ?, cidade = ?, estado = ?, endereco = ?, numero = ?, complemento = ?, telefone = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssssssssi", $nome_completo, $cep, $cidade, $estado, $endereco, $numero, $complemento, $telefone, $role, $usuario_id);
    }

    if ($stmt->execute()) {
        echo "Usuário atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o usuário: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Editar Usuário - SISNUTRI</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="header">
        <h1>SISNUTRI - Editar Usuário</h1>
    </div>

    <div class="container">
        <h2>Editar Usuário</h2>
        <form method="POST" action="editar_usuario.php?id=<?php echo $usuario_id; ?>">
            <label for="nome_completo">Nome Completo:</label>
            <input type="text" name="nome_completo" value="<?php echo $usuario['nome_completo']; ?>" required>

            <label for="senha">Nova Senha (deixe em branco para manter):</label>
            <input type="password" name="senha">

            <label for="cep">CEP:</label>
            <input type="text" name="cep" id="cep" value="<?php echo $usuario['cep']; ?>" maxlength="8" required>

            <label for="cidade">Cidade:</label>
            <input type="text" name="cidade" id="cidade" value="<?php echo $usuario['cidade']; ?>" readonly required>

            <label for="estado">Estado:</label>
            <input type="text" name="estado" id="estado" value="<?php echo $usuario['estado']; ?>" readonly required>

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" value="<?php echo $usuario['endereco']; ?>" required>

            <label for="numero">Número:</label>
            <input type="text" name="numero" value="<?php echo $usuario['numero']; ?>" required>

            <label for="complemento">Complemento:</label>
            <input type="text" name="complemento" value="<?php echo $usuario['complemento']; ?>">

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" value="<?php echo $usuario['telefone']; ?>" required>

            <label for="role">Função do Usuário:</label>
            <select name="role" required>
                <option value="administrador" <?php if ($usuario['role'] == 'administrador') echo 'selected'; ?>>Administrador</option>
                <option value="nutricionista" <?php if ($usuario['role'] == 'nutricionista') echo 'selected'; ?>>Nutricionista</option>
                <option value="secretaria" <?php if ($usuario['role'] == 'secretaria') echo 'selected'; ?>>Secretária</option>
            </select>

            <button type="submit">Atualizar Usuário</button>
        </form>
    </div>

    <script>
        $('#cep').on('blur', function() {
            var cep = $(this).val().replace(/\D/g, '');

            if (cep != "") {
                var validacep = /^[0-9]{8}$/;
                if(validacep.test(cep)) {
                    $('#cidade').val('...');
                    $('#estado').val('...');

                    $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {
                        if (!("erro" in dados)) {
                            $('#cidade').val(dados.localidade);
                            $('#estado').val(dados.uf);
                        } else {
                            alert("CEP não encontrado.");
                            $('#cidade').val('');
                            $('#estado').val('');
                        }
                    });
                } else {
                    alert("Formato de CEP inválido.");
                    $('#cidade').val('');
                    $('#estado').val('');
                }
            }
        });
    </script>
</body>
</html>
