<?php

namespace Name\Controllers;

use Name\Core\Controller;
use Name\Models\painelAdminModels;

class painelAdminController extends Controller
{
    public function index()
    {
        if (isset($_SESSION['id_usuario'])) {
            $this->carregarView("painelAdmin");
        } else {
            $this->carregarView("home");
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
