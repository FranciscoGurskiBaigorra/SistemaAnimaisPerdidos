<?php
include('conecta.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistema Animais Perdidos</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Estilo personalizado -->
  <link rel="stylesheet" href="css/estilo.css">
</head>

<body>
  <div class="card">
    <h2>Login</h2>
    <form action="verifica_login.php" method="POST">
      <div class="mb-3">
        <label for="email" class="form-label">E-mail:</label>
        <input type="email" class="form-control" name="email" id="email" required>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha:</label>
        <input type="password" class="form-control" name="senha" id="senha" required>
      </div>

      <button type="submit" class="btn btn-primary">Entrar</button>

      <div class="text-center mt-3">
        <a href="#">Esqueceu a senha?</a>
      </div>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
