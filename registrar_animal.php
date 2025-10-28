<?php
session_start();
include('conecta.php');

// Se o usuário não estiver logado, redireciona
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Você precisa fazer login para registrar um animal.'); window.location='login.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // capturas e trim
    $usuario_id = (int) $_SESSION['usuario_id'];
    $situacao = trim($_POST['situacao'] ?? '');
    $especie = trim($_POST['especie'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $raca_id_raw = $_POST['raca_id'] ?? '';
    $porte = trim($_POST['porte'] ?? '');
    $cor_predominante = trim($_POST['cor_predominante'] ?? '');
    $idade = trim($_POST['idade'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $telefone_contato = trim($_POST['telefone_contato'] ?? '');
    $data_ocorrido_input = trim($_POST['data_ocorrido'] ?? ''); // pode ser '' para NULL

    // ---- Validações básicas ----
    if ($situacao === '' || $especie === '' || $genero === '' || $porte === '' || $idade === '' || $telefone_contato === '') {
        echo "<script>alert('Preencha os campos obrigatórios.'); window.history.back();</script>";
        exit;
    }

    // valida raça (obrigatória)
    if ($raca_id_raw === '') {
        echo "<script>alert('Por favor, selecione a raça.'); window.history.back();</script>";
        exit;
    }
    $raca_id = intval($raca_id_raw);

    // Verifica se esse ID existe na tabela racas
    $chk = $conexao->prepare("SELECT id FROM racas WHERE id = ?");
    $chk->bind_param("i", $raca_id);
    $chk->execute();
    $chk_res = $chk->get_result();
    if ($chk_res->num_rows === 0) {
        $chk->close();
        echo "<script>alert('Raça inválida. Por favor selecione uma raça válida.'); window.history.back();</script>";
        exit;
    }
    $chk->close();

    // ---- Foto obrigatória (validação no servidor) ----
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('A foto do animal é obrigatória e deve ser uma imagem válida.'); window.history.back();</script>";
        exit;
    }

    // Trata upload da foto
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $tmpName = $_FILES['foto']['tmp_name'];
    $originalName = basename($_FILES['foto']['name']);
    // Gera nome único e seguro
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    $allowedExt = ['jpg','jpeg','png','gif','webp'];
    if (!in_array(strtolower($ext), $allowedExt)) {
        echo "<script>alert('Formato de imagem não permitido. Use jpg, png, gif ou webp.'); window.history.back();</script>";
        exit;
    }

    $foto_nome = uniqid('animal_') . '.' . $ext;
    $destinoRel = 'uploads/' . $foto_nome;
    $destinoAbs = $uploadDir . $foto_nome;

    if (!move_uploaded_file($tmpName, $destinoAbs)) {
        echo "<script>alert('Erro ao enviar a foto. Tente novamente.'); window.history.back();</script>";
        exit;
    }

    // ---- Preparar INSERT ----
    // Usamos NULLIF(?, '') para transformar string vazia em NULL no banco
    $sql = "INSERT INTO animais (
                usuario_id, situacao, especie, genero, foto, raca_id, porte,
                cor_predominante, idade, nome, descricao, data_ocorrido, telefone_contato
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULLIF(?,''), ?)";

    $stmt = $conexao->prepare($sql);
    if (!$stmt) {
        echo "Erro ao preparar query: " . $conexao->error;
        exit;
    }

    // Tipos: i (usuario_id), s situacao, s especie, s genero, s foto, i raca_id,
    // s porte, s cor, s idade, s nome, s descricao, s data_ocorrido ('' vira NULL), s telefone
    $types = "isssissssssss"; // 13 campos: i s s s s i s s s s s s s -> representado por "isssissssssss"
    $bind = $stmt->bind_param(
        $types,
        $usuario_id,
        $situacao,
        $especie,
        $genero,
        $foto_nome,
        $raca_id,
        $porte,
        $cor_predominante,
        $idade,
        $nome,
        $descricao,
        $data_ocorrido_input,
        $telefone_contato
    );

    if (!$bind) {
        echo "Erro no bind_param: " . $stmt->error;
        exit;
    }

    if ($stmt->execute()) {
        echo "<script>alert('Animal registrado com sucesso!'); window.location='index.php';</script>";
    } else {
        // Em caso de erro, remove a foto que já foi movida (limpeza)
        if (file_exists($destinoAbs)) unlink($destinoAbs);
        echo "Erro ao registrar animal: " . $stmt->error;
    }

    $stmt->close();
    $conexao->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Animal</title>
</head>
<body>

<h2>Registrar Animal</h2>

<form action="registrar_animal.php" method="POST" enctype="multipart/form-data">
    <label>Situação:</label><br>
    <select name="situacao" required>
        <option value="perdido">Animal Perdido</option>
        <option value="encontrado">Animal Encontrado</option>
    </select><br><br>

    <label>Espécie:</label><br>
    <select name="especie" required>
        <option value="cachorro">Cachorro</option>
        <option value="gato">Gato</option>
        <option value="outro">Outro</option>
    </select><br><br>

    <label>Gênero:</label><br>
    <select name="genero" required>
        <option value="macho">Macho</option>
        <option value="femea">Fêmea</option>
        <option value="nao_informado">Não informado</option>
    </select><br><br>

    <label for="raca_id">Raça:</label>
    <select name="raca_id" id="raca_id" required>
        <option value=""> -- Selecione a raça -- </option>
        <?php
        $racas = $conexao->query("SELECT id, racas FROM racas WHERE racas IS NOT NULL");
        if ($racas && $racas->num_rows > 0) {
            while ($r = $racas->fetch_assoc()) {
                echo "<option value='".intval($r['id'])."'>".htmlspecialchars($r['racas'])."</option>";
            }
        } else {
            echo "<option value=''>Nenhuma raça cadastrada</option>";
        }
        ?>
    </select><br><br>

    <label>Porte:</label><br>
    <select name="porte" required>
        <option value="pequeno">Pequeno</option>
        <option value="medio">Médio</option>
        <option value="grande">Grande</option>
    </select><br><br>

    <label>Cor predominante:</label><br>
    <select name="cor_predominante" required>
        <option value="preto">Preto</option>
        <option value="branco">Branco</option>
        <option value="marrom">Marrom</option>
        <option value="cinza">Cinza</option>
        <option value="caramelo">Caramelo</option>
        <option value="preto e branco">Preto e Branco</option>
        <option value="outros">Outros</option>
    </select><br><br>

    <label>Idade:</label><br>
    <select name="idade" required>
        <option value="filhote">Filhote</option>
        <option value="adulto">Adulto</option>
        <option value="idoso">Idoso</option>
    </select><br><br>

    <label>Nome do animal (opcional):</label><br>
    <input type="text" name="nome" maxlength="100"><br><br>

    <label>Descrição (opcional):</label><br>
    <textarea name="descricao" rows="3" cols="30"></textarea><br><br>

    <label for="data_ocorrido">Data do ocorrido (opcional):</label>
    <input type="date" name="data_ocorrido" id="data_ocorrido"><br><br>

    <label>Telefone de contato:</label><br>
    <input type="text" name="telefone_contato" required maxlength="20"><br><br>

    <label>Foto do animal (obrigatória):</label><br>
    <input type="file" name="foto" accept="image/*" required><br><br>

    <button type="submit">Registrar</button>
</form>

</body>
</html>
