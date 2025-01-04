<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/painelAdmin.css">
    <title>Document</title>
</head>

<body>
    <div id="logout">
        <p>Sair</p>
    </div>
    <div id="abrir_fechar" class="verde" onclick="mudarSitu()">
        <p></p>
    </div>
    <div id="fundo_load">
        <div id="load"></div>
    </div>
    <div id="fundo_adicionar_produtos">
        <div class="fundo_adicionar_produtos">
            <div class="back" onclick="esconderAdc()">
                <img src="assets/image/close.png" alt="Voltar">
            </div>
            <h1 style="text-align: center; margin-bottom: 15px; margin-left: 50px; margin-right: 50px;">Adicionar
                Produtos</h1>
            <div id="corpo_adicionar">
                <select name="categoria" id="categoria">
                    <option value="#" style="text-align: center;">-----Selecione-----</option>
                    <option value="prato">Prato</option>
                    <option value="bebida">Bebida</option>
                </select>
                <p id="res_select" style="text-align: center; color: red;"></p>
                <input type="text" name="nome_produto" id="nome_produto" placeholder="Nome do Produto">
                <input type="text" name="descricao_produto" id="descricao_produto" placeholder="Descrição do Produto">
                <input type="number" name="valor_produto" id="valor_produto" placeholder="Valor do Produto" min="0"
                    step="0.01">
                <label for="imagem_produto" class="file">
                    <span class="span1">Selecione a Imagem</span>
                    <span class="span">SELECIONAR</span>
                </label>
                <input type="file" name="imagem_produto" id="imagem_produto">
                <button type="button" id="adicionar_produto">Adicionar</button>
            </div>
        </div>
    </div>
    <div id="fundo_editar_produtos">
        <div class="fundo_editar_produtos">
            <div class="back" onclick="esconderEdit()">
                <img src="assets/image/close.png" alt="Voltar">
            </div>
            <h1 style="text-align: center; margin-bottom: 15px; margin-left: 50px; margin-right: 50px;">Editar
                Produtos</h1>
            <div id="corpo_editar">
                <select name="categoria_edit" id="categoria_edit">
                    <option value="#" style="text-align: center;">-----Selecione-----</option>
                    <option value="prato">Prato</option>
                    <option value="bebida">Bebida</option>
                </select>
                <p id="res_select_edit" style="text-align: center; color: red;"></p>
                <input type="text" name="nome_produto_edit" id="nome_produto_edit" placeholder="Nome do Produto">
                <input type="text" name="descricao_produto_edit" id="descricao_produto_edit"
                    placeholder="Descrição do Produto">
                <input type="number" name="valor_produto_edit" id="valor_produto_edit" placeholder="Valor do Produto"
                    min="0" step="0.01">
                <label for="imagem_produto_edit" class="file">
                    <span class="span1_edit">Selecione a Imagem</span>
                    <span class="span">SELECIONAR</span>
                </label>
                <input type="file" name="imagem_produto_edit" id="imagem_produto_edit">
                <input type="hidden" name="id_produto" id="id_produto">
                <button type="button" id="editar_produto">Editar</button>
            </div>
        </div>
    </div>
    <div id="fundo_pedidos">
        <div class="fundo_pedidos">
            <div class="back" onclick="fecharPedidos()">
                <img src="assets/image/close.png" alt="Voltar">
            </div>
            <div class="refresh" onclick="mostrarPedidos()">
                <img src="assets/image/refresh.png" alt="Voltar">
            </div>

            <h1 style="text-align: center;">Seus Pedidos</h1>
            <div class="barra">
                <span id="lupa"><img src="assets/image/lupa.png" alt="lupa"></span>
                <input type="search" name="pesquisar_pedido" id="pesquisar_pedido" class="search"
                    placeholder="Buscar um pedido">
            </div>
            <div id="pedidos_corpo">
                <div class="pedidos"></div>
            </div>
        </div>
    </div>
    <div id="fundo_produtos">
        <div class="fundo_produtos">
            <div class="back" onclick="fecharProdutos()">
                <img src="assets/image/close.png" alt="Voltar">
            </div>
            <div id="adicionar">
                <img src="assets/image/add.png" alt="Adicionar Produto">
            </div>
            <h1 style="text-align: center;">Produtos</h1>
            <div class="barra">
                <span id="lupa"><img src=" assets/image/lupa.png" alt="lupa"></span>
                <input type="search" name="pesquisar_produto" id="pesquisar_produto" class="search"
                    placeholder="Buscar um produto">
            </div>
            <div id="produtos_corpo">
                <div id="tabela_produtos">

                </div>
            </div>
        </div>
    </div>
    <div id="fundo_historico">
        <div class="fundo_historico">
            <div class="back" onclick="esconderHistorico()">
                <img src="assets/image/close.png" alt="Voltar">
            </div>
            <div class="refresh" onclick="limparHistorico()">
                <img src="assets/image/delete.png" alt="Voltar">
            </div>
            <h1 style="text-align: center;">Histórico de Pedidos</h1>
            <div class="barra">
                <span id="lupa"><img src=" assets/image/lupa.png" alt="lupa"></span>
                <input type="search" name="pesquisar_registro" id="pesquisar_registro" class="search"
                    placeholder="Buscar registro">
            </div>
            <div id="corpo_historico">
                <div id="tabela_historico">

                </div>
                <div class="paginacao">
                    <button type="button" onclick="diminuirPagHistorico()">Anterior</button>
                    <input type="number" name="pag" class="pag" readonly value="1">
                    <button type="button" onclick="aumentarPagHistorico()">Próximo</button>
                </div>
            </div>
        </div>
    </div>
    <div id="fundo_funcionarios">
        <div class="fundo_funcionarios">
            <div class="back" onclick="esconderFuncionarios()">
                <img src="assets/image/close.png" alt="Voltar">
            </div>
            <h1 style="text-align: center; margin-bottom: 15px; margin-left: 50px; margin-right: 50px;">Funcionários
                Cadastrados</h1>
            <div id="adicionarFunc" onclick="mostrarFuncionarios()">
                <img src="assets/image/add.png" alt="Adicionar Funcionário">
            </div>
            <div class="barra">
                <span id="lupa"><img src=" assets/image/lupa.png" alt="lupa"></span>
                <input type="search" name="pesquisar_funcionario" id="pesquisar_funcionario" class="search"
                    placeholder="Buscar funcionario">
            </div>
            <div id="corpo_funcionario">
                <div id="tabela_funcionarios">

                </div>
            </div>
        </div>
    </div>
    <div id="fundo_usuarios">
        <div class="fundo_usuarios">
            <div class="back" onclick="esconderUsuarios()">
                <img src="assets/image/close.png" alt="Voltar">
            </div>
            <h1 style="text-align: center; margin-bottom: 15px; margin-left: 50px; margin-right: 50px;">Usuários
                Cadastrados</h1>
            <div class="barra">
                <span id="lupa"><img src=" assets/image/lupa.png" alt="lupa"></span>
                <input type="search" name="pesquisar_usuario" id="pesquisar_usuario" class="search"
                    placeholder="Buscar usuário">
            </div>
            <div id="corpo_usuario">
                <div id="tabela_usuarios">

                </div>
            </div>
        </div>
    </div>
    <div id="fundo_atividades">
        <div class="fundo_atividades">
            <div class="back" onclick="esconderAtividades()">
                <img src="assets/image/close.png" alt="Voltar">
            </div>
            <h1 style="text-align: center;">Histórico de Atividades</h1>
            <div class="barra">
                <span id="lupa"><img src=" assets/image/lupa.png" alt="lupa"></span>
                <input type="search" name="pesquisar_atividade" id="pesquisar_atividade" class="search"
                    placeholder="Buscar atividade">
            </div>
            <div id="corpo_historico">
                <div id="tabela_atividades">

                </div>
                <div class="paginacao">
                    <button type="button" onclick="diminuirPagAtividades()">Anterior</button>
                    <input type="number" name="pag" class="pag" readonly value="1">
                    <button type="button" onclick="aumentarPagAtividades()">Próximo</button>
                </div>
            </div>
        </div>
    </div>
    <div id="fundo_adicionar_user">
        <div class="fundo_adicionar_user">
            <div class="back" onclick="esconderAdicionar()">
                <img src="assets/image/close.png" alt="Voltar">
            </div>
            <h1 style="text-align: center;">Adicionar Funcionário</h1>
            <div id="corpo_adicionar_user">
                <!-- Seus inputs -->
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
                <p class="resposta" style="text-align: center;"></p>
            </div>
            <div class="btn-container">
                <div class="btn">
                    <button id="prox" onclick="cadastrarFunc()">Cadastrar</button>
                </div>
                <div class="btn">
                    <button type="reset">Limpar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="fundo_financas">
        <div class="fundo_financas">
            <div class="back" onclick="esconderFinancas()">
                <img src="assets/image/close.png" alt="Voltar">
            </div>
            <div class="refresh" onclick="mostrarFinancas()">
                <img src="assets/image/refresh.png" alt="Voltar">
            </div>
            <h1 style="text-align: center; margin-bottom: 15px;">Finanças</h1>
            <div id="corpo_financas">
                <div id="vendas_clientes" class="graficos">
                    <h1 style="text-align: center;">Principais Clientes</h1>
                    <canvas id="clientes"></canvas>
                </div>
                <div id="vendas_pratos" class="graficos">
                    <h1 style="text-align: center;">Principais Pratos</h1>
                    <canvas id="pratos"></canvas>
                </div>
                <div id="vendas_bebidas" class="graficos">
                    <h1 style="text-align: center;">Principais Bebidas</h1>
                    <canvas id="bebidas"></canvas>
                </div>
                <div id="vendas_periodo" class="graficos">
                    <h1>Vendas por período</h1>
                    <div id="filtro">
                        <div id="filtro_inicio">
                            <label for="dataInicio">Data Início:</label>
                            <input type="date" id="dataInicio" name="dataInicio">
                        </div>
                        <div id="filtro_final">
                            <label for="dataFim">Data Fim:</label>
                            <input type="date" id="dataFim" name="dataFim"
                                style="margin-left: 11.5px; margin-top: 5px;">
                        </div>
                        <button type="button" onclick="filtrarPorPeriodo()" id="btnFil">Filtrar</button>
                    </div>

                    <canvas id="periodo"></canvas>
                </div>
                <div id="vendas_periodo_valor" class="graficos">
                    <h1>Valor das Vendas</h1>
                    <canvas id="periodo_valor"></canvas>
                </div>

            </div>
        </div>
    </div>
    <section id="corpo">
        <h1 style="text-align: center;">Painel de Controle</h1>
        <div id="corpo_opcoes">
            <div id="opcoes">
                <div id="pedidos">
                    <div id="mostrar_pedidos" class="fundo_opcao" onclick="mostrarPedidos()">
                        <h1 class="opcao">Pedidos</h1>
                    </div>
                    <img src="assets/image/pedidos.png" alt="Pedidos" id="pedidos">
                </div>
                <div id="produtos">
                    <div id="mostrar_produtos" class="fundo_opcao" onclick="mostrarProdutos()">
                        <h1 class="opcao">Produtos</h1>
                    </div>
                    <img src="assets/image/produtos.jpg" alt="Produtos" id="produtos">
                </div>
                <div id="historico">
                    <div id="mostrar_historico" class="fundo_opcao" onclick="mostrarHistorico(1)">
                        <h1 class="opcao">Histórico</h1>
                    </div>
                    <img src="assets/image/historico.png" alt="Historico" id="historico">
                </div>
                <?php if ($_SESSION['tipo_usuario'] === "master") : ?>
                    <div id="financas">
                        <div id="mostrar_financas" class="fundo_opcao" onclick="mostrarFinancas()">
                            <h1 class="opcao">Finanças</h1>
                        </div>
                        <img src="assets/image/financas.webp" alt="Finanças" id="financas">
                    </div>
                    <div id="atividades">
                        <div id="mostrar_atividades" class="fundo_opcao" onclick="mostrarAtividades(1)">
                            <h1 class="opcao">Atividades</h1>
                        </div>
                        <img src="assets/image/atividades.png" alt="Atividades" id="atividades">
                    </div>
                    <div id="adicionar_user">
                        <div id="mostrar_adicionar_user" class="fundo_opcao" onclick="getFuncionarios()">
                            <h1 class="opcao">Adicionar Funcionário</h1>
                        </div>
                        <img src="assets/image/adicionar.png" alt="Adicionar Funcionário" id="adicionar_user">
                    </div>
                    <div id="ver_usuarios">
                        <div id="mostrar_usuarios" class="fundo_opcao" onclick="getUsuarios()">
                            <h1 class="opcao">Usuários</h1>
                        </div>
                        <img src="assets/image/user.png" alt="Consultar Usuários" id="consultar_usuarios">
                    </div>
                <?php else: ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <script src="assets/js/painelAdmin.js"></script>
</body>

</html>