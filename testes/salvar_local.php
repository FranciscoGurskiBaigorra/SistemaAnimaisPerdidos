<?php
// salvar_local.php
$servername = "localhost";
$username = "root"; // ajuste conforme seu ambiente
$password = "";
$dbname = "mapa_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$nome = $_POST['nome'];
$especie = $_POST['especie'];
$situacao = $_POST['situacao'];
$descricao = $_POST['descricao'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];

$sql = "INSERT INTO animais (nome, especie, situacao, descricao, latitude, longitude)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssdd", $nome, $especie, $situacao, $descricao, $lat, $lng);

if ($stmt->execute()) {
    echo "Animal cadastrado e ponto salvo com sucesso!";
} else {
    echo "Erro ao salvar: " . $conn->error;
}

$stmt->close();
$conn->close();
?>