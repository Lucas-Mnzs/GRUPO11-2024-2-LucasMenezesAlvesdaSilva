<?php

namespace Name\Controllers;

use Name\Core\Controller;
use Name\Models\cardapioModels;
use Dotenv\Dotenv;
use Name\Models\Config\Conexao;
use PDO;

$dotenv = Dotenv::createImmutable(__DIR__ . '../../..');
$dotenv->load();
class cardapioController extends Controller
{
    private $con;

    public function __construct()
    {
        $this->con = Conexao::getConexao();
    }

    public function index()
    {
        if (isset($_SESSION['id_usuario'])) {
            $this->carregarView("cardapio");
        } else {
            if (!isset($_SESSION['id_usuario']) && isset($_COOKIE['remember_token'])) {
                $token = $_COOKIE['remember_token'];

                $stmt = $this->con->prepare("SELECT * FROM usuarios WHERE remember_token = ? AND ativo = 'sim'");
                $stmt->execute([$token]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario) {
                    $_SESSION['id_usuario']   = $usuario['idUsuarios'];
                    $_SESSION['user']         = $usuario['usuario'];
                    $_SESSION['nome']         = $usuario['pnome'];
                    $_SESSION['sobrenome']    = $usuario['sobrenome'];
                    $_SESSION['contato']      = $usuario['cell'];
                    $_SESSION['email']        = $usuario['email'];
                    $_SESSION['estado']       = $usuario['estado'];
                    $_SESSION['cidade']       = $usuario['cidade'];
                    $_SESSION['bairro']       = $usuario['bairro'];
                    $_SESSION['rua']          = $usuario['rua'];
                    $_SESSION['numero']       = $usuario['numero'];
                    $_SESSION['complemento']  = $usuario['complemento'];
                    $_SESSION['referencia']   = $usuario['referencia'];
                    $_SESSION['cep']          = $usuario['cep'];
                    $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
                }

                if ($_SESSION['tipo_usuario'] === "master" || $_SESSION['tipo_usuario'] === "funcionario") {
                    $this->carregarView("painelAdmin");
                } elseif ($_SESSION['tipo_usuario'] === "cliente") {
                    $this->carregarView("cardapio");
                }
            } else {
                $this->carregarView("home");
            }
        }
    }

    public function logoutUser()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            if (isset($_SESSION['id_usuario'])) {
                $stmt = $this->con->prepare("UPDATE usuarios SET remember_token = NULL WHERE idUsuarios = ?");
                $stmt->execute([$_SESSION['id_usuario']]);
            }

            setcookie('remember_token', '', time() - 3600, "/"); // Expira o cookie
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
