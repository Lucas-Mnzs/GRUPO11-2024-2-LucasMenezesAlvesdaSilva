<?php

namespace Name\Controllers;

use Name\Core\Controller;

class paginasController extends Controller
{
    public function index()
    {
        $this->carregarView("paginas");
    }
}
