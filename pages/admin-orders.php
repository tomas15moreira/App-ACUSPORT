<?php
if (!isLoggedIn() || !isAdmin()) { header('Location: ' . BASE_URL . '/?page=login'); exit; }
$orders = getAllOrders();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    updateOrderStatus((int)$_POST['order_id'], $_POST['status']);
    header('Location: ' . BASE_URL . '/?page=admin-orders');
    exit;
}
?>

<div class="admin-header">
    <h1><i class="fas fa-shield-alt"></i> AcuSport</h1>
    <a href="<?= BASE_URL ?>/?page=home" class="admin-back-link"><i class="fas fa-store"></i> Ver Loja</a>
</div>

<nav class="admin-nav">
    <a href="<?= BASE_URL ?>/?page=admin"><i class="fas fa-chart-pie"></i> Dashboard</a>
    <a href="<?= BASE_URL ?>/?page=admin-orders" class="active"><i class="fas fa-box"></i> Encomendas</a>
    <a href="<?= BASE_URL ?>/?page=admin-products"><i class="fas fa-leaf"></i> Produtos</a>
    <a href="<?= BASE_URL ?>/?page=admin-users"><i class="fas fa-users"></i> Clientes</a>
</nav>

<div class="admin-page">
    <div class="admin-page-title">
        <span>Encomendas (<?= count($orders) ?>)</span>
    </div>

    <?php if (empty($orders)): ?>
        <div class="admin-empty">
            <i class="fas fa-box-open"></i>
            <p>Sem encomendas registadas.</p>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $o): ?>
        <div class="order-admin-card">
            <div class="order-admin-top">
                <div>
                    <div class="order-admin-id">#<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></div>
                    <div class="order-admin-date"><i class="far fa-clock" style="margin-right: 3px;"></i><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></div>
                </div>
                <span class="status-badge <?= $o['estado'] ?>"><?= ucfirst($o['estado']) ?></span>
            </div>
            <div class="order-admin-client">
                <strong><?= sanitize($o['nome_envio']) ?></strong><br>
                <?= sanitize($o['email_envio']) ?> · <?= $o['total_items'] ?> item(s)<br>
                <span style="font-size: 0.72rem; color: var(--text-light);"><?= sanitize($o['morada_envio']) ?>, <?= sanitize($o['codigo_postal_envio']) ?> <?= sanitize($o['cidade_envio']) ?></span>
                <?php if ($o['metodo_pagamento']): ?>
                <br><span style="font-size: 0.72rem; color: var(--sage);">💳 <?= ucfirst($o['metodo_pagamento']) ?></span>
                <?php endif; ?>
            </div>
            <?php $items = getOrderItemsAdmin($o['id']); ?>
            <?php if (!empty($items)): ?>
            <div class="order-items-list">
                <?php foreach ($items as $item): ?>
                <div class="order-item-row">
                    <div class="order-item-thumb">
                        <?php $iUrl = getProductImageUrl($item['imagem']); ?>
                        <?php if (strpos($iUrl, 'placeholder') !== false): ?>
                            <i class="fas fa-leaf" style="color: var(--sage); font-size: 0.7rem;"></i>
                        <?php else: ?>
                            <img src="<?= $iUrl ?>" alt="">
                        <?php endif; ?>
                    </div>
                    <span class="order-item-name"><?= sanitize($item['nome']) ?></span>
                    <span class="order-item-qty">×<?= $item['quantidade'] ?></span>
                    <span class="order-item-price"><?= formatPrice($item['preco_unitario']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="order-admin-bottom">
                <span class="order-admin-total"><?= formatPrice($o['total']) ?></span>
                <form method="POST" style="display: flex; gap: 6px; align-items: center;">
                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                    <select name="status" class="status-select" onchange="this.form.submit()">
                        <?php foreach (['pendente', 'processando', 'enviado', 'entregue', 'cancelado'] as $s): ?>
                        <option value="<?= $s ?>" <?= $o['estado'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
