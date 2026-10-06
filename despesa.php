<?php
require "funcoes.php";
iniciar();

sair_se_erros(faltando(array(
    "descricao" => "Descrição",
    "valor" => "Valor total",
    "data" => "Data",
    "categoria" => "Categoria",
    "forma_pagamento" => "Forma de pagamento",
    "parcelas" => "Parcelas"
)));

$erros = array();
if (!is_numeric(campo("valor")) || (float) campo("valor") <= 0) {
    $erros[] = "O valor deve ser maior que zero.";
}
$data = DateTime::createFromFormat("Y-m-d", campo("data"));
if (!$data) {
    $erros[] = "Data inválida.";
}
if (!ctype_digit(campo("parcelas")) || (int) campo("parcelas") < 1 || (int) campo("parcelas") > 24) {
    $erros[] = "As parcelas devem ser um número inteiro de 1 a 24.";
}
if (!in_array(campo("forma_pagamento"), array("Dinheiro", "Débito", "Crédito", "Pix"))) {
    $erros[] = "Forma de pagamento inválida.";
}
if ((int) campo("parcelas") > 1 && campo("forma_pagamento") !== "Crédito") {
    $erros[] = "Só é possível parcelar no Crédito.";
}
sair_se_erros($erros);

$valor = (float) campo("valor");
$parcelas = (int) campo("parcelas");
$ultima = clone $data;
$ultima->modify("+" . ($parcelas - 1) . " months");

$linhas = array(
    "Despesa: " . campo("descricao") . " (" . campo("categoria") . ")",
    "Pagamento: " . campo("forma_pagamento"),
    "Valor total: " . dinheiro($valor),
    "Parcelas: " . $parcelas . " x " . dinheiro($valor / $parcelas),
    "Última parcela em: " . $ultima->format("d/m/Y")
);
if ($valor > 1000) {
    $linhas[] = "Alerta: despesa de valor alto (acima de R$ 1.000,00).";
}
resposta(true, "Despesa recebida", $linhas);