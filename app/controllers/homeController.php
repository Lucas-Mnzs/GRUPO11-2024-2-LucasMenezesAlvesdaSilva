<?php

namespace Name\Controllers;

use Name\Core\Controller;
use Dotenv\Dotenv;
use Name\Models\homeModels;

$dotenv = Dotenv::createImmutable(__DIR__ . '../../..');
$dotenv->load();
class homeController extends Controller
{
    public function index()
    {
        $this->carregarView("home");
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
        $data = [
            'resposta' => $_POST['conf']
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
