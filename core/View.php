<?php

class View
{
   
    public function render($viewName, $data = [])
    {
        extract($data);

        $viewFile = VIEWS_PATH . '/pages/' . $viewName . '.php';
        $layoutFile = VIEWS_PATH . '/layouts/main.php';

        if (!file_exists($viewFile)) {
            throw new Exception("View {$viewName} não encontrada");
        }

        ob_start();

        require_once $viewFile;

        $content = ob_get_clean();

        if (file_exists($layoutFile)) {
            require_once $layoutFile;
        } else {
            echo $content;
        }
    }

    
    public static function component($componentName, $data = [])
    {
        extract($data);
        
        $componentFile = VIEWS_PATH . '/components/' . $componentName . '.php';

        if (file_exists($componentFile)) {
            require $componentFile;
        } else {
            throw new Exception("Componente {$componentName} não encontrado");
        }
    }

    
    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    
    public static function css($file)
    {
        return '<link rel="stylesheet" href="' . asset('css/' . $file) . '">';
    }

    public static function js($file)
    {
        return '<script src="' . asset('js/' . $file) . '"></script>';
    }
}
