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

// Excluir o usuário do banco de dados
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);

if ($stmt->execute()) {
    echo "Usuário excluído com sucesso!";
    header("Location: listar_usuarios.php"); // Redireciona para a lista de usuários
} else {
    echo "Erro ao excluir o usuário: " . $conn->error;
}
?>
