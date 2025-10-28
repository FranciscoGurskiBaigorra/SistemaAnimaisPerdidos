<?php
session_start();
include('conecta.php');

if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Você precisa estar logado para editar.'); window.location='login.php';</script>";
    exit;
}

$id = $_GET['id'] ?? 0;
$usuario_id = $_SESSION['usuario_id'];

// Busca os dados do animal
$sql = "SELECT * FROM animais WHERE id = ? AND usuario_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    echo "<script>alert('Animal não encontrado.'); window.location='perfil_animais.php';</script>";
    exit;
}

$animal = $resultado->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $especie = $_POST['especie'];
    $genero = $_POST['genero'];
    $porte = $_POST['porte'];
    $cor = $_POST['cor_predominante'];
    $idade = $_POST['idade'];
    $situacao = $_POST['situacao'];
    $telefone = $_POST['telefone_contato'];
    $descricao = $_POST['descricao'];
    $raca_id = $_POST['raca_id'] ?? null;
    $data_desaparecimento = $_POST['data_desaparecimento'] ?? null;

    // Atualização de foto (opcional)
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
        $foto_nome = uniqid() . "_" . basename($_FILES["foto"]["name"]);
        move_uploaded_file($_FILES["foto"]["tmp_name"], "uploads/" . $foto_nome);
    } else {
        $foto_nome = $animal['foto'];
    }

    $sql = "UPDATE animais SET nome=?, especie=?, genero=?, porte=?, cor_predominante=?, idade=?, situacao=?, telefone_contato=?, descricao=?, raca_id=?, data_desaparecimento=?, foto=? WHERE id=? AND usuario_id=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssssssssssssis", $nome, $especie, $genero, $porte, $cor, $idade, $situacao, $telefone, $descricao, $raca_id, $data_desaparecimento, $foto_nome, $id, $usuario_id);

    if ($stmt->execute()) {
        echo "<script>alert('Animal atualizado com sucesso!'); window.location='perfil_animais.php';</script>";
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Animal</title>
</head>
<body>
<h2>Editar Animal</h2>

<form method="post" enctype="multipart/form-data">
    <label>Nome: <input type="text" name="nome" value="<?php echo htmlspecialchars($animal['nome']); ?>" required></label><br>
    <label>Espécie:
        <select name="especie" required>
            <option value="cachorro" <?= $animal['especie'] == 'cachorro' ? 'selected' : '' ?>>Cachorro</option>
            <option value="gato" <?= $animal['especie'] == 'gato' ? 'selected' : '' ?>>Gato</option>
            <option value="outro" <?= $animal['especie'] == 'outro' ? 'selected' : '' ?>>Outro</option>
        </select>
    </label><br>
    <label>Foto: <input type="file" name="foto" accept="image/*"></label><br>
    <label>Descrição: <br><textarea name="descricao" rows="4" cols="40"><?php echo htmlspecialchars($animal['descricao']); ?></textarea></label><br>
    <input type="submit" value="Salvar Alterações">
</form>

<a href="perfil_animais.php">⬅ Voltar</a>

</body>
</html>
