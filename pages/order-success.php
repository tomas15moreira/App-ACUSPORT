<?php
// Order Success
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : null;
?>
<div class="success-page fade-in">
    <div style="position: relative; margin-bottom: 12px;">
        <div class="success-icon" style="animation: successPop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);">
            <i class="fas fa-check"></i>
        </div>
        <div style="position: absolute; inset: -20px; pointer-events: none;" id="confetti-ring">
            <svg viewBox="0 0 120 120" style="width: 100%; height: 100%; animation: confettiSpin 10s linear infinite;">
                <circle cx="60" cy="60" r="50" fill="none" stroke="rgba(200,164,92,0.15)" stroke-width="1" stroke-dasharray="6 8"/>
            </svg>
        </div>
    </div>
    <h1>Encomenda Confirmada!</h1>
    <?php if ($order_id): ?>
    <p style="margin-bottom: 8px;">A sua encomenda <strong style="color: var(--gold-dark); font-family: 'SF Mono', monospace;">#<?= str_pad($order_id, 5, '0', STR_PAD_LEFT) ?></strong> foi recebida com sucesso.</p>
    <?php else: ?>
    <p style="margin-bottom: 8px;">A sua encomenda foi recebida com sucesso. Obrigado pela sua confiança!</p>
    <?php endif; ?>
    
    <div style="display: flex; align-items: center; gap: 8px; background: rgba(74,139,92,0.08); border: 1px solid rgba(74,139,92,0.15); padding: 10px 16px; border-radius: 10px; margin-bottom: 24px; max-width: 280px;">
        <i class="fas fa-envelope" style="color: var(--success); font-size: 0.8rem;"></i>
        <span style="font-size: 0.78rem; color: var(--text-medium);">Receberá um email com os detalhes e acompanhamento.</span>
    </div>

    <div style="display: flex; flex-direction: column; gap: 10px; width: 100%; max-width: 260px;">
        <a href="<?= BASE_URL ?>/?page=shop" class="btn btn-gold" style="border-radius: 14px; padding: 15px;">
            <i class="fas fa-shopping-bag"></i> Continuar a Comprar
        </a>
        <a href="<?= BASE_URL ?>/?page=home" style="display:flex;align-items:center;justify-content:center;gap:6px;color:var(--gold);font-weight:600;font-size:0.85rem;padding:8px;">
            <i class="fas fa-home" style="font-size:0.75rem;"></i> Voltar ao Início
        </a>
    </div>
</div>

<style>
@keyframes successPop {
    0% { transform: scale(0); opacity: 0; }
    60% { transform: scale(1.15); }
    100% { transform: scale(1); opacity: 1; }
}
@keyframes confettiSpin { to { transform: rotate(360deg); } }
</style>
