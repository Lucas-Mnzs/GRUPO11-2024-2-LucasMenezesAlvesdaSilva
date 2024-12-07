<?php

namespace Name\Controllers;

use Name\Core\Controller;
use Name\Models\cardapioModels;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '../../..');
$dotenv->load();
class cardapioController extends Controller
{
    public function index()
    {
        $this->carregarView("cardapio");
    }

    public function logoutUser()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function getDestaques()
    {
        $cardapioModel = new cardapioModels();
        $destaques = $cardapioModel->getDestaques();

        echo json_encode($destaques);
    }

    public function getPratos()
    {
        $cardapioModel = new cardapioModels();
        $pratos = $cardapioModel->getPratos();
        echo json_encode($pratos);
    }

    public function getBebidas()
    {
        $cardapioModel = new cardapioModels();
        $bebidas = $cardapioModel->getBebidas();
        echo json_encode($bebidas);
    }

    public function getProdutoById($id)
    {
        $cardapioModel = new cardapioModels();
        $dados = $cardapioModel->getProdutoById($id);
        echo json_encode($dados);
    }

    public function adicionarProduto($id)
    {
        $cardapioModel = new cardapioModels();
        $dados = $cardapioModel->adicionarProduto($id, $_POST);

        echo $dados;
    }

    public function getQuantidade()
    {
        $cardapioModel = new cardapioModels();
        $dados = $cardapioModel->getQuantidade();
        echo json_encode($dados);
    }

    public function getCarrinho()
    {
        $cardapioModel = new cardapioModels();
        $dados = $cardapioModel->getCarrinho();
        echo json_encode($dados);
    }

    public function removerProduto($id)
    {
        $cardapioModel = new cardapioModels();
        $dados = $cardapioModel->removerProduto($id);
        echo $dados;
    }

    public function setQuantidade()
    {
        $cardapioModel = new cardapioModels();
        $dados = $cardapioModel->setQuantidade($_POST);
        echo $dados;
    }

    public function getValorTotal()
    {
        $cardapioModel = new cardapioModels();
        $valor = $cardapioModel->getValorTotal();
        echo json_encode($valor);
    }

    public function trocarEndereco()
    {
        $cardapioModel = new cardapioModels();
        $dados = $cardapioModel->trocarEndereco();
        echo $dados;
    }

    public function finalizarPedido()
    {
        $cardapioModel = new cardapioModels();
        $dados = $cardapioModel->finalizarPedido($_POST);
        echo $dados;
    }

    public function getSituacao()
    {
        $cardapioModel = new cardapioModels();
        $dados = $cardapioModel->getSituacao();
        echo json_encode($dados);
    }

    public function getSitu()
    {
        $cardapioModels = new cardapioModels();
        $dados = $cardapioModels->getSitu();

        // Retorna a resposta como JSON
        echo json_encode(['situacao' => $dados]);
    }
}
