$("#titulo").on("click", function () {
  localStorage.setItem("ultimaPagina", "home");
  $.ajax({
    url: "home",
    method: "GET",
    success: function (response) {
      $("#paginas").html(response);
    },
  });
});

function esconderLoad() {
  $("#fundo_load").css("display", "none");
}

// Função para iniciar
$(document).ready(function () {
  if (localStorage.getItem("ultimaPagina") === "cardapio") {
    // Redireciona para a página do cardápio se necessário
    $.ajax({
      url: "cardapio",
      type: "GET",
      success: function (response) {
        $("#paginas").html(response);
      },
    });
  } else if (localStorage.getItem("ultimaPagina") === "painelAdmin") {
    $.ajax({
      url: "painelAdmin",
      type: "GET",
      success: function (response) {
        $("#paginas").html(response);
      },
    });
  } else {
    $.ajax({
      url: "home",
      type: "GET",
      success: function (response) {
        $("#paginas").html(response);
      },
    });
  }
});
