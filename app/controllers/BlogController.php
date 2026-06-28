<?php

class BlogController extends Controller
{
    private $dataFile;

    public function __construct()
    {
        $this->dataFile = APP_PATH . '/data/posts.json';
    }

    public function index()
    {
        $allPosts = $this->getPosts();
        
        // Search filtering
        $searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
        if ($searchQuery !== '') {
            $search = mb_strtolower($searchQuery, 'UTF-8');
            $allPosts = array_values(array_filter($allPosts, function($post) use ($search) {
                $fields = [
                    mb_strtolower($post['title'] ?? '', 'UTF-8'),
                    mb_strtolower($post['excerpt'] ?? '', 'UTF-8'),
                    mb_strtolower($post['category'] ?? '', 'UTF-8'),
                    mb_strtolower($post['content'] ?? '', 'UTF-8'),
                ];
                foreach ($fields as $field) {
                    if (mb_strpos($field, $search) !== false) {
                        return true;
                    }
                }
                return false;
            }));
        }

        $page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
        $perPage = 6; // Quantidade de posts por página
        $totalItems = count($allPosts);
        $totalPages = max(1, ceil($totalItems / $perPage));
        $page = min($page, $totalPages);
        
        $offset = ($page - 1) * $perPage;
        $postsPaginated = array_slice($allPosts, $offset, $perPage);

        $data = [
            'title'           => $searchQuery ? "Pesquisa: {$searchQuery} | Blog" : 'Blog',
            'metaDescription' => 'Artigos e reflexões de Vinicius Henrique sobre tecnologia e design.',
            'posts'           => $postsPaginated,
            'currentPage'     => $page,
            'totalPages'      => $totalPages,
            'searchQuery'     => $searchQuery
        ];

        $this->view('blog', $data);
    }

    public function post($slug = null)
    {
        if (!$slug) {
            $this->redirect('blog');
            return;
        }

        $post = $this->getPostBySlug($slug);

        if (!$post) {
            http_response_code(404);
            $this->view('404', ['title' => 'Post não encontrado']);
            return;
        }

        // Converter Markdown para HTML via Parsedown
        require_once APP_PATH . '/lib/Parsedown.php';
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
        $post['content_html'] = $parsedown->text($post['content'] ?? '');

        $data = [
            'title'           => $post['title'] . ' | Blog',
            'metaDescription' => $post['excerpt'],
            'post'            => $post
        ];
        $this->view('blog-post', $data);
    }

    public function getPosts()
    {
        $posts = $this->fetchRawPosts();
        $posts = array_filter($posts, fn($p) => ($p['status'] ?? 'published') === 'published');
        usort($posts, fn($a, $b) => strcmp($b['date_iso'] ?? '', $a['date_iso'] ?? ''));
        return array_values($posts);
    }

    private static $cachedPosts = null;

    private function fetchRawPosts()
    {
        if (self::$cachedPosts !== null) {
            return self::$cachedPosts;
        }
        try {
            require_once ROOT . '/core/Database.php';
            $pdo = Database::getInstance();
            $stmt = $pdo->query("SELECT * FROM posts ORDER BY date_iso DESC");
            self::$cachedPosts = $stmt->fetchAll();
        } catch (Exception $e) {
            error_log($e->getMessage());
            self::$cachedPosts = [];
        }
        return self::$cachedPosts;
    }

    private function getPostBySlug($slug)
    {
        $posts = $this->fetchRawPosts();
        foreach ($posts as $post) {
            if ($post['slug'] === $slug) return $post;
        }
        return null;
    }
}
