<?php
header("Content-Type: text/html; charset=utf-8");

function iniciar() {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        resposta(false, "Requisição recusada", array("Este endereço aceita apenas POST."));
    }
}

function campo($nome) {
    if (isset($_POST[$nome]) && is_string($_POST[$nome])) {
        return trim($_POST[$nome]);
    }
    return "";
}

function faltando($campos) {
    $erros = array();
    foreach ($campos as $nome => $rotulo) {
        if (campo($nome) === "") {
            $erros[] = "Preencha o campo: " . $rotulo;
        }
    }
    return $erros;
}

function sair_se_erros($erros) {
    if (count($erros) > 0) {
        resposta(false, "Corrija os campos abaixo", $erros);
    }
}

function dinheiro($valor) {
    return "R$ " . number_format((float) $valor, 2, ",", ".");
}

function resposta($ok, $titulo, $linhas) {
    $classe = $ok ? "ok" : "erro";
    $html = "<div class=\"" . $classe . "\"><h3>" . htmlspecialchars($titulo, ENT_QUOTES, "UTF-8") . "</h3><ul>";
    foreach ($linhas as $linha) {
        $html .= "<li>" . htmlspecialchars($linha, ENT_QUOTES, "UTF-8") . "</li>";
    }
    $html .= "</ul></div>";
    echo $html;
    exit;
}