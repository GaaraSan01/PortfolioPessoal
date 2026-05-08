<?php
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    session_destroy();
}

header('Location: ' . BASE_URL . '/admin/index.php');
exit;
