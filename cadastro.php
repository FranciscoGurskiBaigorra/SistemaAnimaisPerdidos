

<form action="salvar_usuario.php" method="POST">
  <input type="text" name="nome" placeholder="Nome completo" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="senha" placeholder="Senha" required>
  <input type="password" name="confirmar_senha" placeholder="Confirmar Senha" required>
  <input type="text" name="telefone" placeholder="Telefone">
  <input type="text" name="endereco" placeholder="Endereço">
  <input type="date" name="data_nascimento" required><br>
  <button type="submit">Cadastrar</button>
</form>

<p>Já tem uma conta? <a href="login.php">Fazer login</a></p>