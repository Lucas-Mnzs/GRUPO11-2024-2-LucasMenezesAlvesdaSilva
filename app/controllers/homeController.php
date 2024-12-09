<?php

namespace Name\Controllers;

use Name\Core\Controller;
use Dotenv\Dotenv;
use Name\Models\Config\Conexao;
use Name\Models\homeModels;
use PDO;

$dotenv = Dotenv::createImmutable(__DIR__ . '../../..');
$dotenv->load();
class homeController extends Controller
{

    private $con;

    public function __construct()
    {
        $this->con = Conexao::getConexao();
    }

    public function index()
    {
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

    public function consultarCEP()
    {
        $rua = $_POST['rua'] ?? '';
        $bairro = $_POST['bairro'] ?? '';
        $num = $_POST['num'] ?? '';

        $distanceKm = 0;
        $apiKey = $_ENV['API_KEY'];

        $origin = 'Rua Doutor Furquim Mendes, 990, Vila Centenário';
        $destination = $rua . ", " . $num . ", " . $bairro;

        // Codifica os endereços para uso na URL
        $originEncoded = urlencode($origin);
        $destinationEncoded = urlencode($destination);

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=$originEncoded&destinations=$destinationEncoded&key=$apiKey";

        // Inicializa o cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Executa a requisição e obtém a resposta
        $response = curl_exec($ch);

        // Verifica se houve erro na requisição
        if (curl_errno($ch)) {
            return json_encode(['status' => 'erro', 'message' => curl_error($ch)]);
        }

        // Fecha o cURL
        curl_close($ch);

        // Decodifica a resposta JSON da API
        $data = json_decode($response, true);

        // Verifica se a requisição foi bem-sucedida
        if ($data['status'] !== 'OK') {
            return json_encode(['status' => 'erro', 'message' => 'Falha na requisição à API do Google']);
        }

        // Obtém a distância
        $distanceKm = $data['rows'][0]['elements'][0]['distance']['value'] / 1000;

        // Verifica a distância e retorna a resposta
        if ($distanceKm > 5) {
            echo json_encode(['status' => 'failed']);
        } else {
            echo json_encode(['status' => 'success']);
        }
    }

    public function logarUser()
    {
        $data = [
            'user'     => $_POST['user'],
            'pass'     => $_POST['pass']
        ];

        $homeModel = new homeModels();
        $usuario = $homeModel->loginUser($data);

        echo $usuario;
    }

    public function segundaAut()
    {
        if (isset($_POST['remember'])) {
            $token = $_POST['remember'];
        } else {
            $token = '';
        }
        $data = [
            'resposta' => $_POST['conf'],
            'remember'  =>  $token
        ];

        $homeModel = new homeModels();
        $usuario = $homeModel->segundaAut($data);

        echo $usuario;
    }

    public function confirmarUser()
    {
        $data = [
            'user'      =>  $_POST['usuario'],
            'email'     =>  $_POST['email'],
            'resposta'  =>  $_POST['aleatoria']
        ];

        $homeModel = new homeModels();
        $usuario = $homeModel->recuperarUser($data);

        echo $usuario;
    }

    public function atualizarSenha()
    {
        $data = [
            'senha'  => password_hash($_POST['senha'], PASSWORD_DEFAULT)
        ];

        $homeModel = new homeModels();
        $usuario = $homeModel->atualizarSenha($data);

        echo $usuario;
    }

    public function cadastrarUser()
    {
        $data = [
            'nome'             => $_POST['nome'],
            'sNome'            => $_POST['sNome'],
            'nomeMae'          => $_POST['nomeMae'],
            'dataNascimento'   => $_POST['dataNascimento'],
            'cpf'              => $_POST['cpf'],
            'cell'             => $_POST['cell'],
            'email'            => $_POST['e_mail'],
            'cep'              => $_POST['cep'],
            'cidade'           => $_POST['cidade'],
            'estado'           => $_POST['estado'],
            'bairro'           => $_POST['bai'],
            'rua'              => $_POST['rua_cad'],
            'numero'           => $_POST['numero'],
            'complemento'      => $_POST['complemento'],
            'referencia'       => $_POST['referencia'],
            'user'             => $_POST['user_cad'],
            'senha'            => password_hash($_POST['sen'], PASSWORD_DEFAULT),
        ];

        $homeModel = new homeModels();
        $usuario = $homeModel->cadastrarUser($data);

        echo $usuario;
    }
}
