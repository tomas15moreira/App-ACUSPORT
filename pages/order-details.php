<?php
// Detalhes da Encomenda
if (!isLoggedIn()) { header('Location: ' . BASE_URL . '/?page=login'); exit; }

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$order_id) { header('Location: ' . BASE_URL . '/?page=profile'); exit; }

$order = getOrderDetails($order_id);

// Validar se a encomenda pertence ao utilizador logado
if (!$order || $order['user_id'] != $_SESSION['user_id']) {
    header('Location: ' . BASE_URL . '/?page=profile');
    exit;
}
?>

<div class="client-area">
    <div class="order-details-hero slide-up">
        <a href="<?= BASE_URL ?>/?page=profile" class="btn-back-link"><i class="fas fa-arrow-left"></i> Voltar ao Perfil</a>
        <h1>Detalhes da Encomenda</h1>
        <p>Acompanhe o estado da sua encomenda #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>.</p>
    </div>

    <div class="client-tab-content active" style="padding-top: 0;">
        <div class="profile-card-luxe fade-in">
            <div class="card-luxe-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3><i class="fas fa-info-circle"></i> Informação Geral</h3>
                <span class="order-badge <?= $order['estado'] ?>"><?= ucfirst($order['estado']) ?></span>
            </div>
            <div class="card-luxe-body">
                <div class="info-grid">
                    <div class="info-block">
                        <span class="i-label">Data do Pedido</span>
                        <span class="i-value"><?= date('d/m/Y \à\s H:i', strtotime($order['created_at'])) ?></span>
                    </div>
                    <div class="info-block">
                        <span class="i-label">Total Pago</span>
                        <span class="i-value gold"><?= formatPrice($order['total']) ?></span>
                    </div>
                    <div class="info-block full-width">
                        <span class="i-label">Método de Pagamento</span>
                        <span class="i-value"><?= ucfirst(str_replace('_', ' ', $order['metodo_pagamento'])) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-card-luxe fade-in" style="animation-delay: 0.1s">
            <div class="card-luxe-header">
                <h3><i class="fas fa-map-marker-alt"></i> Dados de Envio</h3>
            </div>
            <div class="card-luxe-body">
                <div class="info-grid">
                    <div class="info-block full-width">
                        <span class="i-label">Destinatário</span>
                        <span class="i-value"><?= sanitize($order['nome_envio']) ?></span>
                    </div>
                    <div class="info-block full-width">
                        <span class="i-label">Morada</span>
                        <span class="i-value"><?= sanitize($order['morada_envio']) ?>, <?= sanitize($order['codigo_postal_envio']) ?> <?= sanitize($order['cidade_envio']) ?></span>
                    </div>
                    <div class="info-block">
                        <span class="i-label">Contacto</span>
                        <span class="i-value"><?= sanitize($order['telefone_envio'] ?? 'Não fornecido') ?></span>
                    </div>
                    <div class="info-block">
                        <span class="i-label">E-mail</span>
                        <span class="i-value"><?= sanitize($order['email_envio']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-card-luxe fade-in" style="animation-delay: 0.2s">
            <div class="card-luxe-header">
                <h3><i class="fas fa-box-open"></i> Artigos Adquiridos</h3>
            </div>
            <div class="card-luxe-body" style="padding: 0;">
                <div class="order-items-list">
                    <?php foreach ($order['items'] as $item): ?>
                    <div class="order-detail-item">
                        <img src="<?= getProductImageUrl($item['imagem']) ?>" alt="<?= sanitize($item['nome']) ?>" class="order-item-img">
                        <div class="order-item-info">
                            <h4><?= sanitize($item['nome']) ?></h4>
                            <span class="order-item-qty">Qtd: <?= $item['quantidade'] ?></span>
                        </div>
                        <div class="order-item-price"><?= formatPrice($item['preco_unitario'] * $item['quantidade']) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-summary-totals">
                    <div class="summary-line">
                        <span>Subtotal</span>
                        <span><?= formatPrice($order['total']) ?></span>
                    </div>
                    <div class="summary-line">
                        <span>Portes de Envio</span>
                        <span>0,00 €</span>
                    </div>
                    <div class="summary-line total">
                        <span>Total</span>
                        <span><?= formatPrice($order['total']) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


