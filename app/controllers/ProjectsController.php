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
        [$rows, $page, $totalPages] = $this->paginate('projects', "status = 'published'", [], 'created_at DESC', 6);
        $projectsPaginated = array_map([$this, 'withImageUrl'], $rows);

        $data = [
            'title'           => 'Projetos',
            'seoKey'          => 'page.projects',
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

        $project = $this->findPublishedBySlug('projects', $slug);

        if (!$project) {
            http_response_code(404);
            $this->view('404', ['title' => 'Projeto não encontrado', 'noindex' => true]);
            return;
        }

        $project = $this->withImageUrl($project);

        $data = [
            'title'           => $project['title'] . ' | Projetos',
            'seoKey'          => 'project.' . $project['id'],
            'ogImage'         => $project['image'] ?? '',
            'noindex'         => ($project['status'] ?? '') !== 'published',
            'metaDescription' => $project['description'],
            'project'         => $project
        ];
        $this->view('project-single', $data);
    }

    private function withImageUrl(array $p)
    {
        $img = $p['image'] ?? '';
        $p['image_url'] = filter_var($img, FILTER_VALIDATE_URL) ? $img : uploads('/' . ltrim($img, '/'));
        $p['technologies'] = json_decode($p['technologies'] ?? '[]', true) ?: [];
        return $p;
    }
}
