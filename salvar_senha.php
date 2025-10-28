<?php
include('conecta.php');

$email = $_POST['email'];
$token = $_POST['token'];
$senha = $_POST['senha'];
$senha2 = $_POST['senha2'];

if ($senha !== $senha2) {
    die("As senhas não coincidem!");
}

// Criptografa a nova senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Atualiza no banco
$sql1 = "UPDATE usuarios SET senha=? WHERE email=?";
$stmt1 = $conexao->prepare($sql1);
$stmt1->bind_param("ss", $senha_hash, $email);
$stmt1->execute();

// Marca o token como usado
$sql2 = "UPDATE recuperar_senha SET usado=1 WHERE email=? AND token=?";
$stmt2 = $conexao->prepare($sql2);
$stmt2->bind_param("ss", $email, $token);
$stmt2->execute();

echo "<script>alert('Senha alterada com sucesso! Faça login novamente.'); window.location='login.php';</script>";
?>
