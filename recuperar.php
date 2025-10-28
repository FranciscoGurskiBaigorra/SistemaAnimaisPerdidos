<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'conecta.php';
$conexao = $conexao; // Usa sua conexão já existente

$email = $_POST['email'];

// Verifica se o e-mail está cadastrado
$verifica = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
$verifica->bind_param("s", $email);
$verifica->execute();
$resultado = $verifica->get_result();
$usuario = $resultado->fetch_assoc();

if (!$usuario) {
    die("Email não encontrado!");
}

// Cria token aleatório
$token = bin2hex(random_bytes(32));

// Insere na tabela recuperar_senha
$sql = "INSERT INTO recuperar_senha (email, token) VALUES (?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ss", $email, $token);
$stmt->execute();

// PHPMailer
require_once 'PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/src/SMTP.php';
require_once 'PHPMailer-master/src/Exception.php';

$mail = new PHPMailer(true);

try {
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'francisco.2023318347@aluno.iffar.edu.br'; 
    $mail->Password = 'mdnlqoskzdtpsigf'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('francisco.2023318347@aluno.iffar.edu.br', 'Sistema de Animais Perdidos');
    $mail->addAddress($email);

    $link = "http://" . $_SERVER['SERVER_NAME'] . "/sistemaanimaisperdidos/nova_senha.php?email=" . urlencode($email) . "&token=" . urlencode($token);

    $mail->isHTML(true);
    $mail->Subject = 'Recuperação de Senha';
    $mail->Body = 'Olá!<br>
    Você solicitou a recuperação da sua conta no nosso sistema.
    Para isso, clique no link abaixo para redefinir sua senha:<br>
    <a href="http://' . $_SERVER['SERVER_NAME'] .
    '/sistemaanimaisperdidos/SistemaAnimaisPerdidos/nova_senha.php?email=' .
    $email . '&token=' . $token . '">
    Clique aqui para redefinir sua senha</a><br><br>
    Atenciosamente,<br>Equipe do Sistema de Animais Perdidos.';


    $mail->send();
    echo "<script>alert('Um link de recuperação foi enviado para o seu e-mail.'); window.location='login.php';</script>";

} catch (Exception $e) {
    echo "Erro ao enviar email: {$mail->ErrorInfo}";
}
?>
