<?php

namespace Name\Controllers;

use Name\Core\Controller;

class templateController extends Controller
{
    public function index()
    {
        $this->carregarTemplate("template");
    }
}
