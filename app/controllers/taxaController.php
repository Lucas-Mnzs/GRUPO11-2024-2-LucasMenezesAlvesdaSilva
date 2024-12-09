<?php

namespace Name\Controllers;

class taxaController
{
    public function getTaxa()
    {
        $rua = $_SESSION['rua'] ?? '';
        $bairro = $_SESSION['bairro'] ?? '';
        $num = $_SESSION['numero'] ?? '';

        if (empty($rua) || empty($bairro) || empty($num)) {
            return false; // Dados insuficientes
        }

        $apiKey = $_ENV['API_KEY'] ?? '';
        if (empty($apiKey)) {
            return false; // API key não configurada
        }

        $origin = 'Rua Doutor Furquim Mendes, 990, Vila Centenário';
        $destination = "{$rua}, {$num}, {$bairro}";
        $originEncoded = urlencode($origin);
        $destinationEncoded = urlencode($destination);

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=$originEncoded&destinations=$destinationEncoded&key=$apiKey";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return false; // Falha na requisição cURL
        }

        curl_close($ch);
        $data = json_decode($response, true);

        if (!isset($data['rows'][0]['elements'][0]['distance']['value'])) {
            return false; // Resposta inesperada da API
        }

        $distanceKm = $data['rows'][0]['elements'][0]['distance']['value'] / 1000;
        $ratePerKm = 1.00;

        if ($bairro == "Vila Centenário") {
            $taxa = 3.00;
        } else {
            $taxa = 3.00 + ($distanceKm * $ratePerKm);
        }

        $_SESSION['taxa'] = "R$ " . number_format($taxa, 2, ",", ".");
        return true;
    }
}
