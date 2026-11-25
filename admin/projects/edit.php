<?php
require_once dirname(__DIR__) . '/auth.php';
admin_auth_check();

$id = $_GET['id'] ?? null;
$project = null;
$isEdit = false;
$errors = [];

// Carregar para edição
if ($id) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    if ($project) {
        $project['technologies'] = json_decode($project['technologies'] ?? '[]', true) ?: [];
        $isEdit = true;
    } else {
        header('Location: ' . admin_url('projects/index.php'));
        exit;
    }
}

// Processar POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $errors[] = 'Token inválido. Tente novamente.';
        goto render;
    }

    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $year = trim($_POST['year'] ?? date('Y'));
    $description = trim($_POST['description'] ?? '');
    $long_desc = trim($_POST['long_description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $url = trim($_POST['url'] ?? '');
    $technologies = array_filter(array_map('trim', explode(',', $_POST['technologies'] ?? '')));
    $status = in_array($_POST['status'] ?? '', ['published', 'draft'], true) ? $_POST['status'] : 'draft';
    $oldImage = $project['image'] ?? null;
    $uploaded = false;

    if (!$title)
        $errors[] = 'Título é obrigatório.';
    if (!$category)
        $errors[] = 'Categoria é obrigatória.';
    if (!valid_project_url($url))
        $errors[] = 'URL inválida: use um endereço http:// ou https://.';

    if (empty($errors) && ($file = upload_image('image_file', 'proj_', $errors))) {
        $image = $file;
        $uploaded = true;
    }

    if (empty($errors)) {
        try {
            $pdo = Database::getInstance();
            if ($isEdit) {
                // Atualizar existente
                // Slug fica fixo na edição para não quebrar URLs já publicadas
                $stmt = $pdo->prepare("UPDATE projects SET title = ?, category = ?, year = ?, image = ?, description = ?, long_description = ?, technologies = ?, url = ?, status = ? WHERE id = ?");
                $stmt->execute([
                    $title,
                    $category,
                    $year,
                    $image,
                    $description,
                    $long_desc,
                    json_encode(array_values($technologies)),
                    $url,
                    $status,
                    $id
                ]);
                if ($uploaded && $oldImage && $oldImage !== $image) {
                    delete_upload($pdo, $oldImage);
                }
            } else {
                // Novo projeto (id gerado pelo banco)
                $stmt = $pdo->prepare("INSERT INTO projects (slug, title, category, year, image, description, long_description, technologies, url, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    unique_slug($pdo, 'projects', generate_slug($title)),
                    $title,
                    $category,
                    $year,
                    $image,
                    $description,
                    $long_desc,
                    json_encode(array_values($technologies)),
                    $url,
                    $status,
                    date('Y-m-d H:i:s')
                ]);
                $id = $pdo->lastInsertId();
            }
            Seo::save(seo_fields_from_post("project.$id"));
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Projeto salvo com sucesso.'];
            header('Location: ' . admin_url('projects/index.php'));
            exit;
        } catch (Exception $e) {
            error_log('Admin projects save: ' . $e->getMessage());
            $errors[] = 'Erro ao salvar no banco de dados.';
        }
    }
}

render:
$pageTitle = $isEdit ? 'Editar Projeto' : 'Novo Projeto';
?>
<?php
$page_title = $pageTitle . ' — Admin VH';
$body_class = 'admin-body';
include dirname(__DIR__) . '/partials/header.php';
?>

<div class="page-header">
    <div>
        <a href="<?= admin_url('projects/index.php') ?>" class="back-link-admin">← Projetos</a>
        <h2><?= htmlspecialchars($pageTitle) ?></h2>
    </div>
</div>

<?php if ($errors): ?>
    <div class="admin-alert error"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
<?php endif; ?>

<form method="POST" class="admin-form" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

    <div class="form-grid-2">
        <div class="form-group">
            <label>Título *</label>
            <input type="text" name="title" value="<?= htmlspecialchars($project['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Categoria *</label>
            <input type="text" name="category" placeholder="Ex: Web Design / Development"
                value="<?= htmlspecialchars($project['category'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Ano</label>
            <input type="text" name="year" value="<?= htmlspecialchars($project['year'] ?? date('Y')) ?>">
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="published" <?= ($project['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>
                    Publicado</option>
                <option value="draft" <?= ($project['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Rascunho</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Imagem (nome do arquivo ou URL)</label>
        <input type="text" name="image" placeholder="https://... ou projeto.avif"
            value="<?= htmlspecialchars($project['image'] ?? '') ?>" style="margin-bottom: 8px;">
        <input type="file" name="image_file" accept="image/*">
        <small>Faça upload de uma nova imagem ou deixe em branco para manter a URL/nome especificado acima.</small>
    </div>

    <div class="form-group">
        <label>URL do Projeto (opcional)</label>
        <input type="url" name="url" placeholder="https://..." value="<?= htmlspecialchars($project['url'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label>Descrição Curta (aparece no card da listagem)</label>
        <textarea name="description" rows="3"><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label>Descrição Completa (aparece na página do projeto)</label>
        <textarea name="long_description"
            rows="6"><?= htmlspecialchars($project['long_description'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label>Tecnologias (separadas por vírgula)</label>
        <input type="text" name="technologies" placeholder="React, PHP, PostgreSQL"
            value="<?= htmlspecialchars(implode(', ', $project['technologies'] ?? [])) ?>">
    </div>

    <?php
    $seoKey = $isEdit ? "project.$id" : null;
    $seoUrl = rtrim(BASE_URL, '/') . '/projects/show/' . ($project['slug'] ?? '…');
    $seoTitleSuffix = ' | Projetos';
    $seoDescFallback = 'description';
    include dirname(__DIR__) . '/partials/seo-fields.php';
    ?>

    <div class="form-actions">
        <button type="submit" class="btn-primary"><?= $isEdit ? 'Salvar Alterações' : 'Criar Projeto' ?></button>
        <a href="<?= admin_url('projects/index.php') ?>" class="btn-secondary">Cancelar</a>
    </div>
</form>

<?php include dirname(__DIR__) . '/partials/footer.php'; ?>