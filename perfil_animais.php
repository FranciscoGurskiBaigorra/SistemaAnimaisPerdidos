<?php
session_start();
include('conecta.php');

if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Você precisa fazer login para acessar seu perfil.'); window.location='login.php';</script>";
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Busca os animais do usuário logado
$sql = "SELECT a.*, r.racas 
        FROM animais a 
        LEFT JOIN racas r ON a.raca_id = r.id 
        WHERE a.usuario_id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Animais</title>
</head>
<body>
    <h2>Meus Animais</h2>

    <a href="index.php">⬅ Voltar ao Início</a><br><br>

    <?php if ($resultado->num_rows > 0): ?>
        <table border="1" cellpadding="10">
    <tr>
        <th>Foto</th>
        <th>Nome</th>
        <th>Espécie</th>
        <th>Raça</th>
        <th>Cor</th>
        <th>Idade</th>
        <th>Situação</th>
        <th>Data do ocorrido</th>
        <th>Telefone</th>
        <th>Ações</th>
    </tr>
    <?php while ($animal = $resultado->fetch_assoc()): ?>
       <?php
if (!function_exists('safe')) {
    function safe($value) {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
?>
<tr>
    <td>
        <?php if (!empty($animal['foto'])): ?>
            <img src="uploads/<?= safe($animal['foto']) ?>" width="80">
        <?php else: ?>
            Sem foto
        <?php endif; ?>
    </td>
    <td><?= safe($animal['nome']) ?: 'Não informado' ?></td>
    <td><?= safe($animal['especie']) ?></td>
    <td><?= safe($animal['racas']) ?: 'Não informada' ?></td>
    <td><?= safe($animal['cor_predominante']) ?></td>
    <td><?= safe($animal['idade']) ?></td>
    <td><?= safe($animal['situacao']) ?></td>
    <td><?= safe($animal['data_ocorrido']) ?: '-' ?></td>
    <td><?= safe($animal['telefone_contato']) ?></td>
    <td>
        <a href="editar_animal.php?id=<?= safe($animal['id']) ?>">✏️ Editar</a> |
        <a href="excluir_animal.php?id=<?= safe($animal['id']) ?>"
           onclick="return confirm('Tem certeza que deseja excluir este animal?')">🗑️ Excluir</a>
    </td>
</tr>

    <?php endwhile; ?>
</table>

    <?php else: ?>
        <p>Você ainda não cadastrou nenhum animal.</p>
    <?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$conexao->close();
?>
