<?php


class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // 1. Verificar Controlador
        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            
            if (file_exists(APP_PATH . '/controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                // Se o controlador na URL não existe, dispara 404
                $this->controller = new ErrorController();
                $this->controller->index();
                return;
            }
        }

        // Instanciar o controlador se ainda for apenas o nome (string)
        if (is_string($this->controller)) {
            $this->controller = new $this->controller;
        }

        // 2. Verificar Método
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                // Se o método na URL não existe, dispara 404
                $this->controller = new ErrorController();
                $this->controller->index();
                return;
            }
        }

        $this->params = $url ? array_values($url) : [];
        $this->execute();
    }

    private function execute()
    {
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(
                rtrim($_GET['url'], '/'),
                FILTER_SANITIZE_URL
            ));
        }
        
        return [];
    }
}
