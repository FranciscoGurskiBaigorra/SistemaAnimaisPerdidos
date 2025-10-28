<?php
include('conecta.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $data_nascimento = $_POST['data_nascimento'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Verifica se as senhas coincidem
    if ($senha !== $confirmar_senha) {
        echo "<script>alert('As senhas não coincidem!'); window.history.back();</script>";
        exit;
    }

    // Verifica se o e-mail já está cadastrado
    $verifica = $conexao->prepare("SELECT id FROM usuarios WHERE email = ?");
    $verifica->bind_param("s", $email);
    $verifica->execute();
    $resultado = $verifica->get_result();

    if ($resultado->num_rows > 0) {
        echo "<script>alert('Este e-mail já está cadastrado!'); window.history.back();</script>";
        exit;
    }

    // Criptografa a senha
    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

    // Insere o novo usuário
    $sql = "INSERT INTO usuarios (nome, email, telefone, endereco, data_nascimento, senha, tipo_usuario, ativo)
            VALUES (?, ?, ?, ?, ?, ?, 'usuario', 'sim')";
    
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssssss", $nome, $email, $telefone, $endereco, $data_nascimento, $senha_criptografada);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário cadastrado com sucesso!'); window.location='login.php';</script>";
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    // Fecha conexões
    $stmt->close();
    $verifica->close();
    $conexao->close();
}
?>
