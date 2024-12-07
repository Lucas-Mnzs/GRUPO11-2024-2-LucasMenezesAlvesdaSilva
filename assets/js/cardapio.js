let isClosed = false;

function fechado() {
  $(".fundoFechado").css("display", "flex");
}

function getSitu() {
  $.ajax({
    url: "cardapio/getSitu",
    type: "GET",
    dataType: "json",
    success: function (situacao) {
      $("#situacao_aberto").text(situacao.situacao);
      if (situacao.situacao === "Fechado") {
        fechado();
        $("body").css("overflow", "hidden");
        isClosed = true;
      } else {
        $(".fundoFechado").css("display", "none");
        isClosed = false;
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
  $("#der").css("display", "flex");
  $("body").css("overflow", "hidden");
}

function esconderDer() {
  $("#der").css("display", "none");
}

function abrirTroca() {
  $("#fundo_endereco").css("display", "flex");
  $("#fundo_endereco").css("z-index", "9998");
  $("body").css("overflow", "hidden");
}

$("#voltar_trocar").on("click", function () {
  fecharTrocar();
});

$("#trocar_cep").mask("00000-000");

function buscarCEP() {
  let cep = document.querySelector("#trocar_cep").value;
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
          document.querySelector("#trocar_rua").value = endereco.street;
          document.querySelector("#trocar_bairro").value =
            endereco.neighborhood;
          document.querySelector("#trocar_cidade").value = endereco.city;
          document.querySelector("#trocar_estado").value = endereco.state;
          document.querySelector("#trocar_numero").removeAttribute("readonly");
          document.querySelector("#trocar_cep").style.border =
            "1px solid green";
          document.querySelector("#trocar_cep").style.filter =
            "drop-shadow(0 0 5px rgb(76, 248, 24))";
        }
      } else {
        document.querySelector("#trocar_rua").value = "";
        document.querySelector("#trocar_bairro").value = "";
        document.querySelector("#trocar_numero").value = "";
        document.querySelector("#trocar_cidade").value = "";
        document.querySelector("#trocar_estado").value = "";
        document.querySelector("#res_trocar").textContent = "";
        document.querySelector("#trocar_numero").setAttribute("readonly", true);
        document.querySelector("#trocar_cep").style.border = "1px solid red";
        document.querySelector("#trocar_cep").style.filter =
          "drop-shadow(0 0 5px rgb(173, 81, 81))";
      }
    };
  } else {
    document.querySelector("#trocar_rua").value = "";
    document.querySelector("#trocar_bairro").value = "";
    document.querySelector("#trocar_numero").value = "";
    document.querySelector("#trocar_cidade").value = "";
    document.querySelector("#trocar_estado").value = "";
    document.querySelector("#res_trocar").textContent = "";
    document.querySelector("#trocar_numero").setAttribute("readonly", true);
    document.querySelector("#trocar_cep").style.border = "1px solid red";
    document.querySelector("#trocar_cep").style.filter =
      "drop-shadow(0 0 5px rgb(173, 81, 81))";
  }
}

$("#trocar_endereco").on("click", function () {
  if (
    $("#trocar_cep").val() === "" || // Use .val() para pegar o valor do campo
    $("#trocar_numero").val() === "" ||
    $("#trocar_referencia").val() === ""
  ) {
    $("#res_trocar").text("Preencha todos os campos");
    return false;
  } else {
    mostrarLoad();
    let formData = new FormData();

    // Adiciona os valores dos campos ao FormData
    formData.append("rua", $("#trocar_rua").val());
    formData.append("bairro", $("#trocar_bairro").val());
    formData.append("cidade", $("#trocar_cidade").val());
    formData.append("estado", $("#trocar_estado").val());
    formData.append("numero", $("#trocar_numero").val());
    formData.append("complemento", $("#trocar_complemento").val());
    formData.append("referencia", $("#trocar_referencia").val());

    // Faz a requisição AJAX
    $.ajax({
      url: "cardapio/trocarEndereco",
      type: "POST", // Use POST para enviar o FormData
      data: formData,
      dataType: "json",
      processData: false, // Impede o jQuery de processar o FormData
      contentType: false, // Impede o jQuery de adicionar cabeçalhos de conteúdo
      success: function (response) {
        if (response.status === "success") {
          $("#rua_num").empty();
          $("#bairro_cidade_estado").empty();
          $("#taxa").empty();
          $("#rua_num").append(`
            <p><b>${response.rua}, ${response.numero}</b></p>
            `);
          $("#bairro_cidade_estado").append(`
            <p>${response.bairro} - ${response.cidade}/${response.estado}</p>
            `);
          $("#taxa").append(`
            <p>Taxa: ${response.taxa}</p>
            `);

          rua = `${response.rua}, ${response.numero}`;
          bairro = `${response.bairro} - ${response.cidade}/${response.estado}`;

          $("#res_trocar").text("Endereço atualizado!");
          setTimeout(function () {
            esconderLoad();
            fecharTrocar();
          }, 2000);
        } else {
          esconderLoad();
          $("#res_trocar").text("Endereço não atendido!");
        }
      },
      error: function (xhr, status, error) {
        console.log("Status: " + status); // Mostra o status do erro
        console.log("Erro: " + error); // Mostra a descrição do erro
        console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
      },
    });
  }
});

$("#logout").on("click", function () {
  $.ajax({
    url: "cardapio/logoutUser",
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

function getDestaques() {
  $.ajax({
    url: "cardapio/getDestaques",
    type: "GET",
    dataType: "json",
    success: function (destaques) {
      $(".destaques").empty();
      destaques.forEach((destaque) => {
        $(".destaques").append(`
              <div class="destaque" onclick="getProdutoById(${
                destaque.idProdutos
              })">
                <div class="prin">
                    <img src="${destaque.imagem}" alt="imagem do prato">
                    <div class="nome_desc">
                        <h4>${destaque.nome}</h4>
                        <p>${destaque.descricao}</p>
                    </div>
                </div>
                <p>${parseFloat(destaque.preco).toLocaleString("pt-BR", {
                  style: "currency",
                  currency: "BRL",
                })}</p>
              </div>
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

function getPratos() {
  $.ajax({
    url: "cardapio/getPratos",
    type: "GET",
    dataType: "json",
    success: function (pratos) {
      $(".pratos").empty();
      pratos.forEach((prato) => {
        $(".pratos").append(`
            <div class="prato" onclick="getProdutoById(${prato.idProdutos})">
              <div class="prin">
                  <img src="${prato.imagem}" alt="imagem do prato">
                  <div class="nome_desc">
                      <h4>${prato.nome}</h4>
                      <p>${prato.descricao}</p>
                  </div>
              </div>
              <p>${parseFloat(prato.preco).toLocaleString("pt-BR", {
                style: "currency",
                currency: "BRL",
              })}</p>
            </div>
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

function getBebidas() {
  $.ajax({
    url: "cardapio/getBebidas",
    type: "GET",
    dataType: "json",
    success: function (bebidas) {
      $(".bebidas").empty();
      bebidas.forEach((bebida) => {
        $(".bebidas").append(`
            <div class="bebida" onclick="getProdutoById(${bebida.idProdutos})">
              <div class="prin">
                  <img src="${bebida.imagem}" alt="imagem do bebida">
                  <div class="nome_desc">
                      <h4>${bebida.nome}</h4>
                      <p>${bebida.descricao}</p>
                  </div>
              </div>
              <p>${parseFloat(bebida.preco).toLocaleString("pt-BR", {
                style: "currency",
                currency: "BRL",
              })}</p>
            </div>
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

function getProdutoById(id) {
  if (isClosed) {
    fecharComplemento();
    fechado();
    return;
  }
  $.ajax({
    url: "cardapio/getProdutoById/" + id,
    type: "GET",
    dataType: "json",
    success: function (dados) {
      dados.forEach((dado) => {
        $(".complemento").css("display", "flex");
        $("body").css("overflow", "hidden");
        $("#complemento").empty();
        $("#complemento").append(`
                <div id="imagemComp">
                    <img src="${dado.imagem}" alt="Imagem do prato">
                </div>
                <div id="corpoComp">
                    <div id="nome_desc_comp">
                        <h1>${dado.nome}</h1>
                        <p>${dado.descricao}</p>
                    </div>
                    <div id="respostaComp">
                        <input type="text" name="inputcomplemento" id="inputComplemento" placeholder="Alguma observação?"><br>
                        <button type="button" id="addProdutoBtn">Adicionar</button>
                    </div>
                </div>
            `);

        // Adiciona o evento de clique no botão
        $("#addProdutoBtn").on("click", function (event) {
          event.preventDefault(); // Evita o envio padrão do formulário

          let formData = new FormData();
          let complemento = $("#inputComplemento").val(); // Pega o valor do campo de complemento

          // Adiciona o complemento ao FormData
          formData.append("inputComplemento", complemento);

          // Envia a requisição AJAX para adicionar o produto
          $.ajax({
            url: "cardapio/adicionarProduto/" + id,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
              if (response.status === "success") {
                $(".complemento").css("display", "none");
                $("body").css("overflow", "auto");
                getQuantidade();
              } else {
                alert("Deu erro!");
              }
            },
            error: function (xhr, status, error) {
              console.log("Status: " + status);
              console.log("Erro: " + error);
              console.log("Resposta do servidor: " + xhr.responseText);
            },
          });
        });
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status);
      console.log("Erro: " + error);
      console.log("Resposta do servidor: " + xhr.responseText);
    },
  });
}

function getQuantidade() {
  $.ajax({
    url: "cardapio/getQuantidade",
    type: "GET",
    dataType: "json",
    success: function (qtd) {
      $(".qtd").text(qtd);
      if ($(".qtd").text() == "") {
        $(".fundoCarrinho").css("display", "none");
        $("body").css("overflow", "auto");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status);
      console.log("Erro: " + error);
      console.log("Resposta do servidor: " + xhr.responseText);
    },
  });
}

function getCarrinho() {
  if (isClosed) {
    fecharCarrinho();
    fechado();
    return;
  }
  $.ajax({
    url: "cardapio/getCarrinho",
    type: "GET",
    dataType: "json",
    success: function (dados) {
      $(".carrinho_corpo").empty();
      $(".itens_pedido").empty();
      dados.forEach((dado) => {
        $(".fundoCarrinho").css("display", "flex");
        $("body").css("overflow", "hidden");
        $(".carrinho_corpo").append(`
                  <div id="produto_carrinho">
                      <div id="imagem_infos_carrinho">
                          <img src="${dado.imagem}">
                      </div>
                      <div id="valor_qtd_carrinho">
                          <div id="infos_carrinho">
                              <h1>${dado.nome}</h1>
                              <p>${dado.descricao}</p>
                          </div>
                          <div id="direita" style="display: flex; align-items: center;">
                            <div id="valor_uni_prod">
                              <p id="valor_produto_carrinho">${parseFloat(
                                dado.preco
                              ).toLocaleString("pt-BR", {
                                style: "currency",
                                currency: "BRL",
                              })}</p>
                            </div>
                            <div id="qtd_carrinho">
                                <div id="qtd_remover">
                            
                                    <button type="button" id="somar" onclick="somar(this)">+</button>
                                    <input type="number" name="qtd_uni" id="qtd_uni" readonly value="${
                                      dado.qtd
                                    }">
                                    <button type="button" id="subtrair" onclick="subtrair(this)">-</button>
                            
                                    <button type="button" class="remover" onclick="removerProduto(${
                                      dado.id_produto
                                    })">Remover</button>
                            
                                </div>
                                <input type="hidden" name="id_produto" id="id_produto" value="${
                                  dado.id_produto
                                }">
                            </div>
                          </div>
                      </div>
                  </div>
              `);
        atualizarResumo();
      });
      atualizarValorTotal();
      getQuantidade();
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status);
      console.log("Erro: " + error);
      console.log("Resposta do servidor: " + xhr.responseText);
    },
  });
}

// Função para atualizar o valor total
function atualizarValorTotal() {
  // Recupera o valor da taxa da página e converte para número
  let taxaTexto = $("#taxa")
    .text()
    .replace("Taxa:", "")
    .replace("R$", "")
    .trim();
  taxaTexto = taxaTexto.replace(",", "."); // Converte a vírgula para ponto, se necessário
  let taxa = parseFloat(taxaTexto); // Converte a taxa para número

  $.ajax({
    url: "cardapio/getValorTotal",
    type: "GET",
    dataType: "json",
    success: function (valor) {
      // Verifica se a forma de entrega é "entrega" e soma a taxa
      let novo_valor =
        parseFloat(valor) + ($("#forma_entrega").val() == "entrega" ? taxa : 0);
      localStorage.setItem("valor", novo_valor);

      // Formata o valor final como moeda brasileira e exibe
      $("#valor").text(
        novo_valor.toLocaleString("pt-BR", {
          style: "currency",
          currency: "BRL",
        })
      );
    },
    error: function (xhr, status, error) {
      console.log("Erro ao buscar valor: ", error);
    },
  });
}

// Chama a função de atualização ao carregar a página para garantir que o valor seja exibido corretamente inicialmente
$(document).ready(function () {
  atualizarValorTotal();

  // Atualiza o valor sempre que a forma de entrega for alterada
  $("#forma_entrega").change(function () {
    atualizarValorTotal();
  });
});

function atualizarResumo() {
  $.ajax({
    url: "cardapio/getCarrinho", // Obtém os dados do carrinho
    type: "GET",
    dataType: "json",
    success: function (dados) {
      $(".itens_pedido").empty(); // Limpa o resumo atual
      dados.forEach((dado) => {
        $(".itens_pedido").append(`
          <p>${dado.qtd}X ${dado.nome}</p>
          <p>${dado.obs}</p>
        `);
      });
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status);
      console.log("Erro: " + error);
      console.log("Resposta do servidor: " + xhr.responseText);
    },
  });
}

function formaPagamento() {
  if (isClosed) {
    fecharPagamento();
    fechado();
    return;
  }
  $(".fundoCarrinho").css("display", "none");
  $("#fundo_pagamento").css("display", "flex");

  let produtos = [];
  document.querySelectorAll("#qtd_carrinho").forEach((item) => {
    const qtd = item.querySelector("#qtd_uni").value;
    const id_produto = item.querySelector("#id_produto").value;
    produtos.push({ qtd, id_produto });

    let formData = new FormData();

    formData.append("itens", JSON.stringify(produtos));

    $.ajax({
      url: "cardapio/setQuantidade",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          getQuantidade();
          atualizarResumo();
          atualizarValorTotal();
        } else {
          alert("deu pau");
        }
      },
      error: function (xhr, status, error) {
        console.log("Status: " + status);
        console.log("Erro: " + error);
        console.log("Resposta do servidor: " + xhr.responseText);
      },
    });
  });
}

function removerProduto(idProduto) {
  $.ajax({
    url: "cardapio/removerProduto/" + idProduto,
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        getCarrinho();
      } else {
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status);
      console.log("Erro: " + error);
      console.log("Resposta do servidor: " + xhr.responseText);
    },
  });
}

function somar(button) {
  let input = $(button).siblings("#qtd_uni");
  let qtd = parseInt(input.val());

  input.val(qtd + 1);

  $(button).siblings(".remover").hide();
}

function subtrair(button) {
  let input = $(button).siblings("#qtd_uni");
  let qtd = parseInt(input.val());

  if (qtd > 0) {
    input.val(qtd - 1);

    if (qtd - 1 === 0) {
      $(button).siblings(".remover").show();
    }
  }
}

$("#forma_pagamento").change(function () {
  if ($("#forma_pagamento").val() == "dinheiro") {
    $("#fundo_troco").css("display", "flex");
    $("#fundo_troco").css("z-index", "9997");
  }
});

let rua = $("#rua_num p ").text();

let bairro = $("#bairro_cidade_estado p").text();

$("#forma_entrega").change(function () {
  if ($("#forma_entrega").val() == "entrega") {
    $("#rua_num p").text(rua);
    $("#rua_num p").css("font-weight", "bold");
    $("#bairro_cidade_estado p").text(bairro);
  } else {
    $("#rua_num p").text("Rua Doutor Furquim Mendes, 990");
    $("#rua_num p").css("font-weight", "bold");
    $("#bairro_cidade_estado p").text("Vila Centenário - Duque de Caxias/RJ");
  }
});

$("#enviarTroco").on("click", function () {
  let valor = localStorage.getItem("valor");
  if ($("#troco").val() <= valor) {
    $("#resTroco").text("O troco deve ser maior que o valor total!");
    $("#troco").css("border", "1px solid red");
    $("#troco").css("filter", "drop-shadow(0 0 5px rgb(173, 81, 81))");
    return false;
  } else {
    localStorage.setItem("troco", $("#troco").val());
    fecharTroco();
    $("#resTroco").text("");
    $("#troco").css("border", "1px solid rgb(219, 219, 219)");
    $("#troco").css("filter", "none");
  }
});

$("#naoTroco").on("click", function () {
  fecharTroco();
});

function finalizar() {
  const pagamento = document.querySelector("#forma_pagamento");
  const entrega = document.querySelector("#forma_entrega");

  if (pagamento.value == "#") {
    document.querySelector("#res_pagamento").textContent =
      "Selecione uma opção";
    pagamento.style.border = "1px solid red";
    pagamento.style.filter = "drop-shadow(0 0 5px rgb(173, 81, 81))";
    return false;
  } else {
    document.querySelector("#res_pagamento").textContent = "";
    pagamento.style.border = "1px solid black";
    pagamento.style.filter = "drop-shadow(0 0 0 transparent)";
  }

  if (entrega.value == "#") {
    document.querySelector("#res_entrega").textContent = "Selecione uma opção";
    entrega.style.border = "1px solid red";
    entrega.style.filter = "drop-shadow(0 0 5px rgb(173, 81, 81))";
    return false;
  } else {
    document.querySelector("#res_entrega").textContent = "";
    entrega.style.border = "1px solid black";
    entrega.style.filter = "drop-shadow(0 0 0 transparent)";
  }

  let formData = new FormData();
  let valorTexto = $("#valor").text().replace("R$", "").trim();
  valorTexto = valorTexto.replace(",", ".");
  let valor = parseFloat(valorTexto);
  let troco = localStorage.getItem("troco");

  formData.append("pagamento", pagamento.value);
  formData.append("entrega", entrega.value);
  formData.append("valor", valor);
  formData.append("troco", troco);

  $.ajax({
    url: "cardapio/finalizarPedido",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        fecharPagamento();
        getSituacao();
      } else {
        alert("deu pau");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status);
      console.log("Erro: " + error);
      console.log("Resposta do servidor: " + xhr.responseText);
    },
  });
}

function getSituacao() {
  $.ajax({
    url: "cardapio/getSituacao",
    type: "GET",
    dataType: "json",
    success: function (dado) {
      if (
        dado.situacao == "Aguardando a confirmação do restaurante!" ||
        dado.situacao == "O pedido está em preparação!" ||
        dado.situacao == "O pedido saiu para entrega!"
      ) {
        exibirSituacao(dado);
      } else if (
        dado.situacao == "O pedido não pôde ser aceito!" ||
        dado.situacao == "O pedido foi finalizado!"
      ) {
        exibirSituacao(dado);
        setInterval(function () {
          fecharSituacao();
        }, 10000);
      } else {
        fecharSituacao();
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status);
      console.log("Erro: " + error);
      console.log("Resposta do servidor: " + xhr.responseText);
    },
  });
}

function exibirSituacao(dado) {
  $("#situacao").empty();
  $("#situacao").text(dado.situacao);
  $("#fundo_situacao").css("display", "flex");
  $("body").css("overflow", "hidden");
}

// Fecha o complemento ao clicar fora (na área cinza)
function fecharComplemento() {
  $(".complemento").css("display", "none");
  $("body").css("overflow", "auto");
}

// Evita fechar o complemento ao clicar no formulário
$("#complemento").on("click", function (e) {
  e.stopPropagation(); // Impede a propagação do evento para o pai
});

// Evita fechar o complemento ao clicar no formulário
$(".carrinho").on("click", function (e) {
  e.stopPropagation(); // Impede a propagação do evento para o pai
});

// Fecha o complemento ao clicar fora (na área cinza)
function fecharCarrinho() {
  $(".fundoCarrinho").css("display", "none");
  $("body").css("overflow", "auto");
}

// Evita fechar o complemento ao clicar no formulário
$(".pagamento").on("click", function (e) {
  e.stopPropagation(); // Impede a propagação do evento para o pai
});

// Fecha o complemento ao clicar fora (na área cinza)
function fecharPagamento() {
  $("#fundo_pagamento").css("display", "none");
  $("body").css("overflow", "auto");
}

function fecharSituacao() {
  $("#fundo_situacao").css("display", "none");
  $("body").css("overflow", "auto");
}

function fecharTroco() {
  $("#fundo_troco").css("display", "none");
  $("body").css("overflow", "auto");
}

function fecharTrocar() {
  $("#fundo_endereco").css("display", "none");
  $("body").css("overflow", "auto");
}

document.getElementById("pesquisar").addEventListener("keyup", function () {
  const query = this.value.toLowerCase(); // Obtém o valor da pesquisa e converte para minúsculas

  // Seleciona todos os pratos e bebidas
  const pratos = document.querySelectorAll("#pratos .prato");
  const bebidas = document.querySelectorAll("#bebidas .bebida");

  // Função para filtrar os elementos
  function filterItems(items) {
    items.forEach((item) => {
      const match = item.textContent.toLowerCase().includes(query);
      item.style.display = match ? "" : "none"; // Exibe ou oculta o item
    });
  }

  // Filtra os pratos e bebidas
  filterItems(pratos);
  filterItems(bebidas);
});

function mostrarLoad() {
  $("#fundo_load").css("display", "flex");
}

function esconderLoad() {
  $("#fundo_load").css("display", "none");
}

$(document).ready(function () {
  getQuantidade();
  getDestaques();
  getBebidas();
  getPratos();
  getSituacao();
  getSitu();

  // Configurar atualização periódica da situação
  setInterval(function () {
    getDestaques();
    getBebidas();
    getPratos();
    getSituacao();
    getSitu();
  }, 10000); // 10 segundos
});
