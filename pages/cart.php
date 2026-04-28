<?php
// Cart Page
$items = [];
$subtotal = 0;
try {
    $items = getCartItems();
    $subtotal = getCartTotal();
    $totals = calculateCartTotals();
} catch (Exception $e) {}

$shipping_threshold = $totals['shipping_threshold'] ?? 60;
$shipping_cost = $totals['shipping_cost'] ?? 4.90;

$user_plan = isset($_SESSION['user_plan']) ? $_SESSION['user_plan'] : null;
$plan_discount_pct = 0;
if ($user_plan === 'vitalidade') $plan_discount_pct = 0.15;
if ($user_plan === 'mestre') $plan_discount_pct = 0.25;

$subtotal = $totals['subtotal'] ?? 0;
$free_formulas_used = $totals['free_formulas_used'] ?? 0;
$free_discount_amount = $totals['free_discount_amount'] ?? 0;
$pct_discount_amount = $totals['pct_discount_amount'] ?? 0;
$subtotal_after_discount = $totals['subtotal_after_discount'] ?? 0;
$free_shipping = $totals['free_shipping'] ?? false;
$total = $totals['total'] ?? 0;

$remaining = $free_shipping ? 0 : ($shipping_threshold - $subtotal_after_discount);
$progress_pct = $free_shipping ? 100 : min(($subtotal_after_discount / $shipping_threshold) * 100, 100);
?>
<script>
    const USER_PLAN = <?= json_encode($user_plan) ?>;
</script>

<div class="page-header">
    <a href="<?= BASE_URL ?>/?page=shop" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <h1 class="page-title">Carrinho</h1>
</div>

<?php if (empty($items)): ?>
<div class="cart-empty fade-in">
    <div class="cart-empty-icon"><i class="fas fa-shopping-bag"></i></div>
    <h2>O seu carrinho está vazio</h2>
    <p>Explore as nossas fórmulas de Medicina Tradicional Chinesa.</p>
    <a href="<?= BASE_URL ?>/?page=shop" class="btn btn-dark" style="width:auto;display:inline-flex;padding:14px 32px;border-radius:28px;">Explorar Loja</a>
</div>
<?php else: ?>

<?php if ($user_plan === null): ?>
<!-- Shipping Progress Bar -->
<div class="shipping-progress-section">
    <?php if ($free_shipping): ?>
    <div class="shipping-msg free">
        <i class="fas fa-check-circle"></i> <span>Parabéns! Tem <strong>portes grátis</strong> nesta encomenda.</span>
    </div>
    <?php else: ?>
    <div class="shipping-msg">
        <i class="fas fa-truck"></i> <span>Faltam <strong><?= formatPrice($remaining) ?></strong> para portes grátis!</span>
    </div>
    <?php endif; ?>
    <div class="shipping-bar-track">
        <div class="shipping-bar-fill" style="width: <?= $progress_pct ?>%"></div>
    </div>
</div>
<?php else: ?>
<!-- Benefício Clube AcuSport -->
<div style="padding: 14px 18px; background: linear-gradient(135deg, rgba(200, 165, 115, 0.1), rgba(200, 165, 115, 0.02)); border: 1px solid rgba(200, 165, 115, 0.3); border-radius: 12px; display: flex; align-items: center; gap: 14px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(200, 165, 115, 0.05);">
    <div style="background: var(--gold); color: white; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1rem; box-shadow: 0 2px 8px rgba(200, 165, 115, 0.3);">
        <i class="fas fa-crown"></i>
    </div>
    <div style="line-height: 1.3;">
        <span style="display: block; font-family: var(--font-serif); font-weight: 600; color: var(--gold-dark); font-size: 1.05rem; margin-bottom: 2px;">Membro Clube AcuSport</span>
        <?php if ($free_formulas_used > 0 && $subtotal_after_discount == 0): ?>
        <span style="font-size: 0.85rem; color: var(--text-medium);">A encomendar apenas Fórmulas Grátis. É aplicada uma <strong>taxa de envio de 10€</strong>.</span>
        <?php else: ?>
        <span style="font-size: 0.85rem; color: var(--text-medium);">Tem <strong>portes de envio grátis</strong> garantidos nesta encomenda.</span>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Cart Items -->
<div class="cart-items">
    <?php foreach ($items as $item): ?>
    <div class="cart-item" data-item-id="<?= $item['id'] ?>" data-price="<?= $item['preco'] ?>">
        <a href="<?= BASE_URL ?>/?page=product&id=<?= $item['product_id'] ?>" class="cart-item-img">
            <?php $imgUrl = getProductImageUrl($item['imagem']); ?>
            <?php if (strpos($imgUrl, 'placeholder') !== false): ?>
                <div class="product-placeholder"><i class="fas fa-leaf"></i></div>
            <?php else: ?>
                <img src="<?= $imgUrl ?>" alt="<?= sanitize($item['nome']) ?>">
            <?php endif; ?>
        </a>
        <div class="cart-item-info">
            <div class="name"><?= sanitize($item['nome']) ?></div>
            <div class="price"><?= formatPrice($item['preco']) ?></div>
            <div class="cart-item-controls">
                <div class="qty-selector">
                    <button class="qty-btn" onclick="changeCartQty(<?= $item['id'] ?>, this.parentElement.querySelector('.qty-value'), -1)">−</button>
                    <input type="number" class="qty-value" value="<?= $item['quantidade'] ?>" min="1" max="99" readonly>
                    <button class="qty-btn" onclick="changeCartQty(<?= $item['id'] ?>, this.parentElement.querySelector('.qty-value'), 1)">+</button>
                </div>
                <button class="remove-btn" onclick="removeCartItem(<?= $item['id'] ?>)"><i class="fas fa-trash-alt"></i></button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Cart Summary -->
<div class="cart-summary slide-up">
    <h3 class="summary-title">Resumo da Encomenda</h3>
    <div class="summary-row">
        <span>Subtotal</span>
        <span id="cart-subtotal"><?= formatPrice($subtotal) ?></span>
    </div>
    <?php if ($free_formulas_used > 0): ?>
    <div class="summary-row" style="color:var(--success); align-items: flex-start;">
        <span style="line-height: 1.4; padding-right: 12px;"><i class="fas fa-gift"></i> <?= $free_formulas_used ?> Fórmula<?= $free_formulas_used > 1 ? 's' : '' ?> Grátis (<?= ucfirst($user_plan) ?>)</span>
        <span style="white-space: nowrap; flex-shrink: 0;">-<?= formatPrice($free_discount_amount) ?></span>
    </div>
    <?php endif; ?>
    <?php if ($plan_discount_pct > 0 || $pct_discount_amount > 0): ?>
    <div class="summary-row" style="color:var(--success); align-items: flex-start;">
        <span style="line-height: 1.4; padding-right: 12px;"><i class="fas fa-tag"></i> Desconto <?= ucfirst($user_plan) ?> (<?= $plan_discount_pct * 100 ?>%)</span>
        <span style="white-space: nowrap; flex-shrink: 0;">-<?= formatPrice($pct_discount_amount) ?></span>
    </div>
    <?php endif; ?>
    <div class="summary-row">
        <span>Envio</span>
        <?php if ($free_shipping): ?>
        <span class="shipping-free"><i class="fas fa-check-circle"></i> Grátis</span>
        <?php else: ?>
        <span class="shipping-paid"><?= formatPrice($shipping_cost) ?></span>
        <?php endif; ?>
    </div>
    <div class="summary-row total">
        <span>Total</span>
        <span id="cart-total"><?= formatPrice($total) ?></span>
    </div>
    <a href="<?= BASE_URL ?>/?page=checkout" class="btn btn-gold cart-checkout-btn" style="border-radius: 12px;">
        <i class="fas fa-lock" style="font-size: 0.8rem;"></i> Finalizar Compra <i class="fas fa-arrow-right" style="font-size: 0.8rem; margin-left: 4px;"></i>
    </a>
    <div class="cart-secure-note">
        <i class="fas fa-shield-alt"></i> Pagamento 100% seguro e encriptado
    </div>
</div>

<!-- Continue Shopping -->
<div class="cart-continue">
    <a href="<?= BASE_URL ?>/?page=shop"><i class="fas fa-arrow-left"></i> Continuar a Comprar</a>
</div>

<?php endif; ?>
