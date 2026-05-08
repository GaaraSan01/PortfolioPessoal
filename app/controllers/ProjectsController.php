<?php

class ProjectsController extends Controller
{
    private $dataFile;

    public function __construct()
    {
        $this->dataFile = APP_PATH . '/data/projects.json';
    }

    public function index()
    {
        $allProjects = $this->getProjects();
        $page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
        $perPage = 6; // Quantidade de projetos por página
        $totalItems = count($allProjects);
        $totalPages = max(1, ceil($totalItems / $perPage));
        $page = min($page, $totalPages);
        
        $offset = ($page - 1) * $perPage;
        $projectsPaginated = array_slice($allProjects, $offset, $perPage);

        $data = [
            'title'           => 'Projetos',
            'metaDescription' => 'Confira os projetos selecionados desenvolvidos por Vinicius Henrique, focando em UI/UX e performance.',
            'projects'        => $projectsPaginated,
            'currentPage'     => $page,
            'totalPages'      => $totalPages
        ];
        $this->view('projects', $data);
    }

    public function show($slug = null)
    {
        if (!$slug) {
            $this->redirect('projects');
            return;
        }

        $project = $this->getProjectBySlug($slug);

        if (!$project) {
            http_response_code(404);
            $this->view('404', ['title' => 'Projeto não encontrado']);
            return;
        }

        $img = $project['image'] ?? '';
        $project['image_url'] = (filter_var($img, FILTER_VALIDATE_URL)) ? $img : uploads('/' . ltrim($img, '/'));

        $data = [
            'title'           => $project['title'] . ' | Projetos',
            'metaDescription' => $project['description'],
            'project'         => $project
        ];
        $this->view('project-single', $data);
    }

    public function getProjects()
    {
        $projects = $this->fetchRawProjects();
        $published = array_values(array_filter($projects, fn($p) => ($p['status'] ?? 'published') === 'published'));
        
        return array_map(function($p) {
            $img = $p['image'] ?? '';
            $p['image_url'] = (filter_var($img, FILTER_VALIDATE_URL)) ? $img : uploads('/' . ltrim($img, '/'));
            return $p;
        }, $published);
    }

    private static $cachedProjects = null;

    private function fetchRawProjects()
    {
        if (self::$cachedProjects !== null) {
            return self::$cachedProjects;
        }
        try {
            require_once ROOT . '/core/Database.php';
            $pdo = Database::getInstance();
            $stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
            $projects = $stmt->fetchAll();
            foreach ($projects as &$p) {
                $p['technologies'] = json_decode($p['technologies'] ?? '[]', true) ?: [];
            }
            self::$cachedProjects = $projects;
        } catch (Exception $e) {
            error_log($e->getMessage());
            self::$cachedProjects = [];
        }
        
        return self::$cachedProjects;
    }

    private function getProjectBySlug($slug)
    {
        $projects = $this->fetchRawProjects();
        foreach ($projects as $project) {
            if ($project['slug'] === $slug) return $project;
        }
        return null;
    }
}
