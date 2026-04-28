<?php
// Shop / Catálogo
$category_slug = $_GET['category'] ?? null;
$search = $_GET['search'] ?? null;
$category_id = null;
$current_category = null;

$categories = [];
$products = [];

try {
    $categories = getCategories();
    if ($category_slug) {
        $current_category = getCategoryBySlug($category_slug);
        if ($current_category) $category_id = $current_category['id'];
    }
    $products = getProducts(null, $category_id, $search);
} catch (Exception $e) {}
?>

<!-- Page Header -->
<div class="page-header">
    <a href="<?= BASE_URL ?>/?page=home" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <h1 class="page-title">
        <?= $current_category ? sanitize($current_category['nome']) : ($search ? 'Resultados' : 'Catálogo Completo') ?>
    </h1>
</div>

<!-- Shop Hero -->
<div class="shop-hero">
    <h2>Descubra as nossas <em>Fórmulas</em></h2>
    <p>Suplementação natural de excelência com base na Medicina Tradicional Chinesa.</p>
</div>

<!-- Search Bar -->
<div class="shop-search">
    <div class="search-bar">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Procurar fórmula..." value="<?= sanitize($search ?? '') ?>" oninput="searchProducts(this.value)" id="search-input">
        <?php if ($search): ?>
        <a href="<?= BASE_URL ?>/?page=shop" class="search-clear"><i class="fas fa-times"></i></a>
        <?php endif; ?>
    </div>
</div>

<!-- Categories Chips -->
<div class="categories-scroll">
    <button class="category-chip <?= !$category_slug ? 'active' : '' ?>" onclick="filterCategory('')">
        <i class="fas fa-th-large"></i> Todas
    </button>
    <?php foreach ($categories as $cat): ?>
    <button class="category-chip <?= $category_slug === $cat['slug'] ? 'active' : '' ?>" onclick="filterCategory('<?= $cat['slug'] ?>')">
        <?= $cat['icone'] ?> <?= sanitize($cat['nome']) ?>
    </button>
    <?php endforeach; ?>
</div>

<!-- Results Bar -->
<div class="shop-results-bar">
    <span class="product-count">
        <strong><?= count($products) ?></strong> fórmula<?= count($products) !== 1 ? 's' : '' ?>
        <?= $search ? "para \"" . sanitize($search) . "\"" : '' ?>
        <?= $current_category ? 'em ' . sanitize($current_category['nome']) : '' ?>
    </span>
    <div class="shop-view-toggle">
        <button class="view-btn active" onclick="setGridView(2)" title="Grelha"><i class="fas fa-th"></i></button>
        <button class="view-btn" onclick="setGridView(1)" title="Lista"><i class="fas fa-th-list"></i></button>
    </div>
</div>

<!-- Products Grid -->
<div class="products-grid" id="productsGrid">
    <?php if (empty($products)): ?>
    <div class="shop-empty-state">
        <div class="empty-icon-circle"><i class="fas fa-leaf"></i></div>
        <h3>Nenhuma fórmula encontrada</h3>
        <p>Tente outra pesquisa ou explore todas as categorias.</p>
        <a href="<?= BASE_URL ?>/?page=shop" class="btn btn-outline" style="width: auto; padding: 10px 24px; font-size: 0.78rem;">Ver Catálogo Completo</a>
    </div>
    <?php else: ?>
    <?php foreach ($products as $i => $p): ?>
    <a href="<?= BASE_URL ?>/?page=product&id=<?= $p['id'] ?>" class="grid-product-card animate-on-scroll" style="animation-delay: <?= $i * 0.05 ?>s">
        <div class="product-img">
            <?php $imgUrl = getProductImageUrl($p['imagem']); ?>
            <?php if (strpos($imgUrl, 'placeholder') !== false): ?>
                <div class="product-placeholder"><i class="fas fa-leaf"></i></div>
            <?php else: ?>
                <img src="<?= $imgUrl ?>" alt="<?= sanitize($p['nome']) ?>">
            <?php endif; ?>
        </div>
        <div class="product-info">
            <div class="product-category"><?= sanitize($p['categoria_nome']) ?></div>
            <div class="product-name"><?= sanitize($p['nome']) ?></div>
            <div class="product-price-row">
                <span class="product-price"><?= formatPrice($p['preco']) ?></span>
                <span class="product-detail-link">Ver <i class="fas fa-arrow-right"></i></span>
            </div>
        </div>
    </a>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function setGridView(cols) {
    const grid = document.getElementById('productsGrid');
    const btns = document.querySelectorAll('.view-btn');
    btns.forEach(b => b.classList.remove('active'));
    
    if (cols === 1) {
        grid.style.gridTemplateColumns = '1fr';
        btns[1].classList.add('active');
    } else {
        grid.style.gridTemplateColumns = '1fr 1fr';
        btns[0].classList.add('active');
    }
}
</script>
