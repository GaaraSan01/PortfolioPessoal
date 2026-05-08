<?php
require_once dirname(__DIR__) . '/auth.php';
admin_auth_check();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
    header('Location: ' . admin_url('posts/index.php')); exit;
}

$id = $_POST['id'] ?? null;

if ($id) {
    try {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Post excluído com sucesso.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Post não encontrado.'];
        }
    } catch (Exception $e) {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Erro ao excluir post.'];
    }
} else {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'ID não fornecido.'];
}

header('Location: ' . admin_url('posts/index.php'));
exit;
