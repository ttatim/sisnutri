<?php
// Iniciar a sessão
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'administrador') {
    header('Location: ../login.php');
    exit();
}

include '../includes/db.php';

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $senha = md5($_POST['senha']); // Criptografar a senha com MD5
    $nome_completo = $_POST['nome_completo'];
    $cep = $_POST['cep'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $endereco = $_POST['endereco'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $telefone = $_POST['telefone'];
    $role = $_POST['role']; // Função do usuário (Administrador, Nutricionista, Secretária)

    // Inserir o novo usuário no banco de dados
    $stmt = $conn->prepare("INSERT INTO usuarios (username, senha, nome_completo, cep, cidade, estado, endereco, numero, complemento, telefone, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $username, $senha, $nome_completo, $cep, $cidade, $estado, $endereco, $numero, $complemento, $telefone, $role);
    
    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Cadastrar Usuário - SISNUTRI</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="header">
        <h1>SISNUTRI - Administração</h1>
    </div>

    <div class="container">
        <h2>Cadastrar Usuário</h2>
        <form method="POST" action="cadastrar_usuario.php">
            <label for="username">Nome de Usuário:</label>
            <input type="text" name="username" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>

            <label for="nome_completo">Nome Completo:</label>
            <input type="text" name="nome_completo" required>

            <label for="cep">CEP:</label>
            <input type="text" name="cep" id="cep" maxlength="8" required>

            <label for="cidade">Cidade:</label>
            <input type="text" name="cidade" id="cidade" readonly required>

            <label for="estado">Estado:</label>
            <input type="text" name="estado" id="estado" readonly required>

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" required>

            <label for="numero">Número:</label>
            <input type="text" name="numero" required>

            <label for="complemento">Complemento:</label>
            <input type="text" name="complemento">

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" required>

            <label for="role">Função do Usuário:</label>
            <select name="role" required>
                <option value="administrador">Administrador</option>
                <option value="nutricionista">Nutricionista</option>
                <option value="secretaria">Secretária</option>
            </select>

            <button type="submit">Cadastrar Usuário</button>
        </form>
    </div>

    <script>
        // Função para buscar dados do CEP usando ViaCEP
        $('#cep').on('blur', function() {
            var cep = $(this).val().replace(/\D/g, '');

            if (cep != "") {
                var validacep = /^[0-9]{8}$/;
                if(validacep.test(cep)) {
                    $('#cidade').val('...');
                    $('#estado').val('...');

                    // Consulta ViaCEP
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

    <style>
        /* Estilos simples para o layout */
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            background-color: royalblue;
            color: white;
            padding: 10px;
            text-align: center;
        }

        .container {
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 5px;
            max-width: 600px;
            margin: auto;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }

        button {
            background-color: royalblue;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
        }

        button:hover {
            background-color: navy;
        }
    </style>
</body>
</html>
