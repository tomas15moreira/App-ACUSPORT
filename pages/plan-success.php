<?php
$plan_id = isset($_GET['plan']) ? $_GET['plan'] : 'essencia';

$plans = [
    'essencia' => 'Plano Essência',
    'vitalidade' => 'Plano Vitalidade',
    'mestre' => 'Plano Mestre'
];

$plan_name = isset($plans[$plan_id]) ? $plans[$plan_id] : 'Plano Essência';
?>
<div class="success-page animate-on-scroll">
    <div class="success-icon" style="background: var(--gold);">
        <i class="fas fa-crown" style="color: var(--white); font-size: 2.2rem;"></i>
    </div>
    <h1 style="color: var(--gold); font-family: var(--font-serif); font-size: 1.8rem; margin-bottom: 8px;">Bem-vindo ao Clube!</h1>
    <p style="color: var(--text-dark); font-weight: 500; margin-bottom: 16px;">A sua subscrição do <strong><?= $plan_name ?></strong> foi ativada com sucesso.</p>
    <p style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 32px; max-width: 280px; line-height: 1.5;">
        As suas vantagens exclusivas já estão disponíveis na sua conta. Iremos preparar a sua primeira remessa em breve.
    </p>
    
    <a href="<?= BASE_URL ?>/?page=profile" class="btn btn-gold btn-block" style="margin-bottom: 12px;">Ir para o meu Perfil</a>
    <a href="<?= BASE_URL ?>/?page=home" class="btn btn-outline btn-block">Voltar ao Início</a>
</div>
