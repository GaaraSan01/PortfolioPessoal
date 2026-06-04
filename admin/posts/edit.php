<?php
require_once dirname(__DIR__) . '/auth.php';
admin_auth_check();

$id     = $_GET['id'] ?? null;
$post   = null;
$isEdit = false;
$errors = [];

if ($id) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
    if ($post) {
        $isEdit = true;
    } else {
        header('Location: ' . admin_url('posts/index.php')); exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) { $errors[] = 'Token inválido.'; goto render; }

    $title        = trim($_POST['title'] ?? '');
    $category     = trim($_POST['category'] ?? '');
    $date_display = trim($_POST['date'] ?? date('d M, Y'));
    $date_iso     = trim($_POST['date_iso'] ?? date('Y-m-d'));
    $excerpt      = trim($_POST['excerpt'] ?? '');
    $content      = $_POST['content'] ?? '';
    $reading_time = (int)($_POST['reading_time'] ?? 5);
    $status       = $_POST['status'] ?? 'draft';

    if (!$title)   $errors[] = 'Título é obrigatório.';
    if (!$excerpt) $errors[] = 'Resumo é obrigatório.';
    if (!$content) $errors[] = 'Conteúdo é obrigatório.';

    if (empty($errors)) {
        try {
            $pdo = Database::getInstance();
            if ($isEdit) {
                $stmt = $pdo->prepare("UPDATE posts SET slug = ?, title = ?, category = ?, date = ?, date_iso = ?, excerpt = ?, content = ?, reading_time = ?, status = ? WHERE id = ?");
                $stmt->execute([
                    generate_slug($title),
                    $title,
                    $category,
                    $date_display,
                    $date_iso,
                    $excerpt,
                    $content,
                    $reading_time,
                    $status,
                    $id
                ]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO posts (id, slug, title, category, date, date_iso, excerpt, content, reading_time, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    uniqid(),
                    generate_slug($title),
                    $title,
                    $category,
                    $date_display,
                    $date_iso,
                    $excerpt,
                    $content,
                    $reading_time,
                    $status,
                    date('Y-m-d H:i:s')
                ]);
            }
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Post salvo com sucesso.'];
            header('Location: ' . admin_url('posts/index.php'));
            exit;
        } catch (Exception $e) {
            $errors[] = 'Erro ao salvar no banco de dados: ' . $e->getMessage();
        }
    }
}

render:
$pageTitle = $isEdit ? 'Editar Post' : 'Novo Post';
?>
<?php
$page_title = $pageTitle . ' — Admin VH';
$body_class = 'admin-body';
include dirname(__DIR__) . '/partials/header.php';
?>

        <div class="page-header">
            <div>
                <a href="<?= admin_url('posts/index.php') ?>" class="back-link-admin">← Posts</a>
                <h2><?= $pageTitle ?></h2>
            </div>
        </div>

        <?php if ($errors): ?>
        <div class="admin-alert error"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
        <?php endif; ?>

        <form method="POST" class="admin-form" id="postForm">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="form-grid-2">
                <div class="form-group" style="grid-column: 1/-1;">
                    <label>Título *</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($post['title'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Categoria</label>
                    <input type="text" name="category" placeholder="Tecnologia, Design, Dev..." value="<?= htmlspecialchars($post['category'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="published" <?= ($post['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Publicado</option>
                        <option value="draft"     <?= ($post['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Rascunho</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Data (exibição) ex: 24 Abr, 2024</label>
                    <input type="text" name="date" value="<?= htmlspecialchars($post['date'] ?? date('d M, Y')) ?>">
                </div>
                <div class="form-group">
                    <label>Data ISO (para ordenação)</label>
                    <input type="date" name="date_iso" value="<?= htmlspecialchars($post['date_iso'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="form-group">
                    <label>Tempo de leitura (minutos)</label>
                    <input type="number" name="reading_time" min="1" max="60" value="<?= (int)($post['reading_time'] ?? 5) ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Resumo / Excerpt *</label>
                <textarea name="excerpt" rows="3"><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
            </div>

            <!-- Editor Markdown com Preview -->
            <div class="form-group">
                <div class="editor-header">
                    <label>Conteúdo em Markdown *</label>
                    <div class="editor-tabs">
                        <button type="button" class="tab-btn active" data-tab="write">Escrever</button>
                        <button type="button" class="tab-btn" data-tab="preview">Preview</button>
                    </div>
                </div>
                <div class="editor-container">
                    <div class="editor-pane write-pane active">
                        <textarea name="content" id="markdownInput" rows="24" placeholder="# Seu título

Seu conteúdo em Markdown aqui...

## Subtítulo

Parágrafo com **negrito** e *itálico*.

```javascript
console.log('Exemplo de código');
```"><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
                    </div>
                    <div class="editor-pane preview-pane" id="markdownPreview">
                        <div class="preview-content post-body"></div>
                    </div>
                </div>
                <small>Suporte completo a Markdown: títulos, negrito, listas, links, código e blockquotes.</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary"><?= $isEdit ? 'Salvar Alterações' : 'Publicar Post' ?></button>
                <a href="<?= admin_url('posts/index.php') ?>" class="btn-secondary">Cancelar</a>
            </div>
        </form>

<?php 
$extra_js = '
<!-- marked.js para preview Markdown -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="' . BASE_URL . '/admin/assets/js/admin.js"></script>
';
include dirname(__DIR__) . '/partials/footer.php'; 
?>

