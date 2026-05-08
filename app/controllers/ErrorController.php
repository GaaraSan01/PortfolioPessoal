<?php

class ErrorController extends Controller
{
    public function index()
    {
        http_response_code(404);
        
        $data = [
            'title' => 'Página não encontrada',
            'metaDescription' => 'Desculpe, a página que você está procurando não existe ou foi movida.'
        ];

        $this->view('404', $data);
    }
}
