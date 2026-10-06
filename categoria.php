<?php
require "funcoes.php";
iniciar();

sair_se_erros(faltando(array(
    "nome" => "Nome da categoria",
    "tipo" => "Tipo",
    "descricao" => "Descrição",
    "essencial" => "Essencial?",
    "limite" => "Limite mensal"
)));

$erros = array();
if (!in_array(campo("tipo"), array("despesa", "receita"))) {
    $erros[] = "Tipo inválido.";
}
if (!in_array(campo("essencial"), array("sim", "nao"))) {
    $erros[] = "Informe se a categoria é essencial.";
}
if (!is_numeric(campo("limite")) || (float) campo("limite") < 0) {
    $erros[] = "O limite mensal deve ser um número maior ou igual a zero.";
}
sair_se_erros($erros);

$limite = (float) campo("limite");
$linhas = array(
    "Categoria: " . campo("nome") . " (" . campo("tipo") . ")",
    "Descrição: " . campo("descricao"),
    "Classificação: " . (campo("essencial") === "sim" ? "Essencial" : "Não essencial"),
    "Limite mensal: " . dinheiro($limite)
);
if (campo("tipo") === "despesa" && campo("essencial") === "sim" && $limite == 0) {
    $linhas[] = "Aviso: categoria essencial sem limite definido.";
}
if (campo("tipo") === "receita" && $limite > 0) {
    $linhas[] = "Aviso: limite mensal normalmente não se aplica a receitas.";
}
resposta(true, "Categoria recebida", $linhas);