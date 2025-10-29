<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mapa_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$sql = "SELECT * FROM animais";
$result = $conn->query($sql);

$animais = [];
while ($row = $result->fetch_assoc()) {
    $animais[] = $row;
}

echo json_encode($animais);
$conn->close();
?>