<?php
// Product Detail
$product_id = $_GET['id'] ?? null;
$product = null;
if ($product_id) {
    try { $product = getProduct($product_id); } catch (Exception $e) {}
}
if (!$product) { echo '<div style="text-align:center;padding:60px 20px"><h2>Produto não encontrado</h2><a href="'.BASE_URL.'/?page=shop" class="btn btn-gold" style="margin-top:16px;width:auto">Ver Loja</a></div>'; return; }
?>

<div class="page-header">
    <a href="<?= BASE_URL ?>/?page=shop" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <h1 class="page-title"><?= sanitize($product['nome']) ?></h1>
</div>

<div class="product-detail-img fade-in">
    <?php $imgUrl = getProductImageUrl($product['imagem']); ?>
    <?php if (strpos($imgUrl, 'placeholder') !== false): ?>
        <div class="product-placeholder" style="width:200px;height:200px;border-radius:16px"><i class="fas fa-leaf" style="font-size:3rem"></i></div>
    <?php else: ?>
        <img src="<?= $imgUrl ?>" alt="<?= sanitize($product['nome']) ?>">
    <?php endif; ?>
</div>

<div class="product-detail-body slide-up">
    <div class="product-category"><?= sanitize($product['categoria_nome']) ?></div>
    <h1><?= sanitize($product['nome']) ?></h1>
    <div class="price"><?= formatPrice($product['preco']) ?></div>

    <div class="quantity-row">
        <span class="qty-label">Quantidade</span>
        <div class="qty-selector">
            <button class="qty-btn" onclick="changeQty(document.getElementById('qty-input'), -1)">−</button>
            <input type="number" class="qty-value" id="qty-input" value="1" min="1" max="99" readonly>
            <button class="qty-btn" onclick="changeQty(document.getElementById('qty-input'), 1)">+</button>
        </div>
    </div>

    <div class="action-buttons">
        <button class="btn btn-outline" onclick="addToCart(<?= $product['id'] ?>, parseInt(document.getElementById('qty-input').value))" style="border-radius: 12px;">
            <i class="fas fa-shopping-cart" style="font-size: 0.9rem;"></i> Adicionar
        </button>
        <button class="btn btn-gold" onclick="addToCart(<?= $product['id'] ?>, parseInt(document.getElementById('qty-input').value)); setTimeout(()=>window.location.href=BASE_URL+'/?page=cart', 600)" style="border-radius: 12px;">
            Comprar Já <i class="fas fa-arrow-right" style="font-size: 0.8rem; margin-left: 4px;"></i>
        </button>
    </div>

    <div class="badges-row">
        <div class="badge-item"><span class="badge-icon"><i class="fas fa-leaf"></i></span><span>100% Natural</span></div>
        <div class="badge-item"><span class="badge-icon"><i class="fas fa-microscope"></i></span><span>Rigor Clínico</span></div>
        <div class="badge-item"><span class="badge-icon"><i class="fas fa-yin-yang"></i></span><span>Fórmula MTC</span></div>
    </div>

    <?php if ($product['descricao_mtc']): ?>
    <div class="accordion">
        <div class="accordion-header" onclick="toggleAccordion(this)"><h3 style="display: flex; align-items: center; gap: 8px;"><i class="fas fa-book-medical" style="color: var(--gold); font-size: 0.8rem;"></i> Descrição e Benefícios MTC</h3><i class="fas fa-chevron-down accordion-icon"></i></div>
        <div class="accordion-body"><p><?= nl2br(sanitize($product['descricao_mtc'])) ?></p></div>
    </div>
    <?php endif; ?>

    <?php if ($product['modo_utilizacao']): ?>
    <div class="accordion">
        <div class="accordion-header" onclick="toggleAccordion(this)"><h3 style="display: flex; align-items: center; gap: 8px;"><i class="fas fa-mortar-pestle" style="color: var(--gold); font-size: 0.8rem;"></i> Modo de Utilização &amp; Dosagem</h3><i class="fas fa-chevron-down accordion-icon"></i></div>
        <div class="accordion-body"><p><?= nl2br(sanitize($product['modo_utilizacao'])) ?></p></div>
    </div>
    <?php endif; ?>

    <?php if ($product['restricoes']): ?>
    <div class="accordion">
        <div class="accordion-header" onclick="toggleAccordion(this)"><h3 style="display: flex; align-items: center; gap: 8px;"><i class="fas fa-exclamation-triangle" style="color: var(--gold); font-size: 0.8rem;"></i> Restrições &amp; Avisos Legais</h3><i class="fas fa-chevron-down accordion-icon"></i></div>
        <div class="accordion-body"><p><?= nl2br(sanitize($product['restricoes'])) ?></p></div>
    </div>
    <?php endif; ?>
</div>

<script>
function toggleAccordion(header) {
    var acc = header.closest('.accordion');
    acc.classList.toggle('closed');
}
</script>
