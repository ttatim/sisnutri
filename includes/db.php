<?php
// Configurações do banco de dados
$host = 'localhost';      // Endereço do servidor MySQL
$dbname = 'sisnutri';     // Nome do banco de dados
$username = 'root';       // Nome de usuário do MySQL
$password = '';           // Senha do MySQL

// Criando a conexão com o banco de dados
$conn = new mysqli($host, $username, $password, $dbname);

// Verificando se houve erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Definir o charset como UTF-8 para evitar problemas com caracteres especiais
$conn->set_charset("utf8");

// Função para evitar SQL Injection
function sanitize($data, $conn) {
    return mysqli_real_escape_string($conn, trim($data));
}
?>
