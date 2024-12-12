<?php

namespace Name\Models;

use Name\Models\Config\Conexao;
use PDO;

class cardapioModels
{
    private $con;

    public function __construct()
    {
        $this->con = Conexao::getConexao();
    }

    public function getDestaques()
    {
        $destaques = array();
        $cmd = $this->con->prepare("
            SELECT produtos.*, SUM(historico.qtd) AS total_produtos 
            FROM produtos
            JOIN historico ON produtos.idProdutos = historico.id_produto
            GROUP BY produtos.idProdutos
            ORDER BY total_produtos DESC
            LIMIT 2

        ");
        $cmd->execute();
        $destaques = $cmd->fetchAll(PDO::FETCH_ASSOC);

        return $destaques;
    }

    public function getPratos()
    {
        $pratos = array();
        $cmd = $this->con->query('SELECT * FROM produtos WHERE categoria = "prato" AND disponibilidade = "disponível" AND ativo = "sim"');
        $pratos = $cmd->fetchall(PDO::FETCH_ASSOC);

        return $pratos;
    }

    public function getBebidas()
    {
        $bebidas = array();
        $cmd = $this->con->query('SELECT * FROM produtos WHERE categoria = "bebida" AND disponibilidade = "disponível" AND ativo = "sim"');
        $bebidas = $cmd->fetchall(PDO::FETCH_ASSOC);

        return $bebidas;
    }

    public function getProdutoById($id)
    {
        $dados = array();
        $cmd = $this->con->prepare('SELECT * FROM produtos WHERE idProdutos = ?');
        $cmd->execute([$id]);
        $dados = $cmd->fetchall(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function adicionarProduto($id, $data)
    {
        // Verifica se o complemento foi enviado
        $complemento = isset($data['inputComplemento']) ? $data['inputComplemento'] : '';

        // Verifica se o usuário já tem um carrinho
        $cmd_verificar = $this->con->prepare("SELECT id_carrinho FROM carrinho WHERE id_usuario = ? LIMIT 1");
        $cmd_verificar->execute([$_SESSION['id_usuario']]);
        $id_carrinho = $cmd_verificar->fetchColumn();

        if (!$id_carrinho) {
            // Se não existe, cria um novo carrinho
            $cmd_carrinho = $this->con->prepare("INSERT INTO carrinho (id_usuario) VALUES (?)");
            $cmd_carrinho->execute([$_SESSION['id_usuario']]);
            $id_carrinho = $this->con->lastInsertId();
        }

        // Verifica se o produto já existe no carrinho do usuário
        $cmd_verificar_produto = $this->con->prepare("SELECT qtd, obs FROM itens_carrinho WHERE id_carrinho = ? AND id_produto = ? LIMIT 1");
        $cmd_verificar_produto->execute([$id_carrinho, $id]);
        $produto = $cmd_verificar_produto->fetch(PDO::FETCH_ASSOC);

        $qtd_produto = $produto['qtd'] ?? 0;
        $obs_produto = $produto['obs'] ?? '';

        if ($qtd_produto == 0) {
            // Insere o produto no carrinho
            $obsConc = $complemento ? '1 ' . $complemento : '';
            $cmd_itens = $this->con->prepare("INSERT INTO itens_carrinho (id_carrinho, id_produto, obs) VALUES (?, ?, ?)");
            $cmd_itens->execute([$id_carrinho, $id, $obsConc]);
        } else {
            // Atualiza a quantidade e as observações
            $nova_qtd = $qtd_produto + 1;
            $obsConc = $complemento ? "1 " . $complemento . "<br>" . ($obs_produto ? " " . $obs_produto : "") : $obs_produto;
            $cmd_itens = $this->con->prepare("UPDATE itens_carrinho SET qtd = ?, obs = ? WHERE id_carrinho = ? AND id_produto = ?");
            $cmd_itens->execute([$nova_qtd, $obsConc, $id_carrinho, $id]);
        }

        // Retorna a resposta
        return json_encode(['status' => 'success', 'message' => 'Produto inserido no carrinho!']);
    }


    public function getQuantidade()
    {
        $cmd = $this->con->prepare("
            SELECT SUM(qtd) AS quantidade 
            FROM itens_carrinho 
            JOIN carrinho ON itens_carrinho.id_carrinho = carrinho.id_carrinho 
            WHERE carrinho.id_usuario = ?");
        $cmd->execute([$_SESSION['id_usuario']]);
        $quantidade = $cmd->fetchColumn();

        return $quantidade;
    }

    public function getCarrinho()
    {
        $dados = array();
        $cmd = $this->con->prepare("
            SELECT * 
            FROM itens_carrinho 
            JOIN carrinho ON itens_carrinho.id_carrinho = carrinho.id_carrinho
            JOIN produtos ON itens_carrinho.id_produto = produtos.idProdutos
            WHERE carrinho.id_usuario = ?");
        $cmd->execute([$_SESSION['id_usuario']]);
        $dados = $cmd->fetchall();

        return $dados;
    }

    public function removerProduto($id)
    {
        $cmd_itens = $this->con->prepare("
            DELETE FROM itens_carrinho
            WHERE id_produto = ?");
        $cmd_itens->execute([$id]);

        return json_encode(['status' => 'success', 'message' => 'Produto removido do carrinho!']);
    }

    public function setQuantidade($data)
    {
        $cmd_carrinho = $this->con->prepare("
            SELECT id_carrinho FROM carrinho
            WHERE id_usuario = ?
        ");
        $cmd_carrinho->execute([$_SESSION['id_usuario']]);
        $id_carrinho = $cmd_carrinho->fetchColumn();

        $itens = json_decode($data['itens'], true);

        // Atualiza as quantidades de cada produto
        foreach ($itens as $item) {
            $cmd_itens_carrinho = $this->con->prepare("
            UPDATE itens_carrinho
            SET qtd = ?
            WHERE id_carrinho = ?
            AND id_produto = ?
        ");
            $cmd_itens_carrinho->execute([$item['qtd'], $id_carrinho, $item['id_produto']]);
        }

        return json_encode(['status' => 'success', 'message' => 'Quantidade atualizada!']);
    }

    public function getValorTotal()
    {
        $cmd_valor = $this->con->prepare("
        SELECT SUM(qtd * preco) AS valorTotal
        FROM itens_carrinho
        JOIN produtos ON itens_carrinho.id_produto = produtos.idProdutos
        JOIN carrinho ON itens_carrinho.id_carrinho = carrinho.id_carrinho
        WHERE carrinho.id_usuario = ?
        ");
        $cmd_valor->execute([$_SESSION['id_usuario']]);
        $valor_total = $cmd_valor->fetchColumn();

        return $valor_total;
    }

    public function finalizarPedido($data)
    {
        $cmd_carrinho = $this->con->prepare("
        SELECT id_carrinho FROM carrinho
        WHERE id_usuario = ?
    ");
        $cmd_carrinho->execute([$_SESSION['id_usuario']]);
        $id_carrinho = $cmd_carrinho->fetchColumn();

        $cmd_pedido = $this->con->prepare("
        INSERT INTO pedidos (id_carrinho, valor_total, troco, forma_pagamento, tipo_entrega, situacao)
        VALUES (?, ?, ?, ?, ?, 'Aguardando a confirmação do restaurante!')
    ");
        $cmd_pedido->execute([
            $id_carrinho,
            $data['valor'],
            $data['troco'],
            $data['pagamento'],
            $data['entrega']
        ]);

        return json_encode(['status' => 'success', 'message' => 'Pedido finalizado com sucesso!']);
    }


    public function trocarEndereco()
    {
        $distanceKm = 0;
        $cost = 0;

        $rua = $_POST['rua'] ?? '';
        $bairro = $_POST['bairro'] ?? '';
        $num = $_POST['numero'] ?? '';
        $cidade = $_POST['cidade'] ?? '';
        $estado = $_POST['estado'] ?? '';

        // Validação da distância
        $apiKey = $_ENV['API_KEY'];
        $origin = 'Rua Doutor Furquim Mendes, 990, Vila Centenário';
        $destination = $rua . ", " . $num . ", " . $bairro;

        $response = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=" . urlencode($origin) . "&destinations=" . urlencode($destination) . "&key=" . $apiKey);
        $data = json_decode($response, true);

        if ($data['rows'][0]['elements'][0]['distance']['value'] / 1000 > 5) {
            echo json_encode(['status' => 'failed', 'message' => 'Distância maior que o permitido']);
            exit;
        }

        // Obtém a distância
        $distanceKm = $data['rows'][0]['elements'][0]['distance']['value'] / 1000;

        $ratePerKm = 1.00;

        // Calcula o custo
        $cost = $distanceKm * $ratePerKm;

        if ($bairro == "Vila Centenário") {
            $_SESSION['taxa'] = "R$ " . number_format(3, 2, ",", ".");
        } else {
            $fixedRate = 3.00; // Taxa fixa de R$3,00
            $totalCost = $fixedRate + $cost; // Total em valor numérico
            $_SESSION['taxa'] = "R$ " . number_format($totalCost, 2, ",", ".");
        }


        $taxa = $_SESSION['taxa'];

        // Atualiza sessão e banco
        $_SESSION['rua'] = $rua;
        $_SESSION['numero'] = $num;
        $_SESSION['bairro'] = $bairro;
        $_SESSION['cidade'] = $cidade;
        $_SESSION['estado'] = $estado;

        $cmd = $this->con->prepare("
        UPDATE usuarios
        SET rua = ?, numero = ?, bairro = ?, cidade = ?, estado = ?
        WHERE idUsuarios = ?
    ");
        $cmd->execute([$rua, $num, $bairro, $cidade, $estado, $_SESSION['id_usuario']]);

        echo json_encode([
            'status' => 'success',
            'rua' => $rua,
            'numero' => $num,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'taxa'   => $taxa,
            'message' => 'Endereço atualizado com sucesso!'
        ]);
        exit;
    }

    public function getSituacao()
    {
        $cmd_situacao = $this->con->prepare("
            SELECT situacao
            FROM pedidos
            JOIN carrinho ON pedidos.id_carrinho = carrinho.id_carrinho
            WHERE carrinho.id_usuario = ? AND pedidos.ativo = 'sim'
        ");
        $cmd_situacao->execute([$_SESSION['id_usuario']]);
        $situacao = $cmd_situacao->fetch(PDO::FETCH_ASSOC);

        return $situacao;
    }

    public function recusadoFinalizado()
    {
        $cmd_carrinho = $this->con->prepare("
            SELECT id_carrinho
            FROM carrinho
            WHERE id_usuario = ?
        ");
        $cmd_carrinho->execute([$_SESSION['id_usuario']]);
        $id_carrinho = $cmd_carrinho->fetchColumn();

        $cmd_pedido = $this->con->prepare("
            UPDATE pedidos
            SET ativo = 'não'
            WHERE id_carrinho = ?
        ");
        $cmd_pedido->execute([$id_carrinho]);

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
}
