<?php
if (!isLoggedIn() || !isAdmin()) { header('Location: ' . BASE_URL . '/?page=login'); exit; }
$stats = getAdminStats();
?>

<div class="admin-header">
    <h1><i class="fas fa-shield-alt"></i> AcuSport</h1>
    <a href="<?= BASE_URL ?>/?page=home" class="admin-back-link"><i class="fas fa-store"></i> Ver Loja</a>
</div>

<nav class="admin-nav">
    <a href="<?= BASE_URL ?>/?page=admin" class="active"><i class="fas fa-chart-pie"></i> Dashboard</a>
    <a href="<?= BASE_URL ?>/?page=admin-orders"><i class="fas fa-box"></i> Encomendas</a>
    <a href="<?= BASE_URL ?>/?page=admin-products"><i class="fas fa-leaf"></i> Produtos</a>
    <a href="<?= BASE_URL ?>/?page=admin-users"><i class="fas fa-users"></i> Clientes</a>
</nav>

<div class="admin-page">
    <div class="admin-stats">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(200,164,92,0.1); color: var(--gold);"><i class="fas fa-euro-sign"></i></div>
            <div class="stat-value"><?= number_format($stats['total_revenue'], 0, ',', '.') ?>€</div>
            <div class="stat-label">Receita Total</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(74,90,74,0.1); color: var(--sage-dark);"><i class="fas fa-shopping-bag"></i></div>
            <div class="stat-value"><?= $stats['total_orders'] ?></div>
            <div class="stat-label">Encomendas</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(52,152,219,0.1); color: #3498db;"><i class="fas fa-users"></i></div>
            <div class="stat-value"><?= $stats['total_users'] ?></div>
            <div class="stat-label">Clientes</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(231,76,60,0.08); color: #e74c3c;"><i class="fas fa-clock"></i></div>
            <div class="stat-value"><?= $stats['pending_orders'] ?></div>
            <div class="stat-label">Pendentes</div>
        </div>
    </div>

    <div class="admin-section">
        <div class="admin-section-title"><i class="fas fa-history"></i> Encomendas Recentes</div>
        <?php if (empty($stats['recent_orders'])): ?>
            <div class="admin-empty">
                <i class="fas fa-inbox"></i>
                <p>Ainda não existem encomendas.</p>
            </div>
        <?php else: ?>
            <?php foreach ($stats['recent_orders'] as $o): ?>
            <a href="<?= BASE_URL ?>/?page=admin-orders" class="recent-order">
                <div class="ro-left">
                    <span class="ro-id">#<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></span>
                    <span class="ro-name"><?= sanitize($o['user_nome'] ?? $o['nome_envio']) ?></span>
                </div>
                <div class="ro-right">
                    <div class="ro-total"><?= formatPrice($o['total']) ?></div>
                    <span class="status-badge <?= $o['estado'] ?>"><?= ucfirst($o['estado']) ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="admin-section">
        <div class="admin-section-title"><i class="fas fa-crown"></i> Clube VIP &amp; Catálogo</div>
        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
            <div style="flex:1; background: linear-gradient(135deg, var(--gold), var(--gold-dark)); border-radius: 14px; padding: 16px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(200, 165, 115, 0.3);">
                <div style="font-size: 1.4rem; font-weight: 800; font-family: var(--font-serif); display: flex; align-items: center; justify-content: center; gap: 6px;">
                    <i class="fas fa-gem" style="font-size: 0.9rem;"></i> <?= $stats['vip_users'] ?>
                </div>
                <div style="font-size: 0.65rem; color: rgba(255,255,255,0.9); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px; font-weight: 600;">Membros VIP</div>
            </div>
            <div style="flex:1; background: var(--white); border-radius: 14px; padding: 16px; border: 1px solid rgba(0,0,0,0.04); text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                <div style="font-size: 1.4rem; font-weight: 800; color: var(--sage-dark); font-family: var(--font-serif);"><?= $stats['total_products'] ?></div>
                <div style="font-size: 0.65rem; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px; font-weight: 600;">Produtos Ativos</div>
            </div>
        </div>
        <a href="<?= BASE_URL ?>/?page=admin-products" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; box-sizing: border-box; background: var(--white); border-radius: 14px; padding: 14px; text-decoration: none; border: 1px solid rgba(0,0,0,0.04); color: var(--text-dark); font-size: 0.8rem; font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.02); transition: all 0.2s;" onmousedown="this.style.transform='scale(0.98)'" onmouseup="this.style.transform='scale(1)'">
            <i class="fas fa-box-open" style="color: var(--sage);"></i> Gerir Catálogo
        </a>
    </div>
</div>
