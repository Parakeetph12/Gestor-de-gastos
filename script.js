document.addEventListener("DOMContentLoaded", function () {
    var form = document.querySelector("form");
    var saida = document.getElementById("resposta");
    if (!form) { return; }

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        fetch(form.getAttribute("data-destino"), {
            method: "POST",
            body: new FormData(form)
        })
        .then(function (r) { return r.text(); })
        .then(function (html) { saida.innerHTML = html; })
        .catch(function () {
            saida.innerHTML = "<p class='erro'>Erro ao enviar. O servidor PHP está rodando?</p>";
        });
    });
});