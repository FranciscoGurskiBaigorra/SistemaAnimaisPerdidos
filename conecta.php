<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco   = "animais_perdidos";

$conexao = new mysqli($servidor, $usuario, $senha, $banco);

if ($conexao->connect_error) 
{
    die("Erro na conexão: " . $conexao->connect_error);
}
?>
