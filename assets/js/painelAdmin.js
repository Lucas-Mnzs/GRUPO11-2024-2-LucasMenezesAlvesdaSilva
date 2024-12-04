$(document).ready(function () {
  getSitu();
  $("#fundo_load").css("display", "none");
});

function mudarSitu() {
  $.ajax({
    url: "painelAdmin/abrir_fechar", // URL do backend
    type: "POST",
    dataType: "json", // Espera uma resposta JSON
    success: function (response) {
      if (response.status === "success") {
        getSitu();
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function getSitu() {
  $.ajax({
    url: "painelAdmin/getSitu",
    type: "GET",
    dataType: "json",
    success: function (situacao) {
      // Atualiza o texto do elemento diretamente
      if (situacao.situacao === "Aberto") {
        $("#abrir_fechar").removeClass("vermelho").addClass("verde");
      } else {
        $("#abrir_fechar").removeClass("verde").addClass("vermelho");
      }
      $("#abrir_fechar p").text(situacao.situacao);
    },
    error: function (xhr, status, error) {
      console.error("Erro ao obter situação:", error);
    },
  });
}

$("#logout").on("click", function () {
  $.ajax({
    url: "painelAdmin/logoutUser",
    type: "POST",
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        localStorage.clear();
        $.ajax({
          url: "home",
          type: "POST",
          success: function (response) {
            $("#paginas").html(response);
          },
        });
      } else {
        alert("deu pau!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
});

function mostrarPedidos() {
  $("#tabela_pedidos").empty();
  $.ajax({
    url: "painelAdmin/getPedidos",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      $("#fundo_pedidos").css("display", "flex");
      dados.forEach((dado) => {
        const dataHora = new Date(dado.data_hora); // Converte a string para um objeto Date
        const dataFormatada = dataHora.toLocaleDateString("pt-BR"); // Formata a data para o formato DD/MM/AAAA
        const horaFormatada = dataHora.toLocaleTimeString("pt-BR", {
          hour: "2-digit",
          minute: "2-digit",
        }); // Formata a hora para HH:MM
        $("#tabela_pedidos").append(`
          <tr>
              <td>${dado.situacao}</td>
              <td>${dado.id_pedido}</td>
              <td>${dataFormatada} ${horaFormatada}</td>
              <td>${dado.produtos}</td>
              <td>${dado.tipo_entrega}</td>
              <td>${dado.forma_pagamento}</td>
              <td>${parseFloat(dado.valor_total).toLocaleString("pt-BR", {
                style: "currency",
                currency: "BRL",
              })} <br> Troco: ${parseFloat(dado.troco).toLocaleString("pt-BR", {
          style: "currency",
          currency: "BRL",
        })}</td>
              <td>${dado.pnome}</td>
              <td>${dado.cell}</td>
              <td>${dado.rua}, ${dado.numero} - ${dado.complemento}</td>
              <td>${dado.referencia}</td>
              <td>
                  <button type="button" id="aceitar" class="verde" onclick="aceitar(${
                    dado.id_usuario
                  })">Aceitar</button>
              </td>
              <td>
                  <button type="button" id="rota" class="verde" onclick="rota(${
                    dado.id_usuario
                  })">Em Rota</button>
              </td>
              <td>
                  <button type="button" id="recusar" class="vermelho" onclick="recusar(${
                    dado.id_usuario
                  })">Recusar</button>
              </td>
              <td>
                  <button type="button" id="finalizar" class="vermelho" onclick="finalizar(${
                    dado.id_usuario
                  })">Finalizar</button>
              </td>
          </tr>
          `);
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function aceitar(id_usuario) {
  $.ajax({
    url: "painelAdmin/aceitar/" + id_usuario,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        mostrarPedidos();
      } else {
        alert("deu pau");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function rota(id_usuario) {
  $.ajax({
    url: "painelAdmin/rota/" + id_usuario,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        mostrarPedidos();
      } else {
        alert("deu pau");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function recusar(id_usuario) {
  $.ajax({
    url: "painelAdmin/recusar/" + id_usuario,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        mostrarPedidos();
      } else {
        alert("deu pau");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function finalizar(id_usuario) {
  $.ajax({
    url: "painelAdmin/finalizar/" + id_usuario,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        mostrarPedidos();
      } else {
        alert("deu pau");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function fecharPedidos() {
  $("#fundo_pedidos").css("display", "none");
}

$("#pedidos").on("mouseenter", function () {
  $("#mostrar_pedidos").addClass("visible");
});

$("#produtos").on("mouseenter", function () {
  $("#mostrar_produtos").addClass("visible");
});

$("#historico").on("mouseenter", function () {
  $("#mostrar_historico").addClass("visible");
});

$("#financas").on("mouseenter", function () {
  $("#mostrar_financas").addClass("visible");
});

$("#atividades").on("mouseenter", function () {
  $("#mostrar_atividades").addClass("visible");
});

$("#atividades").on("mouseleave", function () {
  $("#mostrar_atividades").removeClass("visible");
});

$("#adicionar_user").on("mouseenter", function () {
  $("#mostrar_adicionar_user").addClass("visible");
});

$("#adicionar_user").on("mouseleave", function () {
  $("#mostrar_adicionar_user").removeClass("visible");
});

$("#der").on("mouseenter", function () {
  $("#mostrar_der").addClass("visible");
});

$("#der").on("mouseleave", function () {
  $("#mostrar_der").removeClass("visible");
});

$("#ver_usuarios").on("mouseenter", function () {
  $("#mostrar_usuarios").addClass("visible");
});

$("#ver_usuarios").on("mouseleave", function () {
  $("#mostrar_usuarios").removeClass("visible");
});

$("#pedidos").on("mouseleave", function () {
  $("#mostrar_pedidos").removeClass("visible");
});

$("#produtos").on("mouseleave", function () {
  $("#mostrar_produtos").removeClass("visible");
});

$("#historico").on("mouseleave", function () {
  $("#mostrar_historico").removeClass("visible");
});

$("#financas").on("mouseleave", function () {
  $("#mostrar_financas").removeClass("visible");
});

function mostrarAtividades() {
  $("#tabela_atividades").empty();
  $.ajax({
    url: "painelAdmin/getAtividades",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      $("#fundo_atividades").css("display", "flex");
      dados.forEach((dado) => {
        $("#tabela_atividades").append(`
          <tr>
              <td>${dado.id_logs}</td>
              <td>${dado.id_usuario}</td>
              <td>${dado.pnome}</td>
              <td>${dado.acao}</td>
              <td>${dado.data}</td>
          </tr>
          `);
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function mostrarProdutos() {
  $("#tabela_produtos").empty();
  $.ajax({
    url: "painelAdmin/getProdutos",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      $("#fundo_produtos").css("display", "flex");
      dados.forEach((dado) => {
        $("#tabela_produtos").append(`
          <tr>
              <td>${dado.disponibilidade}</td>
              <td>${dado.idProdutos}</td>
              <td>${dado.nome}</td>
              <td>${dado.descricao}</td>
              <td>${parseFloat(dado.preco).toLocaleString("pt-BR", {
                style: "currency",
                currency: "BRL",
              })}</td>
              <td><img src="${
                dado.imagem
              }" heigth="70px" width="70px" alt="imagem do produto"></td>
              <td>
                  <button type="button" id="editar" class="verde" onclick="editar(${
                    dado.idProdutos
                  })">Editar</button>
              </td>
              <td>
                  <button type="button" id="deletar" class="vermelho" onclick="deletar(${
                    dado.idProdutos
                  })">Deletar</button>
              </td>
              <td>
                  <button type="button" id="disponivel" class="verde" onclick="disponivel(${
                    dado.idProdutos
                  })">Mostrar</button>
              </td>
              <td>
                  <button type="button" id="indisponivel" class="vermelho" onclick="indisponivel(${
                    dado.idProdutos
                  })">Esconder</button>
              </td>
          </tr>
          `);
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function fecharProdutos() {
  $("#fundo_produtos").css("display", "none");
}

document
  .getElementById("pesquisar_pedido")
  .addEventListener("keyup", function () {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll("#tabela_pedidos tr");

    rows.forEach((row) => {
      const cells = row.getElementsByTagName("td");
      const match = Array.from(cells).some((cell) =>
        cell.textContent.toLowerCase().includes(query)
      );

      row.style.display = match ? "" : "none";
    });
  });

document
  .getElementById("pesquisar_produto")
  .addEventListener("keyup", function () {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll("#tabela_produtos tr");

    rows.forEach((row) => {
      const cells = row.getElementsByTagName("td");
      const match = Array.from(cells).some((cell) =>
        cell.textContent.toLowerCase().includes(query)
      );

      row.style.display = match ? "" : "none";
    });
  });

document
  .getElementById("pesquisar_registro")
  .addEventListener("keyup", function () {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll("#tabela_historico tr");

    rows.forEach((row) => {
      const cells = row.getElementsByTagName("td");
      const match = Array.from(cells).some((cell) =>
        cell.textContent.toLowerCase().includes(query)
      );

      row.style.display = match ? "" : "none";
    });
  });

document
  .getElementById("pesquisar_atividade")
  .addEventListener("keyup", function () {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll("#tabela_atividades tr");

    rows.forEach((row) => {
      const cells = row.getElementsByTagName("td");
      const match = Array.from(cells).some((cell) =>
        cell.textContent.toLowerCase().includes(query)
      );

      row.style.display = match ? "" : "none";
    });
  });

document
  .getElementById("pesquisar_funcionario")
  .addEventListener("keyup", function () {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll("#tabela_funcionarios tr");

    rows.forEach((row) => {
      const cells = row.getElementsByTagName("td");
      const match = Array.from(cells).some((cell) =>
        cell.textContent.toLowerCase().includes(query)
      );

      row.style.display = match ? "" : "none";
    });
  });

document
  .getElementById("pesquisar_usuario")
  .addEventListener("keyup", function () {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll("#tabela_usuarios tr");

    rows.forEach((row) => {
      const cells = row.getElementsByTagName("td");
      const match = Array.from(cells).some((cell) =>
        cell.textContent.toLowerCase().includes(query)
      );

      row.style.display = match ? "" : "none";
    });
  });

function editar(id) {
  $.ajax({
    url: "painelAdmin/getDados/" + id,
    type: "GET",
    dataType: "json",
    success: function (dados) {
      $("#fundo_editar_produtos").css("display", "flex");
      dados.forEach((dado) => {
        $("#nome_produto_edit").val(dado.nome);
        $("#descricao_produto_edit").val(dado.descricao);
        $("#valor_produto_edit").val(dado.preco);
        $("#id_produto").val(dado.idProdutos);
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

$("#editar_produto").on("click", function () {
  // Captura dos valores dos campos
  let categoria = $("#categoria_edit").val();
  let nome_produto = $("#nome_produto_edit").val();
  let descricao_produto = $("#descricao_produto_edit").val();
  let valor_produto = $("#valor_produto_edit").val();
  let id_produto = $("#id_produto").val();
  let imagem_produto = $("#imagem_produto_edit")[0].files[0];

  if (categoria == "#") {
    $("#res_select_edit").text("Selecione uma opção.");
    $("#categoria_edit").css("boder", "1px solid red");
    $("#categoria_edit").css("filter", "drop-shadow(0 0 5px rgb(173, 81, 81))");
    return;
  } else {
    $("#res_select_edit").text("");
    $("#categoria_edit").css("border", "1px solid rgb(219, 219, 219)");
    $("#categoria_edit").css("filter", "none");
  }

  // Criação do objeto FormData
  let formData = new FormData();
  formData.append("categoria", categoria);
  formData.append("nome_produto", nome_produto);
  formData.append("descricao_produto", descricao_produto);
  formData.append("valor_produto", valor_produto);
  formData.append("id_produto", id_produto);
  formData.append("imagem_produto", imagem_produto);

  // Envio via AJAX
  $.ajax({
    url: "painelAdmin/editarProduto", // A URL do servidor para onde os dados são enviados
    type: "POST",
    data: formData,
    processData: false, // Impede o jQuery de processar os dados
    contentType: false, // Impede o jQuery de definir o content-type, já que é um FormData
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        esconderEdit();
        mostrarProdutos();
      } else {
        alert("Erro ao editar produto!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
});

function deletar(id) {
  $.ajax({
    url: "painelAdmin/deletar/" + id,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        mostrarProdutos();
        alert("deletado");
      } else {
        alert("deu pau");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function disponivel(id) {
  $.ajax({
    url: "painelAdmin/disponivel/" + id,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        mostrarProdutos();
        alert("disponível");
      } else {
        alert("deu pau");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function indisponivel(id) {
  $.ajax({
    url: "painelAdmin/indisponivel/" + id,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        mostrarProdutos();
        alert("indisponível");
      } else {
        alert("deu pau");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

$("#adicionar").on("click", function () {
  $("#fundo_adicionar_produtos").css("display", "flex");
  $("#nome_produto").val("");
  $("#descricao_produto").val("");
  $("#valor_produto").val("");
});

$("#adicionar_produto").on("click", function () {
  // Captura dos valores dos campos
  let categoria = $("#categoria").val();
  let nome_produto = $("#nome_produto").val();
  let descricao_produto = $("#descricao_produto").val();
  let valor_produto = $("#valor_produto").val();
  let imagem_produto = $("#imagem_produto")[0].files[0];

  if (categoria == "#") {
    $("#res_select").text("Selecione uma opção.");
    $("#categoria").css("boder", "1px solid red");
    $("#categoria").css("filter", "drop-shadow(0 0 5px rgb(173, 81, 81))");
    return;
  } else {
    $("#res_select").text("");
    $("#categoria").css("border", "1px solid rgb(219, 219, 219)");
    $("#categoria").css("filter", "none");
  }

  // Criação do objeto FormData
  let formData = new FormData();
  formData.append("categoria", categoria);
  formData.append("nome_produto", nome_produto);
  formData.append("descricao_produto", descricao_produto);
  formData.append("valor_produto", valor_produto);
  formData.append("imagem_produto", imagem_produto);

  // Envio via AJAX
  $.ajax({
    url: "painelAdmin/adicionarProduto", // A URL do servidor para onde os dados são enviados
    type: "POST",
    data: formData,
    processData: false, // Impede o jQuery de processar os dados
    contentType: false, // Impede o jQuery de definir o content-type, já que é um FormData
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        esconderAdc();
        mostrarProdutos();
      } else {
        alert("Erro ao adicionar produto!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
});

function esconderAdc() {
  $("#fundo_adicionar_produtos").css("display", "none");
}

function esconderFuncionarios() {
  $("#fundo_funcionarios").css("display", "none");
}

function esconderUsuarios() {
  $("#fundo_usuarios").css("display", "none");
}

function esconderEdit() {
  $("#fundo_editar_produtos").css("display", "none");
}

function esconderAdicionar() {
  $(".fundo_adicionar_user").css("display", "none");
  $("#pNome").val("");
  $("#sNome").val("");
  $("#nomeMae").val("");
  $("#dataNascimento").val("");
  $("#cpf").val("");
  $("#cell").val("");
  $("#e_mail").val("");
  $("#cep").val("");
  $("#estado").val("");
  $("#cidade").val("");
  $("#bairro").val("");
  $("#rua_cad").val("");
  $("#numero").val("");
  $("#complemento").val("");
  $("#referencia").val("");
  $("#user_cad").val("");
  $("#sen").val("");
}

function esconderHistorico() {
  $("#fundo_historico").css("display", "none");
}

function esconderFinancas() {
  $("#fundo_financas").css("display", "none");
}

function esconderAtividades() {
  $("#fundo_atividades").css("display", "none");
}

document
  .querySelector("#imagem_produto")
  .addEventListener("change", function () {
    document.querySelector(".span1").textContent = this.files[0].name;
  });

document
  .querySelector("#imagem_produto_edit")
  .addEventListener("change", function () {
    document.querySelector(".span1_edit").textContent = this.files[0].name;
  });

function mostrarHistorico() {
  $("#tabela_historico").empty();
  $.ajax({
    url: "painelAdmin/getHistorico",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      $("#fundo_historico").css("display", "flex");
      dados.forEach((dado) => {
        const dataHora = new Date(dado.data); // Converte a string para um objeto Date
        const dataFormatada = dataHora.toLocaleDateString("pt-BR"); // Formata a data para o formato DD/MM/AAAA
        const horaFormatada = dataHora.toLocaleTimeString("pt-BR", {
          hour: "2-digit",
          minute: "2-digit",
        }); // Formata a hora para HH:MM
        $("#tabela_historico").append(`
          <tr>
              <td>${dado.id_pedido}</td>
              <td>${dado.pnome}</td>
              <td>${dataFormatada} ${horaFormatada}</td>
              <td>${dado.produtos}</td>
              <td>${dado.pagamento}</td>
              <td>${parseFloat(dado.valor).toLocaleString("pt-BR", {
                style: "currency",
                currency: "BRL",
              })}</td>
              <td>
                <button type="button" id="excluir" class="vermelho" onclick="excluirRegistro(${
                  dado.id_pedido
                })">Excluir</button>
              </td>
          </tr>
          `);
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function limparHistorico() {
  $.ajax({
    url: "painelAdmin/limparHistorico",
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        mostrarHistorico();
      } else {
        alert("deu pau");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function excluirRegistro(id) {
  $.ajax({
    url: "painelAdmin/excluirRegistro/" + id,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        mostrarHistorico();
      } else {
        alert("deu pau");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

// Função para gerar uma cor aleatória em formato hexadecimal
function gerarCorAleatoria() {
  const letras = "0123456789ABCDEF";
  let cor = "#";
  for (let i = 0; i < 6; i++) {
    cor += letras[Math.floor(Math.random() * 16)];
  }
  return cor;
}

// Função para gerar uma lista de cores aleatórias conforme a quantidade de dados
function gerarListaCores(qtd) {
  return Array.from({ length: qtd }, gerarCorAleatoria);
}

let chartClientesVendas = null; // Variável global para armazenar o gráfico

function getClientesVendas() {
  $.ajax({
    url: "painelAdmin/getClientesVendas",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      console.log(dados);
      const ctx = document.getElementById("clientes");

      // Verifica se um gráfico já existe e o destrói
      if (chartClientesVendas) {
        chartClientesVendas.destroy();
      }

      // Cria o novo gráfico e armazena na variável global
      chartClientesVendas = new Chart(ctx, {
        type: "doughnut",
        data: {
          labels: dados.map((clientes) => clientes.pnome),
          datasets: [
            {
              label: "Volume de vendas por cliente",
              data: dados.map((clientes) => clientes.qtd),
              backgroundColor: gerarListaCores(dados.length),
              hoverOffset: 5,
            },
          ],
        },
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

let chartPratosVendas = null;

function getPratosVendas() {
  $.ajax({
    url: "painelAdmin/getPratosVendas",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      console.log(dados);
      const ctx = document.getElementById("pratos");

      // Verifica se um gráfico já existe e o destrói
      if (chartPratosVendas) {
        chartPratosVendas.destroy();
      }

      // Cria o novo gráfico e armazena na variável global
      chartPratosVendas = new Chart(ctx, {
        type: "doughnut",
        data: {
          labels: dados.map((pratos) => pratos.nome),
          datasets: [
            {
              label: "Volume de vendas por cliente",
              data: dados.map((pratos) => pratos.qtd),
              backgroundColor: gerarListaCores(dados.length),
              hoverOffset: 5,
            },
          ],
        },
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

let chartBebidasVendas = null;

function getBebidasVendas() {
  $.ajax({
    url: "painelAdmin/getBebidasVendas",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      console.log(dados);
      const ctx = document.getElementById("bebidas");

      // Verifica se um gráfico já existe e o destrói
      if (chartBebidasVendas) {
        chartBebidasVendas.destroy();
      }

      // Cria o novo gráfico e armazena na variável global
      chartBebidasVendas = new Chart(ctx, {
        type: "doughnut",
        data: {
          labels: dados.map((bebidas) => bebidas.nome),
          datasets: [
            {
              label: "Volume de vendas por cliente",
              data: dados.map((bebidas) => bebidas.qtd),
              backgroundColor: gerarListaCores(dados.length),
              hoverOffset: 5,
            },
          ],
        },
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

let chartVendasPeriodo = null;

function getVendasPeriodo() {
  $.ajax({
    url: "painelAdmin/getVendasPeriodo",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      console.log(dados);
      const ctx = document.getElementById("periodo");

      // Verifica se um gráfico já existe e o destrói
      if (chartVendasPeriodo) {
        chartVendasPeriodo.destroy();
      }

      // Cria o novo gráfico e armazena na variável global
      chartVendasPeriodo = new Chart(ctx, {
        type: "bar",
        data: {
          labels: dados.map((periodo) => periodo.data), // Datas
          datasets: [
            {
              label: "Quantidade de Vendas",
              data: dados.map((periodo) => periodo.qtd), // Quantidade de vendas
              backgroundColor: gerarListaCores(dados.length), // Cores
              borderWidth: 1,
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
              },
            },
          },
        },
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

let chartVendasPeriodoValor = null;

function getVendasPeriodoValor() {
  $.ajax({
    url: "painelAdmin/getVendasPeriodoValor",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      console.log(dados);
      const ctx = document.getElementById("periodo_valor");

      // Verifica se um gráfico já existe e o destrói
      if (chartVendasPeriodoValor) {
        chartVendasPeriodoValor.destroy();
      }

      // Cria o novo gráfico e armazena na variável global
      chartVendasPeriodoValor = new Chart(ctx, {
        type: "bar",
        data: {
          labels: dados.map((periodo) => periodo.data), // Datas
          datasets: [
            {
              label: "Valor das Vendas",
              data: dados.map((periodo) => periodo.qtd), // Quantidade de vendas
              backgroundColor: gerarListaCores(dados.length), // Cores
              borderWidth: 1,
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
              },
            },
          },
        },
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

// Função para filtrar o período
function filtrarPorPeriodo() {
  const dataInicio = document.getElementById("dataInicio").value;
  const dataFim = document.getElementById("dataFim").value;

  if (!dataInicio || !dataFim) {
    alert("Selecione um intervalo válido para o filtro.");
    return;
  }

  // Primeiro, fazemos a requisição para "getVendasPeriodo"
  $.ajax({
    url: "painelAdmin/getVendasPeriodo",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      const vendasFiltradas = dados.filter((item) => {
        const dataVenda = new Date(item.data);
        return (
          dataVenda >= new Date(dataInicio) && dataVenda <= new Date(dataFim)
        );
      });

      // Atualiza o gráfico de vendas por período
      atualizarGraficoPeriodo(vendasFiltradas);
    },
    error: function (xhr, status, error) {
      console.error(
        "Erro ao filtrar vendas por período: ",
        xhr.responseText || error
      );
    },
  });

  // Depois, fazemos a requisição para "getVendasPeriodoValor"
  $.ajax({
    url: "painelAdmin/getVendasPeriodoValor",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      const vendasFiltradasValor = dados.filter((item) => {
        const dataVenda = new Date(item.data);
        return (
          dataVenda >= new Date(dataInicio) && dataVenda <= new Date(dataFim)
        );
      });

      // Atualiza o gráfico de vendas por valor
      atualizarGraficoPeriodoValor(vendasFiltradasValor);
    },
    error: function (xhr, status, error) {
      console.error(
        "Erro ao filtrar vendas por valor no período: ",
        xhr.responseText || error
      );
    },
  });
}

// Função para atualizar o gráfico de vendas por período
function atualizarGraficoPeriodo(vendaFiltrada) {
  const grafPerio = document.getElementById("periodo");
  if (grafPerio) {
    const ctxPerio = grafPerio.getContext("2d");

    if (Chart.getChart(ctxPerio)) {
      Chart.getChart(ctxPerio).destroy();
    }

    new Chart(ctxPerio, {
      type: "bar",
      data: {
        labels: vendaFiltrada.map((perio) => perio.data),
        datasets: [
          {
            label: "Vendas Por Período",
            data: vendaFiltrada.map((perio) => perio.qtd),
            backgroundColor: gerarListaCores(vendaFiltrada.length),
            borderWidth: 1,
          },
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  } else {
    console.error("Elemento canvas 'periodo' não encontrado.");
  }
}

// Função para atualizar o gráfico de vendas por valor
function atualizarGraficoPeriodoValor(vendaFiltrada) {
  const grafPerioValor = document.getElementById("periodo_valor");
  if (grafPerioValor) {
    const ctxPerioValor = grafPerioValor.getContext("2d");

    if (Chart.getChart(ctxPerioValor)) {
      Chart.getChart(ctxPerioValor).destroy();
    }

    new Chart(ctxPerioValor, {
      type: "bar",
      data: {
        labels: vendaFiltrada.map((perio) => perio.data),
        datasets: [
          {
            label: "Valor das Vendas",
            data: vendaFiltrada.map((perio) => perio.qtd), // Usando 'valor' aqui
            backgroundColor: gerarListaCores(vendaFiltrada.length),
            borderWidth: 1,
          },
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  } else {
    console.error("Elemento canvas 'periodo_valor' não encontrado.");
  }
}

function mostrarFinancas() {
  getClientesVendas();
  getPratosVendas();
  getBebidasVendas();
  getVendasPeriodo();
  getVendasPeriodoValor();
  $("#fundo_financas").css("display", "flex");
}

function validarCPF(cpf) {
  const numeros = cpf.replace(/\D+/g, ""); // Remove caracteres não numéricos
  if (numeros.length !== 11) return false; // Verifica se o CPF tem 11 dígitos

  // Verifica se todos os dígitos são iguais
  const digitosIguais = numeros
    .split("")
    .every((digito) => digito === numeros[0]);
  if (digitosIguais) return false;

  // Cálculo do primeiro dígito verificador
  let soma = 0;
  for (let i = 0; i < 9; i++) {
    soma += parseInt(numeros.charAt(i)) * (10 - i);
  }
  let resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;
  if (resto !== parseInt(numeros.charAt(9))) return false;

  // Cálculo do segundo dígito verificador
  soma = 0;
  for (let i = 0; i < 10; i++) {
    soma += parseInt(numeros.charAt(i)) * (11 - i);
  }
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;
  if (resto !== parseInt(numeros.charAt(10))) return false;

  return true; // CPF válido
}

function verificarCPF() {
  const cpfInput = document.getElementById("cpf");
  const cpf1 = cpfInput.value;

  if (validarCPF(cpf1)) {
    document.querySelector("#cpf").style.border = "1px solid green";
    document.querySelector("#cpf").style.filter =
      "drop-shadow(0 0 5px rgb(76, 248, 24))";
    return true;
  } else {
    document.querySelector("#cpf").style.border = "1px solid red";
    document.querySelector("#cpf").style.filter =
      "drop-shadow(0 0 5px rgb(173, 81, 81))";
    return;
  }
}

function buscaCEP() {
  let cep = document.querySelector("#cep").value;
  if (cep != "") {
    let url = "https://brasilapi.com.br/api/cep/v1/" + cep;
    let req = new XMLHttpRequest();
    req.open("GET", url);
    req.send();

    // Tratar a resposta da requisição
    req.onload = function () {
      if (req.status === 200) {
        let endereco = JSON.parse(req.response);
        if (endereco.street && endereco.neighborhood) {
          document.querySelector("#rua_cad").value = endereco.street;
          document.querySelector("#bairro").value = endereco.neighborhood;
          document.querySelector("#cidade").value = endereco.city;
          document.querySelector("#estado").value = endereco.state;
          document.querySelector("#numero").removeAttribute("readonly");
          document.querySelector("#cep").style.border = "1px solid green";
          document.querySelector("#cep").style.filter =
            "drop-shadow(0 0 5px rgb(76, 248, 24))";
        }
      } else {
        document.querySelector("#rua_cad").value = "";
        document.querySelector("#bairro").value = "";
        document.querySelector("#numero").value = "";
        document.querySelector("#cidade").value = "";
        document.querySelector("#estado").value = "";
        document.querySelector("#resposta").textContent = "";
        document.querySelector("#numero").setAttribute("readonly", true);
        document.querySelector("#cep").style.border = "1px solid red";
        document.querySelector("#cep").style.filter =
          "drop-shadow(0 0 5px rgb(173, 81, 81))";
        return;
      }
    };
  } else {
    document.querySelector("#rua_cad").value = "";
    document.querySelector("#bairro").value = "";
    document.querySelector("#numero").value = "";
    document.querySelector("#cidade").value = "";
    document.querySelector("#estado").value = "";
    document.querySelector("#resposta").textContent = "";
    document.querySelector("#numero").setAttribute("readonly", true);
    document.querySelector("#cep").style.border = "1px solid red";
    document.querySelector("#cep").style.filter =
      "drop-shadow(0 0 5px rgb(173, 81, 81))";
    return;
  }
}

function cadastrarFunc() {
  let nome = $("#pNome").val();
  let sobrenome = $("#sNome").val();
  let nomeMae = $("#nomeMae").val();
  let dataNascimento = $("#dataNascimento").val();
  let cpf = $("#cpf").val();
  let cell = $("#cell").val();
  let email = $("#e_mail").val();
  let cep = $("#cep").val();
  let estado = $("#estado").val();
  let cidade = $("#cidade").val();
  let bairro = $("#bairro").val();
  let rua = $("#rua_cad").val();
  let num = $("#numero").val();
  let comp = $("#complemento").val();
  let referencia = $("#referencia").val();
  let user = $("#user_cad").val();
  let senha = $("#sen").val();

  let formData = new FormData();

  formData.append("nome", nome);
  formData.append("sobrenome", sobrenome);
  formData.append("nomeMae", nomeMae);
  formData.append("data", dataNascimento);
  formData.append("cpf", cpf);
  formData.append("cell", cell);
  formData.append("email", email);
  formData.append("cep", cep);
  formData.append("estado", estado);
  formData.append("cidade", cidade);
  formData.append("bairro", bairro);
  formData.append("rua", rua);
  formData.append("num", num);
  formData.append("comp", comp);
  formData.append("ref", referencia);
  formData.append("user", user);
  formData.append("senha", senha);

  $.ajax({
    url: "painelAdmin/adicionarFun",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        $("#fundo_load").css("display", "flex");
        $(".resposta").text("Funcionário cadastrado!");
        setTimeout(function () {
          $(".fundo_adicionar_user").css("display", "none");
          getFuncionarios();
          $("#fundo_load").css("display", "none");
          $("#pNome").val("");
          $("#sNome").val("");
          $("#nomeMae").val("");
          $("#dataNascimento").val("");
          $("#cpf").val("");
          $("#cell").val("");
          $("#e_mail").val("");
          $("#cep").val("");
          $("#estado").val("");
          $("#cidade").val("");
          $("#bairro").val("");
          $("#rua_cad").val("");
          $("#numero").val("");
          $("#complemento").val("");
          $("#referencia").val("");
          $("#user_cad").val("");
          $("#sen").val("");
        }, 2000);
      } else if (response.status === "errorUser") {
        $("#erroUser").text("Usuário já cadastrado!");
      } else if (response.status === "errorEmail") {
        $("#erroEmail").text("E-mail já cadastrado!");
      } else if (response.status === "errorCell") {
        $("#erroCell").text("Celular já cadastrado!");
      } else if (response.status === "errorCpf") {
        $("#erroCpf").text("CPF já cadastrado!");
      } else {
        $(".resposta").text("Preencha todos os campos!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

$("#cep").mask("00000-000");
$("#cpf").mask("000.000.000-00");
$("#cell").mask("+55 (00) 00000-0000");

// FUNÇÃO PARA VERIFICAR FORMATAÇÃO DE LOGIN E SENHA DA TELA DE CADASTRO
document.querySelector("#user_cad").addEventListener("keyup", function () {
  let pass = document.querySelector("#user_cad");
  const maxLength = 6;

  // Limitar o comprimento máximo
  if (pass.value.length > maxLength) {
    pass.value = pass.value.slice(0, maxLength);
  }

  // Verificar se o comprimento mínimo é atingido
  if (pass.value.length < maxLength) {
    pass.style.border = "1px solid red";
    pass.style.filter = "drop-shadow(0 0 2em rgba(255, 0, 0, 0.349))";
    document.querySelector("#erroUser").textContent =
      "O usuário deve ter 6 caracteres alfabéticos!";
    document.querySelector("#erroUser").style.color = "red";
    document.querySelector("#erroUser").style.fontSize = ".8em";
  } else {
    // Resetar estilos quando a senha for válida
    document.querySelector("#erroUser").textContent = "";
    pass.style.border = "";
    pass.style.filter = "";
  }
});

document.querySelector("#sen").addEventListener("keyup", function () {
  let pass = document.querySelector("#sen");
  const maxLength = 8;

  // Limitar o comprimento máximo
  if (pass.value.length > maxLength) {
    pass.value = pass.value.slice(0, maxLength);
  }

  // Verificar se o comprimento mínimo é atingido
  if (pass.value.length < maxLength) {
    pass.style.border = "1px solid red";
    pass.style.filter = "drop-shadow(0 0 2em rgba(255, 0, 0, 0.349))";
    document.querySelector("#erroSen").textContent =
      "A senha deve ter 8 caracteres alfabéticos!";
    document.querySelector("#erroSen").style.color = "red";
    document.querySelector("#erroSen").style.fontSize = ".8em";
  } else {
    // Resetar estilos quando a senha for válida
    document.querySelector("#erroSen").textContent = "";
    pass.style.border = "";
    pass.style.filter = "";
  }
});

document.querySelector("#cSen").addEventListener("keyup", function () {
  const pass = document.querySelector("#sen").value;
  const cPass = document.querySelector("#cSen");

  if (pass != cPass.value) {
    cPass.style.border = "1px solid red";
    cPass.style.filter = "drop-shadow(0 0 2em rgba(255, 0, 0, 0.349))";
    document.querySelector("#erroCsen").textContent =
      "As senhas não são iguais!";
    document.querySelector("#erroCsen").style.color = "red";
    document.querySelector("#erroCsen").style.fontSize = ".8em";
  } else {
    document.querySelector("#erroCsen").textContent = "";
    document.querySelector("#erroCsen").style.color = "";
    cPass.style.border = "";
    cPass.style.filter = "";
  }
});

// Função para verificar se apenas caracteres alfabéticos foram inseridos
function apenasAlfabeticos(event) {
  var input = event.target;
  var valor = input.value;

  // Expressão regular para verificar se há apenas caracteres alfabéticos
  var regex = /^[a-zA-Z]+$/;

  // Se o valor não corresponder à expressão regular, limpe o campo
  if (!regex.test(valor)) {
    input.value = valor.replace(/[^a-zA-Z]/g, "");
  }
}

// Adicione event listeners para os campos de login, senha e confirmação de senha
document
  .getElementById("user_cad")
  .addEventListener("input", apenasAlfabeticos);
document.getElementById("sen").addEventListener("input", apenasAlfabeticos);
document.getElementById("cSen").addEventListener("input", apenasAlfabeticos);

function mostrarFuncionarios() {
  $(".fundo_adicionar_user").css("display", "block");
}

function getFuncionarios() {
  $("#tabela_funcionarios").empty();
  $("#fundo_funcionarios").css("display", "flex");
  $.ajax({
    url: "painelAdmin/getFuncionarios",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      dados.forEach((dado) => {
        $("#tabela_funcionarios").append(`
          
          <tr>
              <td>${dado.idUsuarios}</td>
              <td>${dado.pnome}</td>
              <td>${dado.cpf}</td>
              <td>${dado.cell}</td>
              <td>${dado.email}</td>
              <td>${dado.rua}, ${dado.numero} - ${dado.bairro} - ${dado.cidade}/${dado.estado}</td>
              <td>
                  <button type="button" onclick="excluirFunc(${dado.idUsuarios})" class="vermelho">Excluir</button>
              </td>
          </tr>
          
          `);
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function excluirFunc(id) {
  $.ajax({
    url: "painelAdmin/excluirFunc/" + id,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        alert("Deu bom!");
        getFuncionarios();
      } else {
        alert("Deu pau!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function getUsuarios() {
  $("#tabela_usuarios").empty();
  $("#fundo_usuarios").css("display", "flex");
  $.ajax({
    url: "painelAdmin/getUsuarios",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      dados.forEach((dado) => {
        $("#tabela_usuarios").append(`
          
          <tr>
              <td>${dado.idUsuarios}</td>
              <td>${dado.pnome}</td>
              <td>${dado.cpf}</td>
              <td>${dado.cell}</td>
              <td>${dado.email}</td>
              <td>${dado.rua}, ${dado.numero} - ${dado.bairro} - ${dado.cidade}/${dado.estado}</td>
              <td>
                  <button type="button" onclick="excluirUser(${dado.idUsuarios})" class="vermelho">Excluir</button>
              </td>
          </tr>
          
          `);
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function excluirUser(id) {
  $.ajax({
    url: "painelAdmin/excluirUser/" + id,
    type: "POST",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        alert("Deu bom!");
        getUsuarios();
      } else {
        alert("Deu pau!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
}

function mostrarDer() {
  $("#fundo_der").css("display", "flex");
  $("body").css("overflow", "hidden");
}

function esconderDer() {
  $("#fundo_der").css("display", "none");
  $("body").css("overflow", "auto");
}
