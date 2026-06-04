w
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
    $status = $_POST['status'] ?? 'draft';

    if (!$title)
        $errors[] = 'Título é obrigatório.';
    if (!$category)
        $errors[] = 'Categoria é obrigatória.';

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = ROOT . '/public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $filename = uniqid('proj_') . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', basename($_FILES['image_file']['name']));
        $targetFile = $uploadDir . $filename;

        // Validação de arquivo (Somente imagens)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['image_file']['tmp_name']);
        finfo_close($finfo);

        if (!in_array($ext, $allowedExtensions) || strpos($mime, 'image/') !== 0) {
            $errors[] = 'Apenas arquivos de imagem são permitidos (JPG, PNG, GIF, WEBP, AVIF).';
        } else {
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFile)) {
                $image = $filename;
            } else {
                $errors[] = 'Erro ao fazer upload da imagem.';
            }
        }
    }

    if (empty($errors)) {
        try {
            $pdo = Database::getInstance();
            if ($isEdit) {
                // Atualizar existente
                $stmt = $pdo->prepare("UPDATE projects SET slug = ?, title = ?, category = ?, year = ?, image = ?, description = ?, long_description = ?, technologies = ?, url = ?, status = ? WHERE id = ?");
                $stmt->execute([
                    generate_slug($title),
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
            } else {
                // Novo projeto
                $stmt = $pdo->prepare("INSERT INTO projects (id, slug, title, category, year, image, description, long_description, technologies, url, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    uniqid(),
                    generate_slug($title),
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
            }
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Projeto salvo com sucesso.'];
            header('Location: ' . admin_url('projects/index.php'));
            exit;
        } catch (Exception $e) {
            $errors[] = 'Erro ao salvar no banco de dados: ' . $e->getMessage();
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
        <h2><?= $pageTitle ?></h2>
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

    <div class="form-actions">
        <button type="submit" class="btn-primary"><?= $isEdit ? 'Salvar Alterações' : 'Criar Projeto' ?></button>
        <a href="<?= admin_url('projects/index.php') ?>" class="btn-secondary">Cancelar</a>
    </div>
</form>

<?php include dirname(__DIR__) . '/partials/footer.php'; ?>