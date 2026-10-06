<?php
require "funcoes.php";
iniciar();

sair_se_erros(faltando(array(
    "descricao" => "Descrição",
    "valor" => "Valor",
    "data" => "Data",
    "fonte" => "Fonte",
    "conta" => "Conta de destino",
    "recorrente" => "Recorrente"
)));

$erros = array();
if (!is_numeric(campo("valor")) || (float) campo("valor") <= 0) {
    $erros[] = "O valor deve ser maior que zero.";
}
$data = DateTime::createFromFormat("Y-m-d", campo("data"));
if (!$data) {
    $erros[] = "Data inválida.";
}
if (!in_array(campo("fonte"), array("Salário", "Freelance", "Investimentos", "Outros"))) {
    $erros[] = "Fonte inválida.";
}
if (!in_array(campo("conta"), array("Carteira", "Conta corrente", "Poupança"))) {
    $erros[] = "Conta de destino inválida.";
}
if (!in_array(campo("recorrente"), array("sim", "nao"))) {
    $erros[] = "Informe se a receita é recorrente.";
}
sair_se_erros($erros);

$valor = (float) campo("valor");
$status = ($data > new DateTime()) ? "Previsto" : "Recebido";
$linhas = array(
    "Receita: " . campo("descricao") . " (" . campo("fonte") . ")",
    "Valor: " . dinheiro($valor),
    "Conta: " . campo("conta"),
    "Status: " . $status
);
if (campo("recorrente") === "sim") {
    $linhas[] = "Projeção para 12 meses: " . dinheiro($valor * 12);
}
resposta(true, "Receita recebida", $linhas);