<?php
include('conecta.php');

// Verifica se veio via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if ($senha !== $confirmar_senha) {
        echo "<script>alert('As senhas não coincidem!'); window.history.back();</script>";
        exit;
    }

    // Criptografar senha
    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir no banco
    $sql = "INSERT INTO usuarios (nome, email, telefone, endereco, senha, tipo_usuario, ativo)
            VALUES ('$nome', '$email', '$telefone', '$endereco', '$senha_criptografada', 'usuario', 1)";

    if ($conexao->query($sql) === TRUE) {
        echo "<script>alert('Usuário cadastrado com sucesso!'); window.location='login.php';</script>";
    } else {
        echo "Erro: " . $sql . "<br>" . $conexao->error;
    }

    $conexao->close();
}
?>
