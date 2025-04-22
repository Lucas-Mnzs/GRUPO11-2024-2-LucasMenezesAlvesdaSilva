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
    <div class="carrinhoIcon" onclick="getCarrinho()">
        <img src="assets/image/carrinho.png" alt="Carrinho">
        <div class="qtd">0</div>
    </div>
    <div class="pedidos" onclick="getPedidos(<?php echo $_SESSION['id_usuario'] ?>)">
        <img src="assets/image/order.png" alt="Pedidos">
    </div>
    <div class="perfil" onclick="mostrarPerfil(<?php echo $_SESSION['id_usuario'] ?>)">
        <img src="assets/image/perfil.png" alt="Diagrama Icon">
    </div>
    <div id="pedidos">
        <div id="pedidos_fundo">
            <img src="assets/image/close.png" alt="Fechar pedidos" id="closePedido" onclick="esconderPedidos()">
            <div id="dados_pedidos">
                <h1 style="text-align: center;">Seus pedidos</h1>
                <div id="historico">

                </div>
            </div>
        </div>
    </div>
    <div id="perfil">
        <div id="perfil_fundo">
            <img src="assets/image/close.png" alt="Fechar perfil" id="closePerfil" onclick="esconderPerfil()">
            <div id="dados_perfil">
                <h1 style="text-align: center;">Dados do perfil</h1>
                <div id="dados">
                </div>
                <p id="resposta_perfil" style="margin-bottom: 15px;"></p>
            </div>
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
                <input type="text" name="trocar_cep" id="trocar_cep" placeholder="CEP" oninput="cepInput()">
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
                    <option value="Entrega">Entrega</option>
                    <option value="Retirada">Retirada</option>
                </select>
                <p id="res_entrega" style="text-align: center; font-size: .9em; color: red;"></p><br>
                <label for="forma_pagamento">Forma de Pagamento</label><br>
                <select name="forma_pagamento" id="forma_pagamento">
                    <option value="#" style="text-align: center;">-----Selecione-----</option>
                    <option value="Dinheiro">Dinheiro</option>
                    <option value="Pix">Pix</option>
                    <option value="Crédito visa">Crédito - Visa</option>
                    <option value="Credito master">Crédito - MasterCard</option>
                    <option value="Credito elo">Crédito - Elo</option>
                    <option value="Débito visa">Débito - Visa</option>
                    <option value="Débito master">Débito - MasterCard</option>
                    <option value="Débito elo">Débito - Elo</option>
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
            <p id="numero_pedido" style="text-align: center; margin: 0px 25px;"></p>
            <p id="situacao" style="text-align: center;"></p>
            <a href="https://wa.me/5521990420932?text=Olá! Eu gostaria de tirar uma dúvida!" target="_blank"
                style="margin: 25px; text-align: center; display: block;">Precisa de
                ajuda?</a>
            <div id="tres_pontos">
                <div id="ponto_1" class="pontos"></div>
                <div id="ponto_2" class="pontos"></div>
                <div id="ponto_3" class="pontos"></div>
            </div>
        </div>
    </div>
    <section id="corpo">
        <div id="imgPrin">
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
    <div id="navegacao">
        <div class="topicos" onclick="getPedidos(<?php echo $_SESSION['id_usuario'] ?>)">
            <img src="assets/image/order.png" alt="Pedidos">
        </div>
        <div class="topicos" onclick="getCarrinho()">
            <img src="assets/image/carrinho.png" alt="Carrinho">
            <div id="qtd">0</div>
        </div>
        <div class="topicos" onclick="mostrarPerfil(<?php echo $_SESSION['id_usuario'] ?>)">
            <img src="assets/image/perfil.png" alt="Perfil">
        </div>
    </div>
    <footer>
        <hr>
        <div id="conteudo_footer">
            <div id="sobre">
                <h1>Sobre nós</h1>
                <p>No Cantinho Suam, oferecemos refeições caseiras de qualidade com a praticidade do delivery. Nosso
                    cardápio é pensado para agradar todos os gostos, com opções frescas e saborosas preparadas com
                    carinho. Estamos aqui para tornar o seu dia mais fácil e delicioso, entregando quentinhas quentinhas
                    no conforto da sua casa ou trabalho. Experimente o sabor de uma comida feita com amor!</p>
            </div>
            <div id="redes">
                <h1>Redes</h1>
                <div id="icones">
                    <div class="icon_logo" style="margin-bottom: 15px;">
                        <a href="https://wa.me/5521990420932?text=Olá! Eu gostaria de tirar uma dúvida!"
                            target="_blank">
                            <img src="assets/image/icon_wpp.png" alt="logo WhatsApp" class="logos"
                                id="logoWpp"></img><span>+55(21)99042-0932</span></a>
                    </div>
                    <div class="icon_logo">
                        <a href="#"><img src="assets/image/icon_insta.png" alt="Logo instagram" class="logos"
                                id="logoInsta"></img><span>Instagram</span></a>
                    </div>
                </div>
            </div>
            <div id="endereco">
                <h1 style="text-align: center; margin-bottom: 5px;">Nosso endereço</h1>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3678.8335259780206!2d-43.31605242516302!3d-22.771558533051206!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x997aa3bcff72fb%3A0x77d5c3defa357629!2sR.%20Dr.%20Furquim%20Mendes%2C%20990%20-%20Vila%20Centenario%2C%20Duque%20de%20Caxias%20-%20RJ%2C%2025030-170!5e0!3m2!1spt-BR!2sbr!4v1733319413632!5m2!1spt-BR!2sbr"
                    width="300" height="200" style="border-radius:5px; border: none;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
        <hr>
        <div id="copy">
            <p>&copy; 2024 Cantinho Suam. Todos os direitos reservados.</p>
        </div>
    </footer>
    <script src="assets/js/cardapio.js"></script>
</body>

</html>