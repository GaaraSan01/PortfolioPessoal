<?php
/**
 * Front Controller - Ponto de entrada da aplicação
 * Todas as requisições passam por aqui
 */

// Define o caminho raiz da aplicação
define('ROOT', dirname(__FILE__));

require_once ROOT . '/config/config.php';

require_once ROOT . '/core/Autoloader.php';

Autoloader::register();

$app = new App();
