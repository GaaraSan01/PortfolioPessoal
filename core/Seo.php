<?php

/**
 * Configurações de SEO editáveis pelo admin (tabela chave/valor `settings`)
 * e montagem das metas de cada página.
 *
 * Chaves: site.*, person.*, page.{home|about|projects|blog|contact}.{title|description},
 * post.{id}.{title|description|noindex}, project.{id}.{title|description|noindex}.
 */
class Seo
{
    const DEFAULTS = [
        'site.name'          => APP_NAME,
        'site.description'   => 'Portfólio Vinicius Henrique - Desenvolvedor Full Stack especializado em UI/UX e performance.',
        'site.og_image'      => 'og-image.jpg',
        'site.indexing'      => '1',
        'person.name'        => 'Vinicius Henrique',
        'person.job_title'   => 'Desenvolvedor Full Stack',
        'person.description' => 'Desenvolvedor Full Stack especializado em experiências digitais modernas e performáticas.',
        'person.same_as'     => "https://github.com/GaaraSan01\nhttps://www.linkedin.com/in/viniciushms/",
    ];

    const PAGES = [
        'home'     => 'Home',
        'about'    => 'Sobre',
        'projects' => 'Projetos',
        'blog'     => 'Blog',
        'contact'  => 'Contato',
    ];

    const TITLE_MAX = 60;
    const DESCRIPTION_MAX = 160;

    private static $cache = null;

    public static function ensureTable(PDO $pdo): void
    {
        // Criada sob demanda: dispensa migração no MySQL de produção
        $pdo->exec("CREATE TABLE IF NOT EXISTS settings (name VARCHAR(191) NOT NULL PRIMARY KEY, value TEXT)");
    }

    public static function all(): array
    {
        if (self::$cache === null) {
            $rows = [];
            try {
                $pdo = Database::getInstance();
                self::ensureTable($pdo);
                $rows = $pdo->query("SELECT name, value FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);
            } catch (Exception $e) {
                error_log('Seo: ' . $e->getMessage());
            }
            self::$cache = array_merge(self::DEFAULTS, $rows);
        }
        return self::$cache;
    }

    public static function get(string $name, string $default = ''): string
    {
        $v = self::all()[$name] ?? '';
        return $v !== '' ? $v : $default;
    }

    /** Salva vários valores; string vazia apaga a chave (volta ao padrão). */
    public static function save(array $values): void
    {
        $pdo = Database::getInstance();
        self::ensureTable($pdo);
        $del = $pdo->prepare("DELETE FROM settings WHERE name = ?");
        $ins = $pdo->prepare("INSERT INTO settings (name, value) VALUES (?, ?)");
        $pdo->beginTransaction();
        try {
            foreach ($values as $name => $value) {
                $value = trim((string)$value);
                $del->execute([$name]);
                if ($value !== '') $ins->execute([$name, $value]);
            }
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
        self::$cache = null;
    }

    public static function deletePrefix(string $prefix): void
    {
        $pdo = Database::getInstance();
        self::ensureTable($pdo);
        $pdo->prepare("DELETE FROM settings WHERE name LIKE ?")->execute([$prefix . '%']);
        self::$cache = null;
    }

    public static function imageUrl(string $image): string
    {
        if ($image === '') return '';
        return filter_var($image, FILTER_VALIDATE_URL) ? $image : uploads(ltrim($image, '/'));
    }

    public static function indexingEnabled(): bool
    {
        return self::get('site.indexing', '1') === '1';
    }

    /**
     * Metas finais de uma página a partir dos dados da view:
     * title, metaDescription, seoKey (ex. "page.home", "post.12"), ogImage, ogType, noindex.
     */
    public static function meta(array $data): array
    {
        $key = $data['seoKey'] ?? null;
        $title = ($key ? self::get("$key.title") : '') ?: ($data['title'] ?? 'Home');
        $description = ($key ? self::get("$key.description") : '') ?: ($data['metaDescription'] ?? '') ?: self::get('site.description');
        $noindex = !empty($data['noindex'])
            || ($key && self::get("$key.noindex") === '1')
            || !self::indexingEnabled();
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        return [
            'title'       => $title . ' | ' . self::get('site.name', APP_NAME),
            'description' => $description,
            'canonical'   => rtrim(BASE_URL, '/') . $path,
            'image'       => self::imageUrl(($data['ogImage'] ?? '') ?: self::get('site.og_image')),
            'type'        => $data['ogType'] ?? 'website',
            'robots'      => $noindex ? 'noindex, nofollow' : 'index, follow',
        ];
    }

    public static function personJsonLd(): string
    {
        $sameAs = array_values(array_filter(array_map('trim', explode("\n", self::get('person.same_as')))));
        return json_encode([
            '@context'    => 'https://schema.org',
            '@type'       => 'Person',
            'name'        => self::get('person.name'),
            'url'         => rtrim(BASE_URL, '/') . '/',
            'jobTitle'    => self::get('person.job_title'),
            'description' => self::get('person.description'),
            'sameAs'      => $sameAs,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_PRETTY_PRINT);
    }

    /** Problemas de SEO de um item publicado (usado no diagnóstico do admin). */
    public static function issues(string $title, string $description, ?string $image = null): array
    {
        $issues = [];
        $len = fn($s) => mb_strlen($s, 'UTF-8');
        if ($description === '') $issues[] = 'Sem descrição';
        elseif ($len($description) > self::DESCRIPTION_MAX) $issues[] = 'Descrição longa (' . $len($description) . ')';
        elseif ($len($description) < 50) $issues[] = 'Descrição curta (' . $len($description) . ')';
        if ($len($title) > self::TITLE_MAX) $issues[] = 'Título longo (' . $len($title) . ')';
        if ($image !== null && $image === '') $issues[] = 'Sem imagem';
        return $issues;
    }
}
