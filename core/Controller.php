<?php


class Controller
{

    protected function model($model)
    {
        if (file_exists(APP_PATH . '/models/' . $model . '.php')) {
            require_once APP_PATH . '/models/' . $model . '.php';
            return new $model();
        }

        throw new Exception("Model {$model} não encontrado");
    }

    protected function view($view, $data = [])
    {
        $viewObj = new View();
        $viewObj->render($view, $data);
    }

    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function validateCsrfToken()
    {
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }

    protected function generateCsrfToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Admin logado vê rascunhos (prévia); mesma expiração por inatividade do painel
    protected function isAdmin()
    {
        return ($_SESSION['admin_logged_in'] ?? false) === true
            && time() - ($_SESSION['admin_last_seen'] ?? 0) <= ADMIN_IDLE_TIMEOUT;
    }

    // $table/$where/$order vêm só do código; valores do usuário vão em $params
    protected function paginate($table, $where, array $params, $order, $perPage)
    {
        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $where");
            $stmt->execute($params);
            $totalPages = max(1, (int)ceil($stmt->fetchColumn() / $perPage));
            $page = min(max(1, (int)($_GET['page'] ?? 1)), $totalPages);
            $offset = ($page - 1) * $perPage;
            $stmt = $pdo->prepare("SELECT * FROM $table WHERE $where ORDER BY $order LIMIT $perPage OFFSET $offset");
            $stmt->execute($params);
            return [$stmt->fetchAll(), $page, $totalPages];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [[], 1, 1];
        }
    }

    // Rascunho conta como inexistente (404) para quem não é admin
    protected function findPublishedBySlug($table, $slug)
    {
        try {
            $stmt = Database::getInstance()->prepare("SELECT * FROM $table WHERE slug = ?");
            $stmt->execute([$slug]);
            $row = $stmt->fetch();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
        if (!$row || (($row['status'] ?? '') !== 'published' && !$this->isAdmin())) {
            return null;
        }
        return $row;
    }

    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    protected function redirect($url)
    {
        header('Location: ' . url($url));
        exit;
    }

 
    protected function sanitize($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
