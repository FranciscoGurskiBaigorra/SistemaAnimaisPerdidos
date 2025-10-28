<?php
include('conecta.php');

$email = $_GET['email'];
$token = $_GET['token'];

// Verifica se o token é válido e não foi usado
$sql = "SELECT * FROM recuperar_senha WHERE email=? AND token=? AND usado=0";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ss", $email, $token);
$stmt->execute();
$resultado = $stmt->get_result();
$recuperar = $resultado->fetch_assoc();

if (!$recuperar) {
    die("Token inválido ou já utilizado.");
}

// Verifica validade (24h)
date_default_timezone_set('America/Sao_Paulo');
$agora = new DateTime('now');
$data_criacao = new DateTime($recuperar['data']);
$data_criacao->modify('+1 day');
if ($agora > $data_criacao) {
    die("Este link expirou. Faça um novo pedido de recuperação.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Senha</title>
</head>
<body>
    <h2>Redefinir Senha</h2>
    <form action="salvar_senha.php" method="post">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <label>Nova senha:</label>
        <input type="password" name="senha" required><br>
        <label>Confirme a senha:</label>
        <input type="password" name="senha2" required><br><br>
        <input type="submit" value="Salvar nova senha">
    </form>
</body>
</html>
