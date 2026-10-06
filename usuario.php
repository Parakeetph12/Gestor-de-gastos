<?php
require "funcoes.php";
iniciar();

sair_se_erros(faltando(array(
    "nome" => "Nome",
    "email" => "E-mail",
    "senha" => "Senha",
    "nascimento" => "Data de nascimento",
    "renda" => "Renda mensal"
)));

$erros = array();
if (!filter_var(campo("email"), FILTER_VALIDATE_EMAIL)) {
    $erros[] = "E-mail inválido.";
}
if (strlen(campo("senha")) < 6) {
    $erros[] = "A senha deve ter no mínimo 6 caracteres.";
}
if (!is_numeric(campo("renda")) || (float) campo("renda") < 0) {
    $erros[] = "A renda mensal deve ser um número maior ou igual a zero.";
}
$nascimento = DateTime::createFromFormat("Y-m-d", campo("nascimento"));
$hoje = new DateTime();
if (!$nascimento || $nascimento > $hoje || (int) $nascimento->format("Y") < 1900) {
    $erros[] = "Data de nascimento inválida.";
}
sair_se_erros($erros);

$idade = $nascimento->diff($hoje)->y;
$linhas = array(
    "Nome: " . campo("nome"),
    "E-mail: " . campo("email"),
    "Idade calculada: " . $idade . " anos",
    "Renda mensal: " . dinheiro(campo("renda"))
);
if ($idade < 18) {
    $linhas[] = "Aviso: menor de 18 anos, será necessário informar um responsável.";
}
resposta(true, "Usuário recebido", $linhas);