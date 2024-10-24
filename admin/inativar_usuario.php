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

// Inativar o usuário
$stmt = $conn->prepare("UPDATE usuarios SET ativo = 0 WHERE id = ?");
$stmt->bind_param("i", $usuario_id);

if ($stmt->execute()) {
    echo "Usuário inativado com sucesso!";
    header("Location: listar_usuarios.php");
} else {
    echo "Erro ao inativar o usuário: " . $conn->error;
}
?>
