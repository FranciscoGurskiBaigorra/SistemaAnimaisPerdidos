<?php
include('conecta.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - Sistema Animais Perdidos</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Estilo personalizado -->
  <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
  <div class="card">
    <h2>Cadastro de Usuário</h2>
    <form action="salvar_usuario.php" method="POST">
      
      <div class="mb-3">
        <label for="nome" class="form-label">Nome completo:</label>
        <input type="text" class="form-control" name="nome" id="nome" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">E-mail:</label>
        <input type="email" class="form-control" name="email" id="email" required>
      </div>

      <div class="mb-3">
        <label for="telefone" class="form-label">Telefone:</label>
        <input type="text" class="form-control" name="telefone" id="telefone" placeholder="(XX) XXXXX-XXXX" required>
      </div>

      <div class="mb-3">
        <label for="endereco" class="form-label">Endereço:</label>
        <input type="text" class="form-control" name="endereco" id="endereco" placeholder="Rua, número, bairro..." required>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha:</label>
        <input type="password" class="form-control" name="senha" id="senha" required>
      </div>

      <div class="mb-3">
        <label for="confirmar_senha" class="form-label">Confirmar senha:</label>
        <input type="password" class="form-control" name="confirmar_senha" id="confirmar_senha" required>
      </div>

      <button type="submit" class="btn btn-primary">Cadastrar</button>

      <div class="text-center mt-3">
        <a href="login.php">Já possui conta? Faça login</a>
      </div>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
