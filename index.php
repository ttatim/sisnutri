<?php
// Conexão com o banco
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar o login
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = $username;
            header('Location: painel_atendimento.php');
            exit();
        } else {
            $erro = "Login ou senha incorretos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>SISNUTRI - Login</title>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <?php if(isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
    </div>
</body>
</html>
