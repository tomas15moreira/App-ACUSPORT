<?php
if (!isLoggedIn() || !isAdmin()) { header('Location: ' . BASE_URL . '/?page=login'); exit; }

$product_id = $_GET['id'] ?? 'new';
$is_new = ($product_id === 'new');
$product = null;
$categories = getCategories();
$success = false;

if (!$is_new) {
    $product = getProduct((int)$product_id);
    if (!$product) { echo '<p>Produto não encontrado.</p>'; return; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nome' => trim($_POST['nome'] ?? ''),
        'preco' => floatval($_POST['preco'] ?? 0),
        'descricao_curta' => trim($_POST['descricao_curta'] ?? ''),
        'descricao_mtc' => trim($_POST['descricao_mtc'] ?? ''),
        'modo_utilizacao' => trim($_POST['modo_utilizacao'] ?? ''),
        'restricoes' => trim($_POST['restricoes'] ?? ''),
        'category_id' => (int)($_POST['category_id'] ?? 1),
        'destaque' => isset($_POST['destaque']) ? 1 : 0,
        'stock' => (int)($_POST['stock'] ?? 100),
        'imagem' => trim($_POST['imagem'] ?? ''),
    ];

    if ($is_new) {
        $new_id = adminCreateProduct($data);
        header('Location: ' . BASE_URL . '/?page=admin-product-edit&id=' . $new_id . '&saved=1');
        exit;
    } else {
        adminUpdateProduct((int)$product_id, $data);
        $product = getProduct((int)$product_id);
        $success = true;
    }
}
?>

<div class="admin-header">
    <h1>
        <a href="<?= BASE_URL ?>/?page=admin-products" style="color: rgba(255,255,255,0.5); margin-right: 4px;"><i class="fas fa-arrow-left"></i></a>
        <?= $is_new ? 'Novo Produto' : 'Editar Produto' ?>
    </h1>
    <a href="<?= BASE_URL ?>/?page=admin-products" class="admin-back-link">Cancelar</a>
</div>

<?php if ($success || isset($_GET['saved'])): ?>
<div class="admin-flash success" style="margin: 16px 20px 0; border-radius: 12px;">
    <i class="fas fa-check-circle"></i> Produto guardado com sucesso!
</div>
<?php endif; ?>

<form method="POST" class="admin-form">
    <div class="form-group">
        <label>Nome do Produto *</label>
        <input type="text" name="nome" required value="<?= sanitize($product['nome'] ?? '') ?>" placeholder="Ex: Ponderal Fit 1">
    </div>
    
    <div style="display: flex; gap: 12px;">
        <div class="form-group" style="flex: 1;">
            <label>Preço (€) *</label>
            <input type="number" name="preco" step="0.01" min="0" required value="<?= $product['preco'] ?? '' ?>" placeholder="24.60">
        </div>
        <div class="form-group" style="flex: 1;">
            <label>Stock</label>
            <input type="number" name="stock" min="0" value="<?= $product['stock'] ?? 100 ?>" placeholder="100">
        </div>
    </div>

    <div class="form-group">
        <label>Categoria *</label>
        <select name="category_id" required>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= sanitize($cat['nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Descrição Curta</label>
        <textarea name="descricao_curta" rows="2" placeholder="Breve descrição do produto..."><?= sanitize($product['descricao_curta'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label>Descrição MTC Completa</label>
        <textarea name="descricao_mtc" rows="4" placeholder="Descrição detalhada, ingredientes, ação MTC..."><?= sanitize($product['descricao_mtc'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label>Modo de Utilização</label>
        <textarea name="modo_utilizacao" rows="2" placeholder="Dosagem e modo de tomar..."><?= sanitize($product['modo_utilizacao'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label>Restrições / Avisos</label>
        <textarea name="restricoes" rows="2" placeholder="Contraindicações e avisos legais..."><?= sanitize($product['restricoes'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label>Imagem (nome do ficheiro)</label>
        <input type="text" name="imagem" value="<?= sanitize($product['imagem'] ?? '') ?>" placeholder="nome-produto.jpg">
        <span style="font-size: 0.7rem; color: var(--text-light); margin-top: 6px; display: block;">📁 Coloque a imagem em <code style="background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 0.68rem;">/assets/images/products/</code></span>
    </div>

    <div class="form-group">
        <div class="toggle-row">
            <label class="toggle-switch" style="margin: 0;">
                <input type="checkbox" name="destaque" value="1" <?= ($product['destaque'] ?? 0) ? 'checked' : '' ?>>
                <span class="toggle-slider"></span>
            </label>
            <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-dark);">⭐ Produto em Destaque</span>
        </div>
    </div>

    <button type="submit" class="btn btn-gold btn-block" style="margin-top: 8px; border-radius: 14px; padding: 15px; font-size: 0.88rem;">
        <i class="fas fa-save"></i> <?= $is_new ? 'Criar Produto' : 'Guardar Alterações' ?>
    </button>
</form>
