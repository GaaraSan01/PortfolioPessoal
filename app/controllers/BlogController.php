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
        $searchQuery = trim((string)($_GET['q'] ?? ''));
        $where = "status = 'published'";
        $params = [];
        if ($searchQuery !== '') {
            // shortcut: no SQLite o LIKE só ignora maiúsculas em ASCII ("Ç" ≠ "ç"); no MySQL utf8mb4 ignora tudo
            $where .= " AND (title LIKE ? OR excerpt LIKE ? OR category LIKE ? OR content LIKE ?)";
            $params = array_fill(0, 4, '%' . $searchQuery . '%');
        }
        [$postsPaginated, $page, $totalPages] = $this->paginate('posts', $where, $params, 'date_iso DESC', 6);

        $data = [
            'title'           => $searchQuery ? "Pesquisa: {$searchQuery} | Blog" : 'Blog',
            'seoKey'          => $searchQuery ? null : 'page.blog',
            'noindex'         => (bool)$searchQuery,
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

        $post = $this->findPublishedBySlug('posts', $slug);

        if (!$post) {
            http_response_code(404);
            $this->view('404', ['title' => 'Post não encontrado', 'noindex' => true]);
            return;
        }

        // Converter Markdown para HTML via Parsedown
        require_once APP_PATH . '/lib/Parsedown.php';
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
        [$post['content_html'], $post['toc']] = self::addHeadingIds($parsedown->text($post['content'] ?? ''));

        $data = [
            'title'           => $post['title'] . ' | Blog',
            'seoKey'          => 'post.' . $post['id'],
            'ogType'          => 'article',
            'noindex'         => ($post['status'] ?? '') !== 'published',
            'metaDescription' => $post['excerpt'],
            'post'            => $post
        ];
        $this->view('blog-post', $data);
    }

    // Adiciona id aos <h2>/<h3> e devolve [html, toc] para o índice lateral do post
    public static function addHeadingIds(string $html): array
    {
        $toc = [];
        $used = [];
        $html = preg_replace_callback('#<h([23])>(.*?)</h\1>#s', function ($m) use (&$toc, &$used) {
            $text = html_entity_decode(strip_tags($m[2]), ENT_QUOTES, 'UTF-8');
            // iconv TRANSLIT varia por SO (no Windows "ç" vira "c,"), então o mapa é explícito
            $ascii = strtr(mb_strtolower($text, 'UTF-8'), [
                'á'=>'a','à'=>'a','â'=>'a','ã'=>'a','ä'=>'a','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
                'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i','ó'=>'o','ò'=>'o','ô'=>'o','õ'=>'o','ö'=>'o',
                'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u','ç'=>'c','ñ'=>'n',
            ]);
            $slug = trim(preg_replace('/[^a-z0-9]+/', '-', $ascii), '-') ?: 'secao';
            if (!ctype_alpha($slug[0])) $slug = 's-' . $slug;
            $id = $slug;
            for ($i = 2; isset($used[$id]); $i++) $id = "$slug-$i";
            $used[$id] = true;
            $toc[] = ['id' => $id, 'text' => $text, 'level' => (int)$m[1]];
            return "<h{$m[1]} id=\"$id\">{$m[2]}</h{$m[1]}>";
        }, $html);
        return [$html, $toc];
    }
}
