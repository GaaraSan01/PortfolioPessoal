<?php

class AboutController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Sobre',
            'metaDescription' => 'Saiba mais sobre a trajetória, expertise e filosofia de trabalho de Vinicius Henrique.'
        ];

        $this->view('about', $data);
    }
}
