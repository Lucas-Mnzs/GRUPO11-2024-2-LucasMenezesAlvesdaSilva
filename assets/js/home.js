// FUNÇÃO PARA VALIDAR O CPF
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
    return false;
  }
}

/**
 *
 *
 */

// FUNÇÃO PARA BUSCAR O ENDEREÇO DE ACORDO COM O CEP
function cepHomeError() {
  document.querySelector(".rua").value = "";
  document.querySelector(".bairro").value = "";
  document.querySelector(".num").value = "";
  document.querySelector(".cidade").value = "";
  document.querySelector(".estado").value = "";
  document.querySelector("#resposta").textContent = "";
  document.querySelector("#resposta").style.padding = "0px";
  document.querySelector("#resposta").style.marginTop = "0px";
  document.querySelector(".num").setAttribute("readonly", true);
  document.querySelector(".cep").style.border = "1px solid red";
  document.querySelector(".cep").style.filter =
    "drop-shadow(0 0 5px rgb(173, 81, 81))";
}

var timeoutHome; // Variável para controlar o atraso

function cepInput() {
  const cepInput = document.querySelector(".cep").value;

  // Limpa o timeoutHome anterior para evitar múltiplas execuções
  clearTimeout(timeoutHome);

  // Define um novo timeoutHome de 500ms após a última tecla pressionada
  timeoutHome = setTimeout(() => {
    if (cepInput.length === 9) {
      buscarCEP(cepInput);
    } else {
      cepHomeError();
    }
  }, 300);
}

function buscarCEP(cep) {
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
          document.querySelector(".rua").value = endereco.street;
          document.querySelector(".bairro").value = endereco.neighborhood;
          document.querySelector(".cidade").value = endereco.city;
          document.querySelector(".estado").value = endereco.state;
          document.querySelector(".num").removeAttribute("readonly");
          document.querySelector(".cep").style.border = "1px solid green";
          document.querySelector(".cep").style.filter =
            "drop-shadow(0 0 5px rgb(76, 248, 24))";
          document.querySelector("#rua_cad").value = endereco.street;
          document.querySelector("#bairro").value = endereco.neighborhood;
          document.querySelector("#cidade").value = endereco.city;
          document.querySelector("#estado").value = endereco.state;
          document.querySelector("#cep").value = cep;
          document.querySelector("#numero").removeAttribute("readonly");
          document.querySelector("#cep").style.border = "1px solid green";
          document.querySelector("#cep").style.filter =
            "drop-shadow(0 0 5px rgb(76, 248, 24))";
        }
      } else {
        cepHomeError();
      }
    };
  } else {
    cepHomeError();
  }
}

function cepCadError() {
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
}

var timeoutCadastro; // Variável para controlar o atraso

function cepInpu() {
  const cepInpu = document.querySelector("#cep").value;

  // Limpa o timeout anterior para evitar múltiplas execuções
  clearTimeout(timeoutCadastro);

  // Define um novo timeout de 500ms após a última tecla pressionada
  timeoutCadastro = setTimeout(() => {
    if (cepInpu.length === 9) {
      buscaCEP(cepInpu);
    } else {
      cepCadError();
    }
  }, 300);
}

function buscaCEP(cep) {
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
        cepCadError();
      }
    };
  } else {
    cepCadError();
  }
}

/**
 *
 *
 *
 */

// FUNÇÃO PARA VERIFICAR SE O ENDEREÇO É ATENDIDO
$("#formPrin").on("submit", function (event) {
  event.preventDefault();

  let formData = new FormData(this);

  $.ajax({
    url: "home/consultarCEP",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      mostrarLoad();
      if (response.status === "success") {
        setTimeout(function () {
          esconderLoad();
          $("#resposta").text("Endereço atendido!");
          $("#resposta").css("padding", "10px");
          $("#resposta").css("text-align", "center");
          $("#resposta").css("color", "green");
          $("#resposta").css("background-color", "rgb(160, 252, 176)");
          $("#resposta").css("text-align", "center");
          $("#resposta").css("border-radius", "5px");
          $("#resposta").css("margin-top", "15px");
          if (window.innerWidth < 900) {
            $("#formPrin").css("display", "none");
            $("#formSec").css("transform", "translate(-50%, -50%)");
          } else {
            $("#formSec").css("transform", "translate(400px, 0)");
          }
          $("#formSec").css("display", "flex");
        }, 2000);
      } else {
        setTimeout(function () {
          esconderLoad();
          $("#resposta").text("Endereço não atendido!");
          $("#resposta").css("padding", "10px");
          $("#resposta").css("text-align", "center");
          $("#resposta").css("color", "darkred");
          $("#resposta").css("background-color", "rgb(252, 160, 160)");
          $("#resposta").css("text-align", "center");
          $("#resposta").css("border-radius", "5px");
          $("#resposta").css("margin-top", "15px");
          $("#formSec").css("display", "none");
          $("#formSec").css("transform", "translate(0, 0)");
        }, 2000);
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
      $("#formSec").css("transform", "translate(0, 0)");
    },
  });
});

/**
 *
 *
 *
 *
 */

// FUNÇÃO PARA FAZER O LOGIN DO USUÁRIO
$("#formUser").on("submit", function (event) {
  event.preventDefault();

  let formData = new FormData(this);

  $.ajax({
    url: "home/logarUser",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        $("#form").css("display", "none");
        $("#formSec").css("display", "none");
        $("#formSegunda").css("display", "flex");
        $("#respostaLogin").text("");
      } else if (response.status === "errorUser") {
        $("#respostaLogin").text("Usuário não encontrado!");
        $("#respostaLogin").css("padding", "10px");
        $("#respostaLogin").css("text-align", "center");
        $("#respostaLogin").css("color", "darkred");
        $("#respostaLogin").css("background-color", "rgb(252, 160, 160)");
        $("#respostaLogin").css("text-align", "center");
        $("#respostaLogin").css("border-radius", "5px");
        $("#respostaLogin").css("margin-top", "15px");
      } else {
        $("#respostaLogin").text("Senha incorreta!");
        $("#respostaLogin").css("padding", "10px");
        $("#respostaLogin").css("text-align", "center");
        $("#respostaLogin").css("color", "darkred");
        $("#respostaLogin").css("background-color", "rgb(252, 160, 160)");
        $("#respostaLogin").css("text-align", "center");
        $("#respostaLogin").css("border-radius", "5px");
        $("#respostaLogin").css("margin-top", "15px");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
});

/**
 *
 *
 *
 *
 */

var tentativasRestantes = 3; // Declare apenas uma vez

$("#formAut").on("submit", function (event) {
  mostrarLoad();
  event.preventDefault();

  let formData = new FormData(this);

  $.ajax({
    url: "home/segundaAut",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        localStorage.setItem("ultimaPagina", "cardapio");
        $("#tentativas").text("Login efetuado!");
        $("#tentativas").css("padding", "10px");
        $("#tentativas").css("text-align", "center");
        $("#tentativas").css("color", "green");
        $("#tentativas").css("background-color", "rgb(160, 252, 176)");
        $("#tentativas").css("text-align", "center");
        $("#tentativas").css("border-radius", "5px");
        $("#tentativas").css("margin-top", "15px");
        setTimeout(() => {
          $.ajax({
            url: "cardapio",
            type: "POST",
            success: function (response) {
              esconderLoad();
              $("#paginas").html(response);
            },
          });
        }, 1000);
        tentativasRestantes = 3; // Reseta as tentativas
      } else if (response.status === "successHost") {
        localStorage.setItem("ultimaPagina", "painelAdmin");
        $("#tentativas").text("Login efetuado!");
        $("#tentativas").css("padding", "10px");
        $("#tentativas").css("text-align", "center");
        $("#tentativas").css("color", "green");
        $("#tentativas").css("background-color", "rgb(160, 252, 176)");
        $("#tentativas").css("text-align", "center");
        $("#tentativas").css("border-radius", "5px");
        $("#tentativas").css("margin-top", "15px");
        setTimeout(() => {
          $.ajax({
            url: "painelAdmin",
            type: "POST",
            success: function (response) {
              esconderLoad();
              $("#paginas").html(response);
            },
          });
        }, 1000);
        tentativasRestantes = 3; // Reseta as tentativas
      } else {
        tratarErro();
      }
    },
    error: function () {
      tratarErro();
    },
  });
});

function tratarErro() {
  tentativasRestantes--;
  if (tentativasRestantes > 0) {
    setTimeout(function () {
      esconderLoad();
      $("#tentativas").text(
        `Resposta incorreta. Você ainda tem ${tentativasRestantes} tentativas restantes.`
      );
      $("#tentativas").css("padding", "10px");
      $("#tentativas").css("text-align", "center");
      $("#tentativas").css("color", "darkred");
      $("#tentativas").css("background-color", "rgb(252, 160, 160)");
      $("#tentativas").css("text-align", "center");
      $("#tentativas").css("border-radius", "5px");
      $("#tentativas").css("margin-top", "15px");
    }, 2000);
  } else {
    mostrarLoad();
    $("#tentativas").text(
      "Você excedeu o número de tentativas. Voltando para a tela de login."
    );
    $("#tentativas").css("padding", "10px");
    $("#tentativas").css("text-align", "center");
    $("#tentativas").css("color", "darkred");
    $("#tentativas").css("background-color", "rgb(252, 160, 160)");
    $("#tentativas").css("text-align", "center");
    $("#tentativas").css("border-radius", "5px");
    $("#tentativas").css("margin-top", "15px");
    setTimeout(() => {
      esconderLoad();
      $("#formSegunda").hide();
      $("#formSec").css("display", "flex");
      $("#tentativas").text("");
    }, 2000);
  }
}

/**
 *
 *
 *
 *
 */

// FUNÇÃO PARA CONFIRMAR QUAL É O USUÁRIO QUE ESTÁ TENTANDO ALTERAR A SENHA
$("#formEsquece").on("submit", function (event) {
  event.preventDefault();

  let formData = new FormData(this);

  $.ajax({
    url: "home/confirmarUser",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        $("#formRecuperacao").css("display", "flex");
        $(".close").css("display", "none");
      } else if (response.status === "errorUser") {
        $("#respostaEsqueceu").text("Usuário não encontrado!");
        $("#respostaEsqueceu").css("padding", "10px");
        $("#respostaEsqueceu").css("text-align", "center");
        $("#respostaEsqueceu").css("color", "darkred");
        $("#respostaEsqueceu").css("background-color", "rgb(252, 160, 160)");
        $("#respostaEsqueceu").css("text-align", "center");
        $("#respostaEsqueceu").css("border-radius", "5px");
        $("#respostaEsqueceu").css("margin-top", "15px");
      } else if (response.status === "errorEmail") {
        $("#respostaEsqueceu").text("Email não encontrado!");
        $("#respostaEsqueceu").css("padding", "10px");
        $("#respostaEsqueceu").css("text-align", "center");
        $("#respostaEsqueceu").css("color", "darkred");
        $("#respostaEsqueceu").css("background-color", "rgb(252, 160, 160)");
        $("#respostaEsqueceu").css("text-align", "center");
        $("#respostaEsqueceu").css("border-radius", "5px");
        $("#respostaEsqueceu").css("margin-top", "15px");
      } else {
        $("#respostaEsqueceu").text("Resposta incorreta!");
        $("#respostaEsqueceu").css("padding", "10px");
        $("#respostaEsqueceu").css("text-align", "center");
        $("#respostaEsqueceu").css("color", "darkred");
        $("#respostaEsqueceu").css("background-color", "rgb(252, 160, 160)");
        $("#respostaEsqueceu").css("text-align", "center");
        $("#respostaEsqueceu").css("border-radius", "5px");
        $("#respostaEsqueceu").css("margin-top", "15px");
      }
    },
    error: function (xhr, status, error) {
      console.log("Status: " + status); // Mostra o status do erro
      console.log("Erro: " + error); // Mostra a descrição do erro
      console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
    },
  });
});

/**
 *
 *
 *
 *
 */

// FUNÇÃO PARA ATUALIZAR A SENHA
$("#formRecupera").on("submit", function (event) {
  event.preventDefault();
  let pass = document.querySelector("#senha").value;
  let cPass = document.querySelector("#cSenha").value;
  let sPass = pass.length >= 8;
  let passwordsMatch = pass === cPass;

  if (passwordsMatch && sPass) {
    let formData = new FormData(this);

    $.ajax({
      url: "home/atualizarSenha",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          $("#respSenha").text("Senha alterada!");
          $("#respSenha").css("padding", "10px");
          $("#respSenha").css("text-align", "center");
          $("#respSenha").css("color", "green");
          $("#respSenha").css("background-color", "rgb(160, 252, 176)");
          $("#respSenha").css("text-align", "center");
          $("#respSenha").css("border-radius", "5px");
          $("#respSenha").css("margin-top", "15px");
          setTimeout(() => {
            $("#formRecuperacao").css("display", "none");
            $("#formEsqueceu").css("display", "none");
            $("#formSec").css("display", "flex");
          }, 2000);
        } else {
          $("#respSenha").text("Preencha todos os campos!");
          $("#respSenha").css("padding", "10px");
          $("#respSenha").css("text-align", "center");
          $("#respSenha").css("color", "darkred");
          $("#respSenha").css("background-color", "rgb(252, 160, 160)");
          $("#respSenha").css("text-align", "center");
          $("#respSenha").css("border-radius", "5px");
          $("#respSenha").css("margin-top", "15px");
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

/**
 *
 *
 *
 *
 */

// FUNÇÃO PARA CADASTRAR NOVO USUÁRIO
$("#formCadastro").on("submit", function (event) {
  event.preventDefault();
  let pass = document.querySelector("#sen").value;
  let cPass = document.querySelector("#cSen").value;
  let user_cad = document.querySelector("#user_cad").value;
  let sPass = pass.length >= 8;
  let sUser = user_cad.length >= 6;
  let passwordsMatch = pass === cPass;

  if (passwordsMatch && sPass && sUser) {
    let formData = new FormData(this);

    $.ajax({
      url: "home/cadastrarUser",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          $(".resposta").text("Cadastro efetuado!");
          $(".resposta").css("padding", "10px");
          $(".resposta").css("width", "100%");
          $(".resposta").css("text-align", "center");
          $(".resposta").css("color", "green");
          $(".resposta").css("background-color", "rgb(160, 252, 176)");
          $(".resposta").css("text-align", "center");
          $(".resposta").css("border-radius", "5px");
          $(".resposta").css("margin-top", "15px");
          setTimeout(() => {
            $("#formCad").css("display", "none");
            $("#formSec").css("display", "flex");
            $(".close").css("display", "none");
          }, 2000);
        } else if (response.status === "errorUser") {
          $("#erroUser").text("Usuário já cadastrado!");
        } else if (response.status === "errorEmail") {
          $("#erroEmail").text("E-mail já cadastrado!");
        } else if (response.status === "errorCell") {
          $("#erroCell").text("Celular já cadastrado!");
        } else if (response.status === "errorCpf") {
          $("#erroCpf").text("CPF já cadastrado!");
        }
      },
      error: function (xhr, status, error) {
        console.log("Status: " + status); // Mostra o status do erro
        console.log("Erro: " + error); // Mostra a descrição do erro
        console.log("Resposta do servidor: " + xhr.responseText); // Exibe a resposta completa
      },
    });
  } else {
    $(".resposta").text("Preencha todos os campos!");
    $(".resposta").css("padding", "10px");
    $(".resposta").css("width", "100%");
    $(".resposta").css("text-align", "center");
    $(".resposta").css("color", "darkred");
    $(".resposta").css("background-color", "rgb(252, 160, 160)");
    $(".resposta").css("text-align", "center");
    $(".resposta").css("border-radius", "5px");
    $(".resposta").css("margin-top", "15px");
  }
});

/**
 *
 *
 *
 *
 */

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

/**
 *
 *
 *
 *
 */

// FUNÇÃO PARA VERIFICAR FORMATAÇÃO DA SENHA DA TELA DE RECUPERAÇÃO
document.querySelector("#senha").addEventListener("keyup", function () {
  let pass = document.querySelector("#senha");
  const maxLength = 8;

  // Limitar o comprimento máximo
  if (pass.value.length > maxLength) {
    pass.value = pass.value.slice(0, maxLength);
  }

  // Verificar se o comprimento mínimo é atingido
  if (pass.value.length < maxLength) {
    pass.style.border = "1px solid red";
    pass.style.filter = "drop-shadow(0 0 2em rgba(255, 0, 0, 0.349))";
    document.querySelector("#respoSenha").textContent =
      "A senha deve ter 8 caracteres alfabéticos!";
    document.querySelector("#respoSenha").style.color = "red";
    document.querySelector("#respoSenha").style.fontSize = ".8em";
  } else {
    // Resetar estilos quando a senha for válida
    document.querySelector("#respoSenha").textContent = "";
    pass.style.border = "";
    pass.style.filter = "";
  }
});

document.querySelector("#cSenha").addEventListener("keyup", function () {
  const pass = document.querySelector("#senha").value;
  const cPass = document.querySelector("#cSenha");

  if (pass != cPass.value) {
    cPass.style.border = "1px solid red";
    cPass.style.filter = "drop-shadow(0 0 2em rgba(255, 0, 0, 0.349))";
    document.querySelector("#respSenha").textContent =
      "As senhas não são iguais!";
    document.querySelector("#respSenha").style.color = "red";
    document.querySelector("#respSenha").style.fontSize = ".8em";
  } else {
    document.querySelector("#respSenha").textContent = "";
    document.querySelector("#respSenha").style.color = "";
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
document.getElementById("senha").addEventListener("input", apenasAlfabeticos);
document.getElementById("cSenha").addEventListener("input", apenasAlfabeticos);

/**
 *
 *
 *
 *
 */

// FUNÇÃO PARA ABRIR A TELA DE LOGIN
function abrir() {
  document.querySelector("#formSec").style.display = "flex";
  document.querySelector("#formSec").style.transform = "translate(400px, 0)";
  if (window.innerWidth <= 900) {
    document.querySelector("#formPrin").style.display = "none";
    document.querySelector("#formSec").style.transform =
      "translate(-50%, -50%)";
  }
}

/**
 *
 *
 *
 *
 *  */

// FUNÇÃO PARA FECHAR A TELA DE LOGIN
function fechar() {
  document.querySelector("#formSec").style.display = "none";
  if (window.innerWidth <= 900) {
    document.querySelector("#formPrin").style.display = "flex";
  }
}

/**
 *
 *
 *
 *
 */

// FUNÇÃO PARA ABRIR A TELA DE RECUPERAÇÃO DE SENHA
function esqueceu() {
  document.querySelector("#formEsqueceu").style.display = "flex";
  document.querySelector("#formPrin").style.display = "none";
  document.querySelector("#formSec").style.display = "none";
}

/**
 *
 *
 *
 *
 */

// FUNÇÃO PARA ABRIR A TELA DE CADASTRO DE USUÁRIO
function cadastrar() {
  document.querySelector("#formCad").style.display = "flex";
  document.querySelector("#formPrin").style.display = "none";
  document.querySelector("#formSec").style.display = "none";
}

/**
 *
 *
 *
 *
 */

// FUNÇÃO PARA VOLTAR DA TELA DE CADASTRO PARA A TELA INICIAL
function backCad() {
  document.querySelector("#formCad").style.display = "none";
  document.querySelector("#formPrin").style.display = "flex";
}

/**
 *
 *
 *
 *
 */

// FUNÇÃO PARA VOLTAR DA TELA DE RECUPERAÇÃO PARA A TELA INICIAL
function backConf() {
  document.querySelector("#formEsqueceu").style.display = "none";
  document.querySelector("#formPrin").style.display = "flex";
}

/**
 *
 *
 *
 *
 */

// FUNÇÃO PARA APAGAR AS MENSAGENS DE ERRO DA TELA DE CADASTRO
function apagar() {
  document.querySelector("#erroUser").textContent = "";
  document.querySelector("#erroEmail").textContent = "";
  document.querySelector("#erroCell").textContent = "";
  document.querySelector("#erroCpf").textContent = "";
}

/**
 *
 *
 *
 *
 */

// Certifique-se de não declarar `let pergunta` mais de uma vez
if (typeof pergunta === "undefined") {
  let pergunta = [
    "Qual o nome da sua mãe?",
    "Qual a data do seu nascimento?",
    "Qual o CEP do seu endereço?",
  ];

  const indiceAleatorio = Math.floor(Math.random() * pergunta.length);
  const perguntaAleatoria = pergunta[indiceAleatorio];

  if (perguntaAleatoria === "Qual o nome da sua mãe?") {
    document.querySelector("#aleatoria").type = "text";
    document.querySelector("#conf").type = "text";
  }

  if (perguntaAleatoria === "Qual a data do seu nascimento?") {
    document.querySelector("#aleatoria").type = "date";
    document.querySelector("#conf").type = "date";
  }

  if (perguntaAleatoria === "Qual o CEP do seu endereço?") {
    document.querySelector("#aleatoria").type = "text";
    document.querySelector("#conf").type = "text";
    $("#aleatoria").mask("00000-000");
    $("#conf").mask("00000-000");
  }

  document.querySelector("#aleatoria").placeholder = perguntaAleatoria;
  document.querySelector("#conf").placeholder = perguntaAleatoria;
}

/**
 *
 *
 *
 *
 */

// FUNÇÃO PARA MASCARAR OS INPUTS
$(".cep").mask("00000-000");
$("#cep").mask("00000-000");
$("#cpf").mask("000.000.000-00");
$("#cell").mask("+55 (00) 00000-0000");

/**
 *
 *
 *
 *
 */

// FUNÇÕES PARA MOSTRAR E ESCONDER TELA DE LOADING
function mostrarLoad() {
  $("#fundo_load").css("display", "flex");
}

function esconderLoad() {
  $("#fundo_load").css("display", "none");
}
