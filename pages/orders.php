<?php
// Orders Page — redireciona para o perfil (tab de encomendas)
if (!isLoggedIn()) { header('Location: ' . BASE_URL . '/?page=login'); exit; }
$orders = [];
try { $orders = getUserOrders($_SESSION['user_id']); } catch (Exception $e) {}
?>
<div class="page-header">
    <a href="<?= BASE_URL ?>/?page=profile" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <h1 class="page-title">As Minhas Encomendas</h1>
</div>

<?php if (empty($orders)): ?>
<div class="cart-empty fade-in">
    <i class="fas fa-box-open"></i>
    <h2>Sem encomendas</h2>
    <p>Ainda não realizou nenhuma encomenda.</p>
    <a href="<?= BASE_URL ?>/?page=shop" class="btn btn-gold" style="width:auto;display:inline-flex">Explorar Loja</a>
</div>
<?php else: ?>
<div style="padding: 12px 0;">
    <?php foreach ($orders as $i => $order): ?>
    <div class="order-card fade-in" style="animation-delay: <?= $i * 0.05 ?>s">
        <div class="order-top">
            <span class="order-id">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></span>
            <span class="order-status <?= $order['estado'] ?>"><?= ucfirst($order['estado']) ?></span>
        </div>
        <div class="order-info">
            <i class="fas fa-calendar-alt" style="margin-right:4px;font-size:0.7rem;color:var(--text-light)"></i>
            <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?> · <?= $order['total_items'] ?> item(s)
        </div>
        <div class="order-total"><?= formatPrice($order['total']) ?></div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
