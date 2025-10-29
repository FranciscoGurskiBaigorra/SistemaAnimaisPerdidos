<?php
session_start();
include('conecta.php');

// Se o usuário não estiver logado, redireciona
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Você precisa fazer login para registrar um animal.'); window.location='login.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION['usuario_id'];
    $situacao = $_POST['situacao'];
    $especie = $_POST['especie'];
    $genero = $_POST['genero'];
    $raca_id = $_POST['raca_id'];
    $porte = $_POST['porte'];
    $cor_predominante = $_POST['cor_predominante'];
    $idade = $_POST['idade'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $telefone_contato = $_POST['telefone_contato'];
    $data_ocorrido = !empty($_POST['data_ocorrido']) ? $_POST['data_ocorrido'] : NULL;

    // Upload da foto
    $foto_nome = null;
    if (!empty($_FILES["foto"]["name"])) {
        $foto_nome = uniqid() . "_" . basename($_FILES["foto"]["name"]);
        $destino = "uploads/" . $foto_nome;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $destino);
    }

    $sql = "INSERT INTO animais 
        (usuario_id, situacao, especie, genero, raca_id, porte, cor_predominante, idade, nome, descricao, data_ocorrido, telefone_contato, foto)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param(
        "isssiisssssss",
        $usuario_id,
        $situacao,
        $especie,
        $genero,
        $raca_id,
        $porte,
        $cor_predominante,
        $idade,
        $nome,
        $descricao,
        $data_ocorroido,
        $telefone_contato,
        $foto_nome
    );

    if ($stmt->execute()) {
        echo "<script>alert('Animal registrado com sucesso!'); window.location='index.php';</script>";
    } else {
        echo "Erro ao registrar animal: " . $stmt->error;
    }

    $stmt->close();
    $conexao->close();
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

    <label>Raça:</label><br>
    <select name="raca_id" required>
        <?php
        $racas = $conexao->query("SELECT id, racas FROM racas WHERE racas IS NOT NULL");
        while ($r = $racas->fetch_assoc()) {
            echo "<option value='{$r['id']}'>{$r['racas']}</option>";
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
    <select name="cor_predominante">
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

    <label>Data do ocorrido (opcional):</label><br>
    <input type="date" name="data_ocorrido"><br><br>

    <label>Telefone de contato:</label><br>
    <input type="text" name="telefone_contato" required maxlength="20"><br><br>

    <label>Foto do animal (opcional):</label><br>
    <input type="file" name="foto" accept="image/*"><br><br>

    <button type="submit">Registrar</button>
</form>

</body>
</html>
