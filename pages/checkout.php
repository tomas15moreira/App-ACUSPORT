<?php
// Checkout
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/?page=login&redirect=checkout');
    exit;
}

$items = [];
$subtotal = 0;
$user = null;
try {
    $items = getCartItems();
    $subtotal = getCartTotal();
    $totals = calculateCartTotals();
    if (isLoggedIn()) $user = getCurrentUser();
} catch (Exception $e) {}
if (empty($items)) {
    echo '<div class="cart-empty fade-in"><i class="fas fa-shopping-bag"></i><h2>Carrinho vazio</h2><p>Adicione produtos antes de finalizar.</p><a href="'.BASE_URL.'/?page=shop" class="btn btn-gold" style="width:auto;display:inline-flex">Ver Loja</a></div>';
    return;
}
?>

<div class="page-header">
    <a href="<?= BASE_URL ?>/?page=cart" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <h1 class="page-title">Checkout</h1>
</div>

<form class="checkout-form" onsubmit="handleCheckout(event)">
    <div class="checkout-section">
        <h2><i class="fas fa-truck" style="color:var(--gold);margin-right:8px"></i>Dados de Envio</h2>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-user" style="color: var(--gold); margin-right: 6px; font-size: 0.75rem;"></i>Nome completo *</label>
            <input type="text" name="nome" class="form-input" required value="<?= sanitize($user['nome'] ?? '') ?>" placeholder="O seu nome">
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-envelope" style="color: var(--gold); margin-right: 6px; font-size: 0.75rem;"></i>Email *</label>
            <input type="email" name="email" class="form-input" required value="<?= sanitize($user['email'] ?? '') ?>" placeholder="email@exemplo.com">
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-phone" style="color: var(--gold); margin-right: 6px; font-size: 0.75rem;"></i>Telefone</label>
            <input type="tel" name="telefone" class="form-input" value="<?= sanitize($user['telefone'] ?? '') ?>" placeholder="912 345 678">
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fas fa-map-marker-alt" style="color: var(--gold); margin-right: 6px; font-size: 0.75rem;"></i>Morada *</label>
            <input type="text" name="morada" class="form-input" required value="<?= sanitize($user['morada'] ?? '') ?>" placeholder="Rua, número, andar">
        </div>
        <div style="display:flex;gap:12px">
            <div class="form-group" style="flex:1">
                <label class="form-label"><i class="fas fa-map-pin" style="color: var(--gold); margin-right: 6px; font-size: 0.75rem;"></i>Cód. Postal *</label>
                <input type="text" name="codigo_postal" class="form-input" required value="<?= sanitize($user['codigo_postal'] ?? '') ?>" placeholder="1234-567">
            </div>
            <div class="form-group" style="flex:1.5">
                <label class="form-label"><i class="fas fa-city" style="color: var(--gold); margin-right: 6px; font-size: 0.75rem;"></i>Cidade *</label>
                <input type="text" name="cidade" class="form-input" required value="<?= sanitize($user['cidade'] ?? '') ?>" placeholder="Lisboa">
            </div>
        </div>
    </div>

    <div class="checkout-section">
        <h2><i class="fas fa-credit-card" style="color:var(--gold);margin-right:8px"></i>Pagamento</h2>
        <div class="payment-options">
            <div class="payment-option selected" onclick="selectPayment(this)">
                <input type="radio" name="pagamento" value="cartao" checked>
                <div style="display: flex; gap: 6px; align-items: center; width: 90px; justify-content: center;">
                    <img src="https://imagedelivery.net/5MYSbk45M80qAwecrlKzdQ/a0dbde26-08e5-456f-8c9c-77d607cdfc00/public" alt="Visa Mastercard" style="height: 45px; max-width: 90px; object-fit: contain;">
                </div>
                <label>Cartão de Crédito/Débito</label>
            </div>
            <div class="payment-option" onclick="selectPayment(this)">
                <input type="radio" name="pagamento" value="mbway">
                <div style="width: 90px; display: flex; align-items: center; justify-content: center;">
                    <img src="https://www.escolamagica.pt/uploads/payments/mbway.png" alt="MB WAY" style="height: 35px; object-fit: contain;">
                </div>
                <label>MB WAY</label>
            </div>
            <div class="payment-option" onclick="selectPayment(this)">
                <input type="radio" name="pagamento" value="multibanco">
                <div style="width: 90px; display: flex; align-items: center; justify-content: center;">
                    <img src="https://portugalexpresso.pt/wp-content/uploads/2022/12/kisspng-vector-graphics-multibanco-computer-icons-logo-por-pegasus-mtodos-de-pagamento-5b6d61ae94ee21.76971059153389508661-600x600.png" alt="Multibanco" style="height: 35px; object-fit: contain;">
                </div>
                <label>Multibanco</label>
            </div>
        </div>

        <!-- Dados do Cartão -->
        <div class="payment-details" id="payment-details-cartao" style="margin-top: 20px;">
            <div class="form-group">
                <label class="form-label">Número do Cartão *</label>
                <div style="position: relative;">
                    <input type="text" name="card_number" class="form-input" placeholder="1234 5678 9012 3456" maxlength="19" oninput="formatCardNumber(this)" style="padding-left: 16px; padding-right: 44px; letter-spacing: 1.5px; font-family: 'Courier New', monospace; font-size: 1rem;">
                    <i class="fas fa-credit-card" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--text-light); font-size: 1.1rem;"></i>
                </div>
            </div>
            <div style="display: flex; gap: 12px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Validade *</label>
                    <input type="text" name="card_expiry" class="form-input" placeholder="MM/AA" maxlength="5" oninput="formatExpiry(this)" style="letter-spacing: 2px; font-family: 'Courier New', monospace;">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">CVV *</label>
                    <div style="position: relative;">
                        <input type="password" name="card_cvv" class="form-input" placeholder="•••" maxlength="4" style="letter-spacing: 4px; font-family: 'Courier New', monospace;">
                        <i class="fas fa-lock" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--text-light); font-size: 0.85rem;"></i>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Nome no Cartão *</label>
                <input type="text" name="card_name" class="form-input" placeholder="NOME APELIDO" style="text-transform: uppercase; letter-spacing: 1px;">
            </div>
        </div>

        <!-- Dados MB WAY -->
        <div class="payment-details" id="payment-details-mbway" style="margin-top: 20px; display: none;">
            <div style="background: linear-gradient(135deg, #f8f9fa, #fff); border-radius: 12px; padding: 20px; border: 1px solid rgba(0,0,0,0.05);">
                <p style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-mobile-alt" style="color: var(--gold);"></i>
                    <span>Introduza o número de telemóvel associado ao MB WAY.</span>
                </p>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Telemóvel *</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <span style="background: var(--white); border: 1.5px solid var(--border); border-radius: var(--radius-sm); padding: 14px 12px; font-size: 0.9rem; color: var(--text-dark); font-weight: 600; white-space: nowrap;">+351</span>
                        <input type="tel" name="mbway_phone" class="form-input" placeholder="912 345 678" maxlength="11" oninput="formatPhone(this)" style="letter-spacing: 1px; font-size: 1rem;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Dados Multibanco (informação antes de confirmar) -->
        <div class="payment-details" id="payment-details-multibanco" style="margin-top: 20px; display: none;">
            <div style="background: linear-gradient(135deg, #f8f9fa, #fff); border-radius: 12px; padding: 20px; border: 1px solid rgba(0,0,0,0.05);">
                <p style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 0; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-university" style="color: var(--gold);"></i>
                    <span>Ao confirmar a encomenda, será gerada uma referência Multibanco válida por <strong>10 minutos</strong> para efetuar o pagamento.</span>
                </p>
            </div>
        </div>
    </div>

    <div class="checkout-section">
        <h2><i class="fas fa-clipboard-list" style="color:var(--gold);margin-right:8px"></i>Resumo</h2>
        <?php
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
        $free_shipping = $totals['free_shipping'] ?? false;
        $total = $totals['total'] ?? 0;
        ?>
        <div class="order-review">
            <?php foreach ($items as $item): ?>
            <div class="review-item">
                <span><?= sanitize($item['nome']) ?> × <?= $item['quantidade'] ?></span>
                <span><?= formatPrice($item['preco'] * $item['quantidade']) ?></span>
            </div>
            <?php endforeach; ?>
            <div class="review-item">
                <span>Envio</span>
                <?php if ($free_shipping): ?>
                <span style="color:var(--success)">Grátis</span>
                <?php else: ?>
                <span><?= formatPrice($shipping_cost) ?></span>
                <?php endif; ?>
            </div>
            <?php if ($free_formulas_used > 0): ?>
            <div class="review-item" style="color:var(--success); align-items: flex-start;">
                <span style="line-height: 1.4; padding-right: 12px;"><i class="fas fa-gift"></i> <?= $free_formulas_used ?> Fórmula<?= $free_formulas_used > 1 ? 's' : '' ?> Grátis (<?= ucfirst($user_plan) ?>)</span>
                <span style="white-space: nowrap; flex-shrink: 0;">-<?= formatPrice($free_discount_amount) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($plan_discount_pct > 0 || $pct_discount_amount > 0): ?>
            <div class="review-item" style="color:var(--success); align-items: flex-start;">
                <span style="line-height: 1.4; padding-right: 12px;"><i class="fas fa-tag"></i> Desconto <?= ucfirst($user_plan) ?> (<?= $plan_discount_pct * 100 ?>%)</span>
                <span style="white-space: nowrap; flex-shrink: 0;">-<?= formatPrice($pct_discount_amount) ?></span>
            </div>
            <?php endif; ?>
            <div class="review-item" style="font-weight:700; font-size:1.1rem; border-top:1px solid #f1f3f5; padding-top:12px; margin-top:4px;">
                <span>Total</span><span><?= formatPrice($total) ?></span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Notas (opcional)</label>
        <textarea name="notas" class="form-input" rows="3" placeholder="Instruções especiais..."></textarea>
    </div>

    <button type="submit" class="btn btn-gold btn-block" id="btn-confirmar-encomenda">
        <i class="fas fa-lock"></i> Confirmar Encomenda
    </button>
</form>


<script>
// Checkout total for Multibanco
var checkoutTotal = <?= number_format($total, 2, '.', '') ?>;
</script>
