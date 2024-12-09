<?php

namespace Name\Controllers;

use Name\Core\Controller;
use Name\Models\Config\Conexao;
use Name\Models\painelAdminModels;
use PDO;

class painelAdminController extends Controller
{
    private $con;

    public function __construct()
    {
        $this->con = Conexao::getConexao();
    }

    public function index()
    {
        if (isset($_SESSION['id_usuario'])) {
            $this->carregarView("painelAdmin");
        } else {
            if (!isset($_SESSION['id_usuario']) && isset($_COOKIE['remember_token'])) {
                $token = $_COOKIE['remember_token'];

                $stmt = $this->con->prepare("SELECT * FROM usuarios WHERE remember_token = ? AND ativo = 'sim'");
                $stmt->execute([$token]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario) {
                    $_SESSION['id_usuario']   = $usuario['idUsuarios'];
                    $_SESSION['user']         = $usuario['usuario'];
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
                    $taxaController = new taxaController;
                    $taxa = $taxaController->getTaxa();

                    if ($taxa) {
                        $this->carregarView("cardapio");
                        return json_encode(['status' => 'success']);
                    } else {
                        return json_encode(['status' => 'error', 'message' => 'Resposta de recuperação incorreta.']);
                    }
                }
            } else {
                $this->carregarView("home");
            }
        }
    }

    public function abrir_fechar()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->abrir_fechar();

        echo $dados;
    }

    public function getSitu()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->getSitu();

        // Retorna a resposta como JSON
        echo json_encode(['situacao' => $dados]);
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

    public function getPedidos()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->getPedidos();

        echo json_encode($dados);
    }

    public function aceitar($id)
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->aceitar($id);

        echo $dados;
    }

    public function rota($id)
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->rota($id);

        echo $dados;
    }

    public function recusar($id)
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->recusar($id);

        echo $dados;
    }

    public function finalizar($id)
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->finalizar($id);

        echo $dados;
    }

    public function getProdutos()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->getProdutos();

        echo json_encode($dados);
    }

    public function getDados($id)
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->getDados($id);

        echo json_encode($dados);
    }

    public function editarProduto()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->editarProduto($_POST);

        echo $dados;
    }

    public function deletar($id)
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->deletar($id);

        echo $dados;
    }

    public function disponivel($id)
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->disponivel($id);

        echo $dados;
    }

    public function indisponivel($id)
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->indisponivel($id);

        echo $dados;
    }

    public function adicionarProduto()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->adicionarProduto($_POST);

        echo $dados;
    }

    public function getHistorico()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->getHistorico();

        echo json_encode($dados);
    }

    public function limparHistorico()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->limparHistorico();

        echo $dados;
    }

    public function excluirRegistro($id)
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->excluirRegistro($id);

        echo $dados;
    }

    public function getClientesVendas()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->getClientesVendas();

        echo json_encode($dados);
    }

    public function getPratosVendas()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->getPratosVendas();

        echo json_encode($dados);
    }

    public function getBebidasVendas()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->getBebidasVendas();

        echo json_encode($dados);
    }

    public function getVendasPeriodo()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->getVendasPeriodo();

        echo json_encode($dados);
    }

    public function getVendasPeriodoValor()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->getVendasPeriodoValor();

        echo json_encode($dados);
    }

    public function getAtividades()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->getAtividades();

        echo json_encode($dados);
    }

    public function adicionarFun()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->adicionarFun($_POST);

        echo $dados;
    }

    public function getFuncionarios()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->getFuncionarios();

        echo json_encode($dados);
    }

    public function excluirFunc($id)
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->excluirFunc($id);

        echo $dados;
    }

    public function getUsuarios()
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->getUsuarios();

        echo json_encode($dados);
    }

    public function excluirUser($id)
    {
        $painelModels = new painelAdminModels();
        $dados = $painelModels->excluirUser($id);

        echo $dados;
    }
}
