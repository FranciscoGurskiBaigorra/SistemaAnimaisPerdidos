<?php
$host = "localhost";
$user = "root"; // usuário do banco
$pass = "";     // senha do banco
$dbname = "mapa_db"; // nome do banco

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}