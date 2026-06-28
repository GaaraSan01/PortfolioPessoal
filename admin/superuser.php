<?php

/**
 * Criação do Super User em ambiênte local
 */

define('ROOT', dirname(__FILE__));
require_once('./config/config.php');


try {
    $pdo = new PDO('sqlite:' . DB_DATABASE);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)";
    $stmt = $pdo->prepare($sql);

    $username = "root";
    $password = password_hash("root2026", PASSWORD_ARGON2ID); 

    
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password_hash', $password);

    $stmt->execute();

    echo "Usuário inserido com sucesso!";

} catch (PDOException $e) {

    echo "Erro ao inserir usuário: " . $e->getMessage();
}