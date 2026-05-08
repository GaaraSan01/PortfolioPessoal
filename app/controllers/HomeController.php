<?php

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Home',
            'metaDescription' => 'Portfolio de Vinicius Henrique - Desenvolvimento Web',
            'projects' => $this->getProjects()
        ];

        $this->view('home', $data);
    }

    private function getProjects()
    {
        require_once ROOT . '/core/Database.php';
        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->query("SELECT * FROM projects WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
            $projects = $stmt->fetchAll();
        } catch (Exception $e) {
            $projects = [];
        }
        
        // Mapear para adicionar "num" formatado e url
        $result = [];
        foreach ($projects as $index => $p) {
            $result[] = [
                'id' => $p['id'],
                'num' => str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'title' => $p['title'],
                'category' => $p['category'],
                'image' => (filter_var($p['image'] ?? '', FILTER_VALIDATE_URL)) ? $p['image'] : uploads('/' . ltrim($p['image'] ?? '', '/')),
                'url' => url('projects/show/' . ($p['slug'] ?? ''))
            ];
        }
        
        return $result;
    }
}
