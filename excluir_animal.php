<?php
session_start();
include('conecta.php');

if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Você precisa estar logado para excluir.'); window.location='login.php';</script>";
    exit;
}

$id = $_GET['id'] ?? 0;
$usuario_id = $_SESSION['usuario_id'];

// Busca o nome da foto para excluir
$sql = "SELECT foto FROM animais WHERE id = ? AND usuario_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $animal = $resultado->fetch_assoc();
    if (!empty($animal['foto']) && file_exists("uploads/" . $animal['foto'])) {
        unlink("uploads/" . $animal['foto']);
    }

    // Exclui o registro
    $sql = "DELETE FROM animais WHERE id = ? AND usuario_id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();

    echo "<script>alert('Animal excluído com sucesso!'); window.location='perfil_animais.php';</script>";
} else {
    echo "<script>alert('Animal não encontrado.'); window.location='perfil_animais.php';</script>";
}
?>
