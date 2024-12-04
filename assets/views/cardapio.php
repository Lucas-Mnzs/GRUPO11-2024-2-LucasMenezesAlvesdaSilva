<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/cardapio.css">
    <title>Document</title>
</head>

<body>
    <div id="fundo_load">
        <div id="load"></div>
    </div>
    <div id="logout">
        <p>Sair</p>
    </div>
    <div class="carrinhoIcon" onclick="getCarrinho()">
        <img src="assets/image/carrinho.png" alt="Carrinho">
        <div class="qtd">0</div>
    </div>
    </div>
    <div class="der" onclick="mostrarDer()">
        <img src="assets/image/der.png" alt="Diagrama Icon">
    </div>
    <div id="der">
        <div id="der_img">
            <img src="assets/image/diagrama.png" alt="Diagrama">
            <img src="assets/image/close.png" alt="Fechar diagrama" id="closeDiagrama" onclick="esconderDer()">
        </div>
    </div>
    <div class="complemento" onclick="fecharComplemento()">
        <div id="complemento">
        </div>
    </div>
    <div class="fundoFechado" onclick="fechado()">
        <div class="fechado">
            <h1 style="text-align: center;">Estamos fechados no momento</h1>
            <div class="fechado_corpo">
                <img src="assets/image/sad.png" alt="Emoji Triste">
            </div>
            <p style="text-align: center;">Nosso horário de funcionamento é de seg a sáb das 11h às 15h</p>
        </div>
    </div>
    <div class="fundoCarrinho" onclick="fecharCarrinho()">
        <div class="carrinho">
            <h1 style="text-align: center;">Carrinho</h1>
            <div class="carrinho_corpo">

            </div>
            <button type="button" id="pagamento" onclick="formaPagamento()">Forma de pagamento</button>
        </div>
    </div>
    <div id="fundo_troco">
        <div class="troco">
            <h1 style="text-align: center;">Precisa de troco?</h1>
            <div id="valor_troco">
                <input type="number" name="troco" id="troco" placeholder="Troco para quanto?">
                <p id="resTroco" style="margin-bottom: 15px;"></p>
                <button type="button" style="margin-bottom: 15px;" id="enviarTroco">Enviar</button>
                <button type="button" id="naoTroco">Não preciso</button>
            </div>
        </div>
    </div>
    <div id="fundo_endereco">
        <div id="trocar">
            <div id="voltar_trocar">
                <img src="assets/image/back.png" height="40px" width="40px" style="cursor: pointer;" alt="Voltar">
            </div>
            <h1 style="text-align: center;">Novo Endereço</h1>
            <div id="corpo_trocar">
                <input type="text" name="trocar_cep" id="trocar_cep" placeholder="CEP" onkeyup="buscarCEP()">
                <input type="text" name="trocar_rua" id="trocar_rua" placeholder="Rua">
                <input type="text" name="trocar_bairro" id="trocar_bairro" placeholder="Bairro">
                <input type="text" name="trocar_cidade" id="trocar_cidade" placeholder="Cidade">
                <input type="text" name="trocar_estado" id="trocar_estado" placeholder="Estado">
                <input type="text" name="trocar_numero" id="trocar_numero" placeholder="Número">
                <input type="text" name="trocar_complemento" id="trocar_complemento" placeholder="Complemento">
                <input type="text" name="trocar_referencia" id="trocar_referencia" placeholder="Referência">
                <p id="res_trocar"></p>
                <button type="button" id="trocar_endereco">Mudar endereço</button>
            </div>
        </div>
    </div>
    <div id="fundo_pagamento" onclick="fecharPagamento()">
        <div class="pagamento">
            <h1 style="text-align: center; margin-bottom: 10px;">Finalize o seu pedido</h1>
            <div id="pagamento_corpo">
                <label for="forma_entrega">Forma de entrega</label><br>
                <select name="forma_entrega" id="forma_entrega">
                    <option value="#" style="text-align: center;">-----Selecione-----</option>
                    <option value="entrega">Entrega</option>
                    <option value="retirada">Retirada</option>
                </select>
                <p id="res_entrega" style="text-align: center; font-size: .9em; color: red;"></p><br>
                <label for="forma_pagamento">Forma de Pagamento</label><br>
                <select name="forma_pagamento" id="forma_pagamento">
                    <option value="#" style="text-align: center;">-----Selecione-----</option>
                    <option value="dinheiro">Dinheiro</option>
                    <option value="pix">Pix</option>
                    <option value="c_visa">Crédito - Visa</option>
                    <option value="c_master">Crédito - MasterCard</option>
                    <option value="c_elo">Crédito - Elo</option>
                    <option value="d_visa">Débito - Visa</option>
                    <option value="d_master">Débito - MasterCard</option>
                    <option value="d_elo">Débito - Elo</option>
                </select>
                <p id="res_pagamento" style="text-align: center; font-size: .9em; color: red;"></p>
                <div id="endereco" style="margin-top: 10px;">
                    <h2 style="text-align: center;">Endereço</h2>
                    <div id="endereco_corpo">
                        <div class="endereco">
                            <div id="rua_num">
                                <p style="font-weight: bold;">
                                    <?php echo $_SESSION['rua'] . ', ' . $_SESSION['numero'] ?></p>
                            </div>
                            <div id="bairro_cidade_estado">
                                <p><?php echo $_SESSION['bairro'] . ' - ' . $_SESSION['cidade'] . '/' . $_SESSION['estado'] ?>
                                </p>
                            </div>
                        </div>
                        <div id="mudar_endereco">
                            <button type="button" onclick="abrirTroca()">Trocar</button>
                        </div>
                    </div>
                </div>
                <div id="itens_pedido" style="margin-top: 10px;">
                    <h2 style="text-align: center;">Pedido</h2>
                    <div class="itens_pedido">

                    </div>

                </div>
                <div id="valor_pedido" style="margin-top: 10px;">
                    <h2 style="text-align: center;">Valor Total</h2>
                    <div class="valor_pedido">
                        <p id="valor"></p>
                    </div>

                </div>
                <button type="button" id="finalizar" onclick="finalizar()">Enviar Pedido</button>
            </div>
        </div>
    </div>

    <div id="fundo_situacao">
        <div id="corpo_situacao">
            <h1>Situação do pedido</h1>
            <p id="situacao" style="margin-bottom: 25px; text-align: center;"></p>
            <div id="tres_pontos">
                <div id="ponto_1" class="pontos"></div>
                <div id="ponto_2" class="pontos"></div>
                <div id="ponto_3" class="pontos"></div>
            </div>
        </div>
    </div>
    <section id="corpo">
        <div id="imgPrin">
            <img src="assets/image/imagemPrinLogo.jpg" alt="Imagem Principal">
        </div>
        <div class="usuario">
            <h2 id="bv">Seja bem-vindo(a), <b><?php echo $_SESSION['nome'] ?></b>!</h2>
        </div>
        <div id="informacoes">
            <div id="hora_aberto">
                <p id="hora">Funcionamento: 11 - 15h</p>
                <p id="situacao_aberto"></p>
            </div>
            <div id="entrega_taxa">
                <p id="entrega">Entrega: 30 - 60min</p>
                <b>
                    <div id="taxa">
                        <p>Taxa: <?php echo $_SESSION['taxa'] ?></p>
                    </div>
                </b>
            </div>
            <div id="barra">
                <span id="lupa"><img src="assets/image/lupa.png" alt="lupa"></span>
                <input type="search" name="pesquisar" id="pesquisar" placeholder="Buscar no Cardápio">
            </div>
        </div>
        <div id="destaques">
            <h2>Destaques</h2>
            <div class="destaques">
            </div>
        </div>
        <div id="categorias">
            <a href="#pratos">
                <h3>Pratos</h3>
            </a>
            <a href="#bebidas">
                <h3>Bebidas</h3>
            </a>
        </div>
        <div id="pratos">
            <h2>Pratos</h2>
            <div class="pratos">
            </div>
        </div>
        <div id="bebidas">
            <h2>Bebidas</h2>
            <div class="bebidas">
            </div>
        </div>
    </section>
    <script src="assets/js/cardapio.js"></script>
</body>

</html>