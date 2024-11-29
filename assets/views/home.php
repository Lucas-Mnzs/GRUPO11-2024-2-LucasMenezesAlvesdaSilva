<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/home.css">
    <title>Document</title>
</head>

<body>
    <div id="fundo_load">
        <div id="load"></div>
    </div>
    <section id="form">
        <form action="" class="formCont" id="formPrin">
            <h1>Verifique a Disponibilidade</h1><br>
            <label for="pesquisar" style="display: block;">Procure por seu endereço</label>
            <input type="text" name="pesquisar" class="cep" placeholder="00000-000" onkeyup="buscarCEP()">
            <input type="text" name="rua" class="rua" placeholder="Rua" readonly>
            <input type="text" name="bairro" class="bairro" placeholder="Bairro" readonly>
            <input type="text" name="cidade" class="cidade" placeholder="Cidade" readonly>
            <input type="text" name="estado" class="estado" placeholder="Estado" readonly>
            <input type="text" name="num" class="num" placeholder="Número" readonly>
            <p id="resposta"></p>
            <button type="submit">Procurar</button>
            <p onclick="abrir()" id="abrir">Já possui cadastro?</p>
        </form>
    </section>
    <section class="formCont" id="formSec">
        <form action="" id="formUser">
            <h1>Login</h1>
            <div class="close" onclick="fechar()">
                <p>X</p>
            </div>
            <input type="text" name="user" id="user" placeholder="Usuário">
            <input type="password" name="pass" id="pass" placeholder="Senha">
            <p id="respostaLogin"></p>
            <p id="esqSenha" onclick="esqueceu()">Esqueceu a Senha?</p>
            <button type="submit" style="margin-bottom: 5px;">Entrar</button>
            <p id="cadas" onclick="cadastrar()">Cadastre-se</p>
        </form>
    </section>
    <section class="formCont" id="formSegunda">
        <form action="" id="formAut">
            <h1>Confirme a sua identidade</h1>
            <input type="text" name="conf" id="conf">
            <p id="tentativas"></p>
            <button type="submit">Confirmar</button>
        </form>
    </section>
    <section class="formCont" id="formEsqueceu">
        <form action="" id="formEsquece">
            <div class="backConf" onclick="backConf()">
                <img src="assets/image/back.png" alt="">
            </div>
            <h1>Confirme a Identidade</h1>
            <input type="text" name="usuario" id="usuario" placeholder="Usuário">
            <input type="text" name="email" id="email" placeholder="E-mail">
            <input type="text" name="aleatoria" id="aleatoria">
            <p id="respostaEsqueceu"></p>
            <button type="submit">Confirmar</button>
        </form>
    </section>
    <section class="formCont" id="formRecuperacao">
        <form action="" id="formRecupera">
            <h1>Redefina a Senha</h1>
            <input type="password" name="senha" id="senha" placeholder="Digite a nova senha">
            <p id="respoSenha"></p>
            <input type="password" name="cSenha" id="cSenha" placeholder="Repita a senha">
            <p id="respSenha"></p>
            <button type="submit">Redefinir</button>
        </form>
    </section>
    <div id="form_cad_fundo">
        <section class="formCont" id="formCad">
            <div class="backCad" onclick="backCad()">
                <img src="assets/image/back.png" alt="">
            </div>
            <h1>Cadastre-se</h1>
            <form action="" id="formCadastro">
                <div class="inpu" id="in">
                    <input type="text" name="nome" id="pNome" placeholder="Nome">
                </div>
                <div class="inpu">
                    <input type="text" name="sNome" id="sNome" placeholder="Sobrenome">
                </div>
                <div class="inpu">
                    <input type="text" name="nomeMae" id="nomeMae" placeholder="Nome completo da Mãe">
                </div>
                <div class="inpu">
                    <input type="date" name="dataNascimento" id="dataNascimento">
                </div>
                <div class="inpu">
                    <input type="text" name="cpf" id="cpf" placeholder="CPF" onkeyup="verificarCPF()"
                        onkeyup="apagar()">
                    <p id="erroCpf"></p>
                </div>
                <div class="inpu" id="im">
                    <input type="text" name="cell" id="cell" placeholder="Número para Contato" onkeyup="apagar()">
                    <p id="erroCell"></p>
                </div>
                <div class="inpu">
                    <input type="email" name="e_mail" id="e_mail" placeholder="E-mail" onkeyup="apagar()">
                    <p id="erroEmail"></p>
                </div>
                <div class="inpu">
                    <input type="text" name="cep" id="cep" onkeyup="buscaCEP()" placeholder="CEP">
                </div>
                <div class="inpu">
                    <input type="text" name="estado" id="estado" placeholder="Estado" readonly>
                </div>
                <div class="inpu">
                    <input type="text" name="cidade" id="cidade" placeholder="Cidade" readonly>
                </div>
                <div class="inpu">
                    <input type="text" name="bai" id="bairro" placeholder="Bairro" readonly>
                </div>
                <div class="inpu">
                    <input type="text" name="rua_cad" id="rua_cad" placeholder="Logradouro" readonly>
                </div>
                <div class="inpu">
                    <input type="text" name="numero" id="numero" placeholder="Número" readonly>
                </div>
                <div class="inpu">
                    <input type="text" name="complemento" id="complemento" placeholder="Complemento(Opcional)">
                </div>
                <div class="inpu">
                    <input type="text" name="referencia" id="referencia" placeholder="Ponto de Referência">
                </div>
                <div class="inpu">
                    <input type="text" name="user_cad" id="user_cad" placeholder="Usuário" onkeyup="apagar()">
                    <p id="erroUser"></p>
                </div>
                <div class="inpu" id="bgSenha">
                    <input type="password" name="sen" id="sen" placeholder="Senha">
                    <p id="erroSen"></p>
                </div>
                <div class="inpu" id="bgCsenha">
                    <input type="password" name="cSen" id="cSen" placeholder="Confirme a Senha">
                    <p id="erroCsen"></p>
                </div>
                <p class="resposta"></p>
                <div class="btn">
                    <button id="prox">Próximo</button>
                    <button type="reset">Limpar</button>
                </div>
            </form>
        </section>
    </div>
    <script src="assets/js/home.js"></script>
</body>

</html>