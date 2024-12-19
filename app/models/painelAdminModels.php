<?php

namespace Name\Models;

use Name\Models\Config\Conexao;
use PDO;

class painelAdminModels
{
    private $con;

    public function __construct()
    {
        $this->con = Conexao::getConexao();
    }

    public function abrir_fechar()
    {
        // Obter a situação atual do banco de dados
        $cmd_situ = $this->con->prepare("
        SELECT situ FROM usuarios
        WHERE idUsuarios = ?
    ");
        $cmd_situ->execute([59]);
        $situ = $cmd_situ->fetchColumn();

        // Alterna entre "Aberto" e "Fechado"
        $situ = ($situ === "Aberto") ? "Fechado" : "Aberto";

        // Atualiza a situação no banco de dados
        $cmd = $this->con->prepare("
        UPDATE usuarios
        SET situ = ?
        WHERE idUsuarios = ?
    ");
        $cmd->execute([$situ, 59]);

        // Retorna a resposta em JSON
        echo json_encode(['status' => 'success']);
    }

    public function getSitu()
    {
        // Obter a situação atual do banco de dados
        $cmd_situ = $this->con->prepare("
        SELECT situ FROM usuarios
        WHERE idUsuarios = ?
    ");
        $cmd_situ->execute([59]);
        $situ = $cmd_situ->fetchColumn();

        // Retorna a situação como string
        return $situ;
    }

    public function getPedidos()
    {
        $dados = array();
        $cmd_pedidos = $this->con->prepare("
            SELECT 
                pedidos.id_pedido,
                pedidos.data_hora,
                pedidos.situacao,
                pedidos.tipo_entrega,
                pedidos.forma_pagamento,
                pedidos.valor_total,
                pedidos.troco,
                carrinho.id_usuario,
                usuarios.pnome,
                usuarios.cell,
                usuarios.rua,
                usuarios.numero,
                usuarios.complemento,
                usuarios.referencia,
                GROUP_CONCAT(CONCAT(itens_carrinho.qtd, 'x ', produtos.nome, '<br>', 'obs.: ', itens_carrinho.obs) SEPARATOR '<br>') AS produtos
            FROM 
                pedidos
            JOIN 
                carrinho ON pedidos.id_carrinho = carrinho.id_carrinho
            JOIN 
                usuarios ON carrinho.id_usuario = usuarios.idUsuarios
            JOIN 
                itens_carrinho ON carrinho.id_carrinho = itens_carrinho.id_carrinho
            JOIN 
                produtos ON itens_carrinho.id_produto = produtos.idProdutos
            WHERE pedidos.ativo = 'sim'
            GROUP BY 
                pedidos.id_pedido
            ORDER BY pedidos.id_pedido ASC
        ");
        $cmd_pedidos->execute();
        $dados = $cmd_pedidos->fetchall(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function aceitar($id)
    {
        $cmd_pedidos = $this->con->prepare("
        SELECT 
            pedidos.id_pedido,
            carrinho.id_usuario,
            itens_carrinho.id_produto,
            pedidos.valor_total,
            pedidos.forma_pagamento,
            itens_carrinho.qtd
        FROM 
            pedidos
        JOIN 
            carrinho ON pedidos.id_carrinho = carrinho.id_carrinho
        JOIN 
            itens_carrinho ON carrinho.id_carrinho = itens_carrinho.id_carrinho
        WHERE carrinho.id_usuario = ? AND pedidos.ativo = 'sim'
    ");
        $cmd_pedidos->execute([$id]);
        $dados = $cmd_pedidos->fetchAll(PDO::FETCH_ASSOC);

        $ids_pedidos = []; // Array para armazenar ids_pedidos únicos

        foreach ($dados as $dado) {
            $cmd_historico = $this->con->prepare("
            INSERT INTO historico (id_pedido, id_usuario, id_produto, valor, pagamento, qtd)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
            $cmd_historico->execute([
                $dado['id_pedido'],
                $dado['id_usuario'],
                $dado['id_produto'],
                $dado['valor_total'],
                $dado['forma_pagamento'],
                $dado['qtd'],
            ]);

            if (!in_array($dado['id_pedido'], $ids_pedidos)) {
                $ids_pedidos[] = $dado['id_pedido']; // Armazena id_pedido se ainda não estiver no array
            }
        }

        // Inserção dos logs fora do foreach
        foreach ($ids_pedidos as $id_pedido) {
            $cmd_logs = $this->con->prepare("
            INSERT INTO logs (id_usuario, acao)
            VALUES(?, ?)
        ");
            $cmd_logs->execute([$_SESSION['id_usuario'], "Aceitou o pedido de ID: $id_pedido!"]);
        }

        $cmd_carrinho = $this->con->prepare("
        SELECT id_carrinho FROM carrinho
        WHERE id_usuario = ?
    ");
        $cmd_carrinho->execute([$id]);
        $id_carrinho = $cmd_carrinho->fetchColumn();

        $cmd_aceitar = $this->con->prepare("
        UPDATE pedidos SET situacao = 'O pedido está em preparação!'
        WHERE id_carrinho = ?
    ");
        $cmd_aceitar->execute([$id_carrinho]);

        return json_encode(['status' => 'success']);
    }



    public function rota($id)
    {
        $cmd_carrinho = $this->con->prepare("
        SELECT id_carrinho FROM carrinho
        WHERE id_usuario = ?
        ");
        $cmd_carrinho->execute([$id]);
        $id_carrinho = $cmd_carrinho->fetchColumn();

        $cmd_pedido = $this->con->prepare("
        SELECT id_pedido FROM pedidos
        WHERE id_carrinho = ?
        AND ativo = 'sim'
        ");
        $cmd_pedido->execute([$id_carrinho]);
        $id_pedido = $cmd_pedido->fetchColumn();

        $cmd_logs = $this->con->prepare("
            INSERT INTO logs (id_usuario, acao)
            VALUES(?, ?)
        ");
        $cmd_logs->execute([$_SESSION['id_usuario'], "Pôs em rota o pedido de ID: $id_pedido!"]);

        $cmd_rota = $this->con->prepare("
        UPDATE pedidos SET situacao = 'O pedido saiu para entrega!'
        WHERE id_carrinho = ?
        ");
        $cmd_rota->execute([$id_carrinho]);

        return json_encode(['status' => 'success']);
    }

    public function recusar($id)
    {
        $cmd_carrinho = $this->con->prepare("
        SELECT id_carrinho FROM carrinho
        WHERE id_usuario = ?
        ");
        $cmd_carrinho->execute([$id]);
        $id_carrinho = $cmd_carrinho->fetchColumn();

        $cmd_pedido = $this->con->prepare("
        SELECT id_pedido FROM pedidos
        WHERE id_carrinho = ?
        AND ativo = 'sim'
        ");
        $cmd_pedido->execute([$id_carrinho]);
        $id_pedido = $cmd_pedido->fetchColumn();

        $cmd_logs = $this->con->prepare("
            INSERT INTO logs (id_usuario, acao)
            VALUES(?, ?)
        ");
        $cmd_logs->execute([$_SESSION['id_usuario'], "Recusou o pedido de ID: $id_pedido!"]);

        $cmd_recusar = $this->con->prepare("
        UPDATE pedidos SET situacao = 'O pedido não pôde ser aceito!'
        WHERE id_carrinho = ?
        ");
        $cmd_recusar->execute([$id_carrinho]);

        $cmd_limpar_carrinho = $this->con->prepare("
            DELETE FROM itens_carrinho
            WHERE id_carrinho = ?
        ");
        $cmd_limpar_carrinho->execute([$id_carrinho]);

        return json_encode(['status' => 'success']);
    }

    public function finalizar($id)
    {
        $cmd_carrinho = $this->con->prepare("
                SELECT id_carrinho FROM carrinho
                WHERE id_usuario = ?
            ");
        $cmd_carrinho->execute([$id]);
        $id_carrinho = $cmd_carrinho->fetchColumn();

        $cmd_pedido = $this->con->prepare("
        SELECT id_pedido FROM pedidos
        WHERE id_carrinho = ?
        AND ativo = 'sim'
        ");
        $cmd_pedido->execute([$id_carrinho]);
        $id_pedido = $cmd_pedido->fetchColumn();

        $cmd_logs = $this->con->prepare("
            INSERT INTO logs (id_usuario, acao)
            VALUES(?, ?)
        ");
        $cmd_logs->execute([$_SESSION['id_usuario'], "Finalizou o pedido de ID: $id_pedido!"]);

        $cmd_finalizar = $this->con->prepare("
                UPDATE pedidos SET situacao = 'O pedido foi finalizado!'
                WHERE id_carrinho = ?
            ");
        $cmd_finalizar->execute([$id_carrinho]);

        $cmd_limpar_carrinho = $this->con->prepare("
            DELETE FROM itens_carrinho
            WHERE id_carrinho = ?
        ");
        $cmd_limpar_carrinho->execute([$id_carrinho]);

        return json_encode(['status' => 'success']);
    }

    public function getProdutos()
    {
        $dados = array();
        $cmd_pedidos = $this->con->prepare("
            SELECT * FROM produtos
            WHERE ativo = 'sim'
            ORDER BY categoria = 'prato' DESC
        ");
        $cmd_pedidos->execute();
        $dados = $cmd_pedidos->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function getDados($id)
    {
        $dados = array();
        $cmd_pedidos = $this->con->prepare("
            SELECT * FROM produtos
            WHERE idProdutos = ?
        ");
        $cmd_pedidos->execute([$id]);
        $dados = $cmd_pedidos->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function editarProduto($data)
    {
        // Verifica se todos os campos necessários foram preenchidos
        if (
            $data['categoria'] != "#" &&
            !empty($data['nome_produto']) &&
            !empty($data['descricao_produto']) &&
            !empty($data['valor_produto']) &&
            isset($_FILES['imagem_produto']) // Verifica se a imagem foi enviada
        ) {
            // Captura os dados
            $categoria = $data['categoria'];
            $nome = $data['nome_produto'];
            $descricao = $data['descricao_produto'];
            $valor = $data['valor_produto'];
            $id_produto = $data['id_produto'];
            $imagem = $_FILES['imagem_produto']; // Captura o arquivo da imagem

            // Definindo o caminho para salvar a imagem
            $pasta = "assets/arquivos/";
            $nomeDoArquivo = $imagem['name'];
            $novoNomeDoArquivo = uniqid(); // Gera um nome único para o arquivo
            $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION)); // Pega a extensão do arquivo

            // Verifica se a extensão da imagem é válida
            if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'webp'])) {
                die('Tipo de arquivo não aceito!');
            }

            // Define o caminho completo do arquivo
            $path = $pasta . $novoNomeDoArquivo . "." . $extensao;

            // Move o arquivo para o diretório correto
            $deuCerto = move_uploaded_file($imagem['tmp_name'], $path);

            if ($deuCerto) {
                $cmd_logs = $this->con->prepare("
                    INSERT INTO logs (id_usuario, acao)
                    VALUES(?, ?)
                ");
                $cmd_logs->execute([$_SESSION['id_usuario'], "Editou o produto de ID: $id_produto"]);

                // Prepara e executa a inserção no banco de dados
                $cmd_produto = $this->con->prepare("
                UPDATE produtos SET nome = ?, descricao = ?, preco = ?, imagem = ?, categoria = ?
                WHERE idProdutos = ?
                ");

                $cmd_produto->execute([$nome, $descricao, $valor, $path, $categoria, $id_produto]);

                return json_encode(['status' => 'success']);
            }
        }
    }

    public function deletar($id)
    {
        $cmd_produto = $this->con->prepare("
            UPDATE produtos
            SET ativo = 'não'
            WHERE idProdutos = ?
        ");
        $cmd_produto->execute([$id]);

        $cmd_logs = $this->con->prepare("
            INSERT INTO logs (id_usuario, acao)
            VALUES(?, ?)
        ");
        $cmd_logs->execute([$_SESSION['id_usuario'], "Deletou o produto de ID: $id"]);

        return json_encode(['status' => 'success']);
    }

    public function disponivel($id)
    {
        $cmd_logs = $this->con->prepare("
            INSERT INTO logs (id_usuario, acao)
            VALUES(?, ?)
        ");
        $cmd_logs->execute([$_SESSION['id_usuario'], "Deixou o produto disponível!"]);

        $cmd_produto = $this->con->prepare("
            UPDATE produtos
            SET disponibilidade = 'disponível'
            WHERE idProdutos = ?
        ");
        $cmd_produto->execute([$id]);

        return json_encode(['status' => 'success']);
    }

    public function indisponivel($id)
    {
        $cmd_logs = $this->con->prepare("
            INSERT INTO logs (id_usuario, acao)
            VALUES(?, ?)
        ");
        $cmd_logs->execute([$_SESSION['id_usuario'], "Deixou o produto indisponível!"]);

        $cmd_produto = $this->con->prepare("
            UPDATE produtos
            SET disponibilidade = 'indisponível'
            WHERE idProdutos = ?
        ");
        $cmd_produto->execute([$id]);

        return json_encode(['status' => 'success']);
    }

    public function adicionarProduto($data)
    {
        // Verifica se todos os campos necessários foram preenchidos
        if (
            $data['categoria'] != "#" &&
            !empty($data['nome_produto']) &&
            !empty($data['descricao_produto']) &&
            !empty($data['valor_produto']) &&
            isset($_FILES['imagem_produto']) // Verifica se a imagem foi enviada
        ) {
            // Captura os dados
            $categoria = $data['categoria'];
            $nome = $data['nome_produto'];
            $descricao = $data['descricao_produto'];
            $valor = $data['valor_produto'];
            $imagem = $_FILES['imagem_produto']; // Captura o arquivo da imagem

            // Definindo o caminho para salvar a imagem
            $pasta = "assets/arquivos/";
            $nomeDoArquivo = $imagem['name'];
            $novoNomeDoArquivo = uniqid(); // Gera um nome único para o arquivo
            $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION)); // Pega a extensão do arquivo

            // Verifica se a extensão da imagem é válida
            if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'webp'])) {
                die('Tipo de arquivo não aceito!');
            }

            // Define o caminho completo do arquivo
            $path = $pasta . $novoNomeDoArquivo . "." . $extensao;

            // Move o arquivo para o diretório correto
            $deuCerto = move_uploaded_file($imagem['tmp_name'], $path);

            if ($deuCerto) {
                // Prepara e executa a inserção no banco de dados
                $cmd_produto = $this->con->prepare("
                    INSERT INTO produtos (nome, descricao, preco, imagem, categoria)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $cmd_produto->execute([$nome, $descricao, $valor, $path, $categoria]);

                // Recupera o último ID inserido
                $lastInsertId = $this->con->lastInsertId();

                $cmd_logs = $this->con->prepare("
                    INSERT INTO logs (id_usuario, acao)
                    VALUES(?, ?)
                ");
                $cmd_logs->execute([$_SESSION['id_usuario'], "Inseriu o produto de ID: $lastInsertId!"]);

                return json_encode(['status' => 'success']);
            }
        }
    }

    public function getHistorico($pag)
    {
        $pagina = $pag;

        $limite = 9;

        $inicio = ($pagina * $limite) - $limite;

        $dados = array();
        $cmd_historico = $this->con->prepare("
            SELECT
                historico.*,
                usuarios.pnome,
                GROUP_CONCAT(CONCAT(historico.qtd, 'x ', produtos.nome) SEPARATOR '<br>') AS produtos
            FROM historico
            JOIN usuarios ON historico.id_usuario = usuarios.idUsuarios
            JOIN produtos ON historico.id_produto = produtos.idProdutos
            WHERE historico.ativo = 'sim'
            GROUP BY historico.id_pedido, historico.data, usuarios.pnome, historico.pagamento, historico.valor
            ORDER BY historico.id_pedido DESC
            LIMIT $inicio, $limite
        ");
        $cmd_historico->execute();
        $dados = $cmd_historico->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function limparHistorico()
    {
        $cmd_limpar = $this->con->prepare("
            UPDATE historico
            SET ativo = 'não'
        ");
        $cmd_limpar->execute();

        $cmd_logs = $this->con->prepare("
            INSERT INTO logs (id_usuario, acao)
            VALUES(?, ?)
        ");
        $cmd_logs->execute([$_SESSION['id_usuario'], "Limpou o histórico de pedidos!"]);

        return json_encode(['status' => 'success']);
    }

    public function excluirRegistro($id)
    {
        $cmd_limpar = $this->con->prepare("
            UPDATE historico
            SET ativo = 'não'
            WHERE id_pedido = ?
        ");
        $cmd_limpar->execute([$id]);

        $cmd_logs = $this->con->prepare("
            INSERT INTO logs (id_usuario, acao)
            VALUES(?, ?)
        ");
        $cmd_logs->execute([$_SESSION['id_usuario'], "Limpou um registro de pedido!"]);

        return json_encode(['status' => 'success']);
    }

    public function getClientesVendas()
    {
        $dados = array();
        $cmd_vendas = $this->con->prepare("
            SELECT usuarios.pnome, COUNT(DISTINCT historico.id_pedido) AS qtd
            FROM historico
            JOIN produtos ON historico.id_produto = produtos.idProdutos
            JOIN usuarios ON historico.id_usuario = usuarios.idUsuarios
            GROUP BY usuarios.pnome
            ORDER BY qtd DESC
        ");
        $cmd_vendas->execute();
        $dados = $cmd_vendas->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function getPratosVendas()
    {
        $dados = array();
        $cmd_vendas = $this->con->prepare("
            SELECT produtos.nome, SUM(historico.qtd) AS qtd
            FROM historico
            JOIN produtos ON historico.id_produto = produtos.idProdutos
            WHERE categoria = 'prato'
            GROUP BY produtos.nome
            ORDER BY qtd DESC
        ");
        $cmd_vendas->execute();
        $dados = $cmd_vendas->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function getBebidasVendas()
    {
        $dados = array();
        $cmd_vendas = $this->con->prepare("
            SELECT produtos.nome, SUM(historico.qtd) AS qtd
            FROM historico
            JOIN produtos ON historico.id_produto = produtos.idProdutos
            WHERE categoria = 'bebida'
            GROUP BY produtos.nome
            ORDER BY qtd DESC
        ");
        $cmd_vendas->execute();
        $dados = $cmd_vendas->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function getVendasPeriodo()
    {
        $dados = array();
        $cmd_vendas = $this->con->prepare("
            SELECT historico.data, 
            COUNT(DISTINCT historico.id_pedido) AS qtd
            FROM historico
            GROUP BY historico.data
        ");
        $cmd_vendas->execute();
        $dados = $cmd_vendas->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function getVendasPeriodoValor()
    {
        $dados = array();
        $cmd_vendas = $this->con->prepare("
            WITH cte AS (
                SELECT data,
                    valor,
                    ROW_NUMBER() OVER (PARTITION BY id_pedido ORDER BY (SELECT NULL)) AS rn
                FROM historico
            )
            SELECT data,
                SUM(valor) AS qtd
            FROM cte
            WHERE rn = 1
            GROUP BY data

        ");
        $cmd_vendas->execute();
        $dados = $cmd_vendas->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function getAtividades($pag)
    {
        $pagina = $pag;

        $limite = 9;

        $inicio = ($pagina * $limite) - $limite;

        $dados = array();
        $cmd_vendas = $this->con->prepare("
            SELECT logs.*, usuarios.pnome FROM logs
            JOIN usuarios ON logs.id_usuario = usuarios.idUsuarios
            ORDER BY logs.id_logs DESC
            LIMIT $inicio, $limite
        ");
        $cmd_vendas->execute();
        $dados = $cmd_vendas->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function adicionarFun($data)
    {
        if (
            !empty($data['nome']) &&
            !empty($data['sobrenome']) &&
            !empty($data['nomeMae']) &&
            !empty($data['data']) &&
            !empty($data['cpf']) &&
            !empty($data['cell']) &&
            !empty($data['email']) &&
            !empty($data['cep']) &&
            !empty($data['estado']) &&
            !empty($data['cidade']) &&
            !empty($data['bairro']) &&
            !empty($data['rua']) &&
            !empty($data['num']) &&
            !empty($data['ref']) &&
            !empty($data['user']) &&
            !empty($data['senha'])
        ) {

            $senha = password_hash($data['senha'], PASSWORD_DEFAULT);

            // Verificar duplicidade no banco de dados
            $cmdCheck = $this->con->prepare('
                SELECT * 
                FROM usuarios 
                WHERE ativo = "sim" AND (usuario = :usuario OR email = :email OR cpf = :cpf OR cell = :cell)
            ');
            $cmdCheck->execute([
                ':usuario' => $data['user'],
                ':email' => $data['email'],
                ':cpf' => $data['cpf'],
                ':cell' => $data['cell'],
            ]);

            $existingUser = $cmdCheck->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                // Retornar mensagens específicas dependendo do conflito
                if ($existingUser['usuario'] === $data['user']) {
                    return json_encode(['status' => 'errorUser', 'message' => 'Usuário já existe.']);
                }
                if ($existingUser['email'] === $data['email']) {
                    return json_encode(['status' => 'errorEmail', 'message' => 'E-mail já cadastrado.']);
                }
                if ($existingUser['cpf'] === $data['cpf']) {
                    return json_encode(['status' => 'errorCpf', 'message' => 'CPF já cadastrado.']);
                }
                if ($existingUser['cell'] === $data['cell']) {
                    return json_encode(['status' => 'errorCell', 'message' => 'Celular já cadastrado.']);
                }
            }

            // Inserir novo usuário
            $cmd = $this->con->prepare('
                INSERT INTO usuarios (pnome, sobrenome, nomeMae, dataNascimento, cpf, cell, email, estado, cidade, bairro, rua, numero, complemento, referencia, cep, usuario, senha, tipo_usuario) 
                VALUES (:pnome, :sobrenome, :nomeMae, :dataNascimento, :cpf, :cell, :email, :estado, :cidade, :bairro, :rua, :numero, :complemento, :referencia, :cep, :usuario, :senha, "funcionario")
            ');

            $cmd->execute([
                ':pnome' => $data['nome'],
                ':sobrenome' => $data['sobrenome'],
                ':nomeMae' => $data['nomeMae'],
                ':dataNascimento' => $data['data'],
                ':cpf' => $data['cpf'],
                ':cell' => $data['cell'],
                ':email' => $data['email'],
                ':estado' => $data['estado'],
                ':cidade' => $data['cidade'],
                ':bairro' => $data['bairro'],
                ':rua' => $data['rua'],
                ':numero' => $data['num'],
                ':complemento' => $data['comp'],
                ':referencia' => $data['ref'],
                ':cep' => $data['cep'],
                ':usuario' => $data['user'],
                ':senha' => $senha,
            ]);

            // Retorno de sucesso
            return json_encode(['status' => 'success', 'message' => 'Usuário cadastrado com sucesso.']);
        } else {
            // Caso falte algum campo obrigatório
            return json_encode(['status' => 'error', 'message' => 'Por favor, preencha todos os campos obrigatórios.']);
        }
    }

    public function getFuncionarios()
    {
        $dados = array();
        $cmd_func = $this->con->prepare("
            SELECT * FROM usuarios
            WHERE ativo = 'sim'
            AND tipo_usuario = 'funcionario'
        ");
        $cmd_func->execute();
        $dados = $cmd_func->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function excluirFunc($id)
    {
        $cmd_func = $this->con->prepare("
            UPDATE usuarios
            SET ativo = 'não'
            WHERE idUsuarios = ?
        ");
        $cmd_func->execute([$id]);

        $cmd_logs = $this->con->prepare("
            INSERT INTO logs (id_usuario, acao)
            VALUES(?, ?)
        ");
        $cmd_logs->execute([$_SESSION['id_usuario'], "Deletou o funcionário de ID: $id"]);

        return json_encode(["status" => "success"]);
    }

    public function getUsuarios()
    {
        $dados = array();
        $cmd_func = $this->con->prepare("
            SELECT * FROM usuarios
            WHERE ativo = 'sim'
        ");
        $cmd_func->execute();
        $dados = $cmd_func->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function excluirUser($id)
    {
        $cmd_func = $this->con->prepare("
            UPDATE usuarios
            SET ativo = 'não'
            WHERE idUsuarios = ?
        ");
        $cmd_func->execute([$id]);

        $cmd_logs = $this->con->prepare("
            INSERT INTO logs (id_usuario, acao)
            VALUES(?, ?)
        ");
        $cmd_logs->execute([$_SESSION['id_usuario'], "Deletou o usuário de ID: $id"]);

        return json_encode(["status" => "success"]);
    }
}