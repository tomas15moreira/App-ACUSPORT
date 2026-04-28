<?php
if (!isLoggedIn() || !isAdmin()) { header('Location: ' . BASE_URL . '/?page=login'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    adminDeleteProduct((int)$_POST['delete_id']);
    header('Location: ' . BASE_URL . '/?page=admin-products&deleted=1');
    exit;
}

$products = getProducts();
$categories = getCategories();
?>

<div class="admin-header">
    <h1><i class="fas fa-shield-alt"></i> AcuSport</h1>
    <a href="<?= BASE_URL ?>/?page=home" class="admin-back-link"><i class="fas fa-store"></i> Ver Loja</a>
</div>

<nav class="admin-nav">
    <a href="<?= BASE_URL ?>/?page=admin"><i class="fas fa-chart-pie"></i> Dashboard</a>
    <a href="<?= BASE_URL ?>/?page=admin-orders"><i class="fas fa-box"></i> Encomendas</a>
    <a href="<?= BASE_URL ?>/?page=admin-products" class="active"><i class="fas fa-leaf"></i> Produtos</a>
    <a href="<?= BASE_URL ?>/?page=admin-users"><i class="fas fa-users"></i> Clientes</a>
</nav>

<div class="admin-page">
    <div class="admin-page-title">
        <span>Produtos (<?= count($products) ?>)</span>
        <a href="<?= BASE_URL ?>/?page=admin-product-edit&id=new" class="btn-add-product"><i class="fas fa-plus"></i> Novo</a>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
    <div class="admin-flash success"><i class="fas fa-check-circle"></i> Produto eliminado com sucesso.</div>
    <?php endif; ?>

    <?php foreach ($products as $p): ?>
    <div class="product-admin-card">
        <div class="product-admin-img">
            <?php $imgUrl = getProductImageUrl($p['imagem']); ?>
            <?php if (strpos($imgUrl, 'placeholder') !== false): ?>
                <i class="fas fa-leaf" style="color: var(--sage); font-size: 1.1rem;"></i>
            <?php else: ?>
                <img src="<?= $imgUrl ?>" alt="<?= sanitize($p['nome']) ?>">
            <?php endif; ?>
        </div>
        <div class="product-admin-info">
            <div class="product-admin-name"><?= sanitize($p['nome']) ?></div>
            <div class="product-admin-meta">
                <?= sanitize($p['categoria_nome']) ?> · Stock: <?= $p['stock'] ?>
                <?= $p['destaque'] ? ' ⭐' : '' ?>
            </div>
        </div>
        <span class="product-admin-price"><?= formatPrice($p['preco']) ?></span>
        <div class="product-admin-actions">
            <a href="<?= BASE_URL ?>/?page=admin-product-edit&id=<?= $p['id'] ?>" title="Editar"><i class="fas fa-pen"></i></a>
            <button type="button" class="delete-btn" title="Eliminar" onclick="openDeleteProductModal(<?= $p['id'] ?>, '<?= sanitize($p['nome']) ?>')"><i class="fas fa-trash"></i></button>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Delete Product Modal -->
<div id="deleteProductModal" class="admin-modal-overlay">
    <div class="admin-modal">
        <div class="admin-modal-icon danger"><i class="fas fa-leaf"></i></div>
        <h3>Eliminar Produto</h3>
        <p>Tem a certeza que pretende eliminar</p>
        <p style="margin: 8px 0 0;"><strong id="delProductName" style="color: var(--text-dark); font-size: 0.92rem;"></strong></p>
        <div class="modal-warning"><i class="fas fa-exclamation-triangle"></i> O produto será removido permanentemente do catálogo.</div>
        <div class="modal-actions">
            <button onclick="closeDeleteProductModal()" class="btn-cancel">Cancelar</button>
            <form id="deleteProductForm" method="POST" style="flex:1; margin:0;">
                <input type="hidden" name="delete_id" id="deleteProductId" value="">
                <button type="submit" class="btn-danger" style="width:100%;">Eliminar</button>
            </form>
        </div>
    </div>
</div>

<script>
function openDeleteProductModal(id, name) {
    document.getElementById('deleteProductId').value = id;
    document.getElementById('delProductName').textContent = name;
    document.getElementById('deleteProductModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeDeleteProductModal() {
    document.getElementById('deleteProductModal').style.display = 'none';
    document.body.style.overflow = '';
}
document.getElementById('deleteProductModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteProductModal();
});
</script>
