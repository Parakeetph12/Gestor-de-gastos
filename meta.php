<?php
require "funcoes.php";
iniciar();

sair_se_erros(faltando(array(
    "categoria" => "Categoria",
    "mes" => "Mês",
    "limite" => "Limite da meta",
    "gasto" => "Gasto atual",
    "alerta" => "Percentual de alerta"
)));

$erros = array();
if (!preg_match("/^\d{4}-(0[1-9]|1[0-2])$/", campo("mes"))) {
    $erros[] = "Mês inválido.";
}
if (!is_numeric(campo("limite")) || (float) campo("limite") <= 0) {
    $erros[] = "O limite deve ser maior que zero.";
}
if (!is_numeric(campo("gasto")) || (float) campo("gasto") < 0) {
    $erros[] = "O gasto atual deve ser maior ou igual a zero.";
}
if (!ctype_digit(campo("alerta")) || (int) campo("alerta") < 1 || (int) campo("alerta") > 100) {
    $erros[] = "O percentual de alerta deve ser de 1 a 100.";
}
sair_se_erros($erros);

$limite = (float) campo("limite");
$gasto = (float) campo("gasto");
$percentual = ($gasto / $limite) * 100;

if ($gasto > $limite) {
    $situacao = "META ESTOURADA";
} elseif ($percentual >= (int) campo("alerta")) {
    $situacao = "ALERTA: perto do limite";
} else {
    $situacao = "Dentro da meta";
}

$linhas = array(
    "Categoria: " . campo("categoria") . " - mês " . campo("mes"),
    "Limite: " . dinheiro($limite),
    "Gasto atual: " . dinheiro($gasto) . " (" . number_format($percentual, 1, ",", ".") . "% do limite)",
    "Saldo restante: " . dinheiro($limite - $gasto),
    "Situação: " . $situacao
);
resposta(true, "Meta calculada", $linhas);