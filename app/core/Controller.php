<?php

namespace Name\Core;

class Controller
{
    public $dados;
    public $dados2;

    public function __construct()
    {
        $this->dados = array();
    }

    public function carregarTemplate($nomeView, $dadosModel = array(), $dados2 = array())
    {
        $this->dados = $dadosModel;
        $this->dados2 = $dados2;

        require 'assets/views/template.php';
    }

    public function carregarView($nomeView, $dadosModel = array(), $dados2 = array())
    {
        $this->dados2 = $dados2;
        extract($dadosModel);

        require "assets/views/" . $nomeView . ".php";
    }
}
