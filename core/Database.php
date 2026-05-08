<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $conn = DB_CONNECTION;
        try {
            if ($conn === 'sqlite') {
                $dbPath = DB_DATABASE;
                // Se o caminho não for absoluto, transforma em absoluto a partir da raiz
                if (strpos($dbPath, '/') !== 0 && strpos($dbPath, ':\\') !== 1) {
                    $dbPath = ROOT . '/' . $dbPath;
                }
                
                // Garantir que a pasta exista
                $dir = dirname($dbPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }

                $this->pdo = new PDO("sqlite:" . $dbPath);
            } else {
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_DATABASE . ";charset=utf8mb4";
                $this->pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
            }
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            die("Erro de conexão com o banco de dados. Verifique os logs.");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}
