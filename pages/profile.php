<?php
// Área de Cliente - Profile Page Redesenhada
if (!isLoggedIn()) { header('Location: ' . BASE_URL . '/?page=login'); exit; }

$user = getCurrentUser();
$orders = [];
try { $orders = getUserOrders($_SESSION['user_id']); } catch (Exception $e) {}
$recent_orders = array_slice($orders, 0, 3);
$member_since = date('Y');
?>

<div class="client-area">
    <!-- Premium Header -->
    <div class="client-header-premium slide-up">
        <?php if (isAdmin()): ?>
        <a href="<?= BASE_URL ?>/?page=admin" style="position: absolute; top: 16px; left: 16px; z-index: 10; display: flex; align-items: center; gap: 6px; color: var(--gold); background: rgba(200,165,115,0.15); padding: 8px 14px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; backdrop-filter: blur(5px); text-decoration: none; border: 1px solid rgba(200,165,115,0.3); transition: background 0.3s ease;"><i class="fas fa-shield-alt"></i> Admin</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/?page=profile-edit" class="btn-top-edit"><i class="fas fa-pen"></i> Editar</a>
        <div class="client-header-bg"></div>
        <div class="client-header-content">
            <div class="client-avatar-luxe">
                <?= strtoupper(substr($user['nome'], 0, 1)) ?>
            </div>
            <div class="client-welcome-text">
                <h2><?= sanitize($user['nome']) ?></h2>
                <span class="member-badge"><i class="fas fa-crown"></i> Membro desde <?= $member_since ?></span>
            </div>
        </div>
    </div>

    <!-- Client Navigation Tabs -->
    <div class="client-tabs-wrapper">
        <div class="client-tabs">
            <button class="client-tab active" onclick="switchClientTab('overview', this)">
                <i class="fas fa-layer-group"></i> Visão Geral
            </button>
            <button class="client-tab" onclick="switchClientTab('orders', this)">
                <i class="fas fa-box-open"></i> Encomendas
            </button>
            <button class="client-tab" onclick="switchClientTab('plans', this)">
                <i class="fas fa-seedling"></i> O Meu Plano
            </button>
        </div>
    </div>

    <!-- Tab: Visão Geral -->
    <div class="client-tab-content active" id="tab-overview">
        <!-- Quick Actions -->
        <div class="quick-actions-premium fade-in">
            <a href="<?= BASE_URL ?>/?page=shop" class="qa-btn">
                <div class="qa-icon-wrap"><i class="fas fa-shopping-bag"></i></div>
                <span>Loja</span>
            </a>
            <a href="<?= BASE_URL ?>/?page=cart" class="qa-btn">
                <div class="qa-icon-wrap"><i class="fas fa-shopping-cart"></i></div>
                <span>Carrinho</span>
            </a>
            <a href="<?= BASE_URL ?>/?page=plans" class="qa-btn">
                <div class="qa-icon-wrap"><i class="fas fa-leaf"></i></div>
                <span>Planos</span>
            </a>
            <a href="<?= BASE_URL ?>/?page=contacts" class="qa-btn">
                <div class="qa-icon-wrap"><i class="fas fa-headset"></i></div>
                <span>Suporte</span>
            </a>
        </div>

        <div class="profile-card-luxe fade-in" style="animation-delay: 0.1s">
            <div class="card-luxe-header">
                <h3><i class="fas fa-id-card"></i> Resumo da Conta</h3>
            </div>
            <div class="card-luxe-body">
                <div class="info-grid">
                    <div class="info-block">
                        <span class="i-label">Nome Completo</span>
                        <span class="i-value"><?= sanitize($user['nome']) ?></span>
                    </div>
                    <div class="info-block">
                        <span class="i-label">E-mail Associado</span>
                        <span class="i-value"><?= sanitize($user['email']) ?></span>
                    </div>
                    <div class="info-block full-width">
                        <span class="i-label">Estado da Subscrição</span>
                        <?php
                        $user_plan = isset($_SESSION['user_plan']) ? $_SESSION['user_plan'] : null;
                        if ($user_plan) {
                            echo '<div class="status-badge active"><i class="fas fa-check-circle"></i> Plano ' . ucfirst($user_plan) . ' Ativo</div>';
                            echo '<button class="btn-cancel-inline" onclick="openCancelModal()"><i class="fas fa-times"></i> Cancelar subscrição</button>';
                        } else {
                            echo '<div class="status-badge inactive"><i class="fas fa-times-circle"></i> Sem Subscrição Ativa</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-card-luxe fade-in" style="animation-delay: 0.2s">
            <div class="card-luxe-header">
                <h3><i class="fas fa-history"></i> Atividade Recente</h3>
                <?php if (count($orders) > 3): ?>
                <a href="javascript:void(0)" onclick="switchClientTab('orders', document.querySelectorAll('.client-tab')[1])" class="header-link">Ver todas <i class="fas fa-chevron-right"></i></a>
                <?php endif; ?>
            </div>
            <div class="card-luxe-body">
                <?php if (empty($recent_orders)): ?>
                <div class="empty-state-luxe">
                    <div class="empty-icon-luxe"><i class="fas fa-box-open"></i></div>
                    <h4>Sem Atividade Recente</h4>
                    <p>A sua jornada começa aqui. Descubra as fórmulas que temos para si.</p>
                    <a href="<?= BASE_URL ?>/?page=shop" class="btn btn-dark btn-sm" style="display:inline-block; width:auto;">Explorar Loja</a>
                </div>
                <?php else: ?>
                <div class="activity-timeline-v2">
                    <?php foreach ($recent_orders as $i => $order): 
                        $status_icons = [
                            'pendente' => 'fa-clock',
                            'processando' => 'fa-cog fa-spin',
                            'pago' => 'fa-check-circle',
                            'enviado' => 'fa-truck-fast',
                            'entregue' => 'fa-box-open',
                            'cancelado' => 'fa-times-circle'
                        ];
                        // Failsafe for icons that might not exist in older FontAwesome versions
                        $status_icon = $status_icons[$order['estado']] ?? 'fa-box';
                        // Fallback truck-fast to truck if FA is v5
                        if ($status_icon === 'fa-truck-fast') $status_icon = 'fa-truck';
                    ?>
                    <a href="<?= BASE_URL ?>/?page=order-details&id=<?= $order['id'] ?>" class="timeline-card" style="animation-delay: <?= $i * 0.08 ?>s">
                        <div class="timeline-card-left">
                            <div class="timeline-card-icon <?= $order['estado'] ?>">
                                <i class="fas <?= $status_icon ?>"></i>
                            </div>
                            <div class="timeline-card-info">
                                <span class="timeline-card-title">Encomenda #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></span>
                                <span class="timeline-card-meta">
                                    <span class="timeline-status-pill <?= $order['estado'] ?>"><?= ucfirst($order['estado']) ?></span>
                                    <span class="timeline-card-date"><i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($order['created_at'])) ?></span>
                                </span>
                            </div>
                        </div>
                        <div class="timeline-card-right">
                            <span class="timeline-card-amount"><?= formatPrice($order['total']) ?></span>
                            <span class="timeline-card-arrow"><i class="fas fa-chevron-right"></i></span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="logout-wrapper fade-in" style="animation-delay: 0.3s; margin-top: 24px;">
            <button class="btn-logout-premium" onclick="logout()">
                <i class="fas fa-sign-out-alt"></i> Terminar Sessão de Forma Segura
            </button>
        </div>
    </div>

    <!-- Tab: Encomendas -->
    <div class="client-tab-content" id="tab-orders">
        <div class="profile-card-luxe fade-in">
            <div class="card-luxe-header">
                <h3><i class="fas fa-boxes"></i> Histórico de Compras</h3>
            </div>
            <div class="card-luxe-body">
                <?php if (empty($orders)): ?>
                <div class="empty-state-luxe">
                    <div class="empty-icon-luxe"><i class="fas fa-shopping-basket"></i></div>
                    <h4>Histórico Vazio</h4>
                    <p>Ainda não realizou nenhuma compra. As fórmulas perfeitas para o seu corpo estão à sua espera.</p>
                    <a href="<?= BASE_URL ?>/?page=shop" class="btn btn-gold btn-sm" style="display:inline-block; width:auto;">Descobrir Fórmulas</a>
                </div>
                <?php else: ?>
                <div class="activity-timeline-v2">
                    <?php foreach ($orders as $i => $order): 
                        $status_icons = [
                            'pendente' => 'fa-clock',
                            'processando' => 'fa-cog fa-spin',
                            'pago' => 'fa-check-circle',
                            'enviado' => 'fa-truck-fast',
                            'entregue' => 'fa-box-open',
                            'cancelado' => 'fa-times-circle'
                        ];
                        // Failsafe for icons that might not exist in older FontAwesome versions
                        $status_icon = $status_icons[$order['estado']] ?? 'fa-box';
                        if ($status_icon === 'fa-truck-fast') $status_icon = 'fa-truck';
                    ?>
                    <a href="<?= BASE_URL ?>/?page=order-details&id=<?= $order['id'] ?>" class="timeline-card" style="animation-delay: <?= ($i % 5) * 0.08 ?>s">
                        <div class="timeline-card-left">
                            <div class="timeline-card-icon <?= $order['estado'] ?>">
                                <i class="fas <?= $status_icon ?>"></i>
                            </div>
                            <div class="timeline-card-info">
                                <span class="timeline-card-title">Encomenda #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></span>
                                <span class="timeline-card-meta">
                                    <span class="timeline-status-pill <?= $order['estado'] ?>"><?= ucfirst($order['estado']) ?></span>
                                    <span class="timeline-card-date"><i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($order['created_at'])) ?></span>
                                </span>
                            </div>
                        </div>
                        <div class="timeline-card-right">
                            <span class="timeline-card-amount"><?= formatPrice($order['total']) ?></span>
                            <span class="timeline-card-arrow"><i class="fas fa-chevron-right"></i></span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tab: Gestão de Planos -->
    <div class="client-tab-content" id="tab-plans">
        <div class="plan-hero-card fade-in">
            <div class="plan-hero-bg"></div>
            <div class="plan-hero-content">
                <div class="plan-hero-icon"><i class="fas fa-crown"></i></div>
                <h2>Clube AcuSport</h2>
                <?php if ($user_plan): ?>
                    <p>Tem atualmente o <strong>Plano <?= ucfirst($user_plan) ?></strong> ativo. Recebe as nossas fórmulas em casa com toda a comodidade, de forma automática.</p>
                    <button class="btn-cancel-plan" onclick="openCancelModal()">
                        <i class="fas fa-times-circle"></i> Cancelar Subscrição
                    </button>
                <?php else: ?>
                    <p>Atualmente não possui nenhum plano de subscrição ativo. Otimize a sua rotina recebendo as nossas fórmulas mensalmente com portes gratuitos e descontos exclusivos.</p>
                    <a href="<?= BASE_URL ?>/?page=plans" class="btn btn-gold" style="margin-top:16px; display:inline-block; width:auto;">Ver Planos Disponíveis</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($user_plan): 
            $available_formulas = getFreeFormulasAvailable();
            $limits = getPlanLimits();
            $total_limit = $limits[strtolower($user_plan)] ?? 0;
            $used_formulas = max(0, $total_limit - $available_formulas);
            
            // Array to map english month names to portuguese
            $meses = ['January'=>'Janeiro', 'February'=>'Fevereiro', 'March'=>'Março', 'April'=>'Abril', 'May'=>'Maio', 'June'=>'Junho', 'July'=>'Julho', 'August'=>'Agosto', 'September'=>'Setembro', 'October'=>'Outubro', 'November'=>'Novembro', 'December'=>'Dezembro'];
            $month_name = $meses[date('F')] ?? date('F');
        ?>
        <div class="profile-card-luxe fade-in" style="margin-bottom: 24px;">
            <div class="card-luxe-header">
                <h3><i class="fas fa-gift"></i> As Suas Fórmulas de <?= $month_name ?></h3>
            </div>
            <div class="card-luxe-body">
                <div style="padding: 20px; background: linear-gradient(135deg, rgba(78, 203, 113, 0.08), rgba(78, 203, 113, 0.02)); border: 1px solid rgba(78, 203, 113, 0.2); border-radius: 14px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <h4 style="margin: 0 0 6px; font-size: 1.15rem; color: var(--success); display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-check-circle"></i> Fórmulas Disponíveis
                            </h4>
                            <p style="margin: 0; font-size: 0.85rem; color: var(--text-medium); line-height: 1.5;">
                                Tem direito a encomendar estas fórmulas sem custos adicionais este mês. Aproveite!
                            </p>
                        </div>
                        <div style="background: white; padding: 14px 24px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.04); text-align: center; min-width: 110px; margin: 0 auto;">
                            <span style="display: block; font-size: 2.4rem; font-weight: 800; color: var(--success); line-height: 1; margin-bottom: 6px;">
                                <?= $available_formulas ?> <span style="font-size: 1.1rem; color: var(--text-light); font-weight: 500;">/ <?= $total_limit ?></span>
                            </span>
                            <span style="font-size: 0.7rem; color: var(--text-medium); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                Já usou <?= $used_formulas ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php if ($available_formulas > 0): ?>
                <div style="margin-top: 24px; text-align: center;">
                    <a href="<?= BASE_URL ?>/?page=shop" class="btn btn-gold" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; max-width: 280px; border-radius: 12px; height: 48px; font-size: 0.95rem; font-weight: 600; text-transform: none; letter-spacing: 0.5px; box-shadow: 0 6px 20px rgba(200, 164, 92, 0.25);">
                        <i class="fas fa-shopping-bag" style="font-size: 1.05rem;"></i> Usar Fórmulas Agora
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($user_plan): ?>
        <?php
        // Vantagens específicas por plano
        $plan_advantages = [
            'essencia' => [
                ['icon' => 'fas fa-leaf', 'title' => '1 Fórmula por Mês', 'desc' => 'Receba uma fórmula à sua escolha todos os meses, diretamente em casa.'],
                ['icon' => 'fas fa-truck-fast', 'title' => 'Portes Gratuitos', 'desc' => 'Todas as entregas do seu plano são enviadas sem custos adicionais.'],
                ['icon' => 'fas fa-envelope-open-text', 'title' => 'Newsletter Premium', 'desc' => 'Acesso exclusivo a conteúdos e dicas de Medicina Tradicional Chinesa.'],
            ],
            'vitalidade' => [
                ['icon' => 'fas fa-bolt', 'title' => '2 Fórmulas por Mês', 'desc' => 'Receba duas fórmulas à sua escolha todos os meses, diretamente em casa.'],
                ['icon' => 'fas fa-truck-fast', 'title' => 'Portes Gratuitos', 'desc' => 'Todas as entregas do seu plano são enviadas sem custos adicionais.'],
                ['icon' => 'fas fa-clock', 'title' => 'Acesso Antecipado', 'desc' => 'Seja o primeiro a conhecer e experimentar as nossas novidades.'],
                ['icon' => 'fas fa-tags', 'title' => '15% Desconto Extra', 'desc' => 'Desconto de 15% em qualquer compra adicional na loja.'],
            ],
            'mestre' => [
                ['icon' => 'fas fa-crown', 'title' => '4 Fórmulas por Mês', 'desc' => 'Receba quatro fórmulas à sua escolha todos os meses, diretamente em casa.'],
                ['icon' => 'fas fa-truck-fast', 'title' => 'Portes Gratuitos', 'desc' => 'Todas as entregas do seu plano são enviadas sem custos adicionais.'],
                ['icon' => 'fas fa-video', 'title' => 'Consulta Online', 'desc' => 'Aconselhamento personalizado com os nossos especialistas em MTC.'],
                ['icon' => 'fas fa-tags', 'title' => '25% Desconto Extra', 'desc' => 'Desconto de 25% em qualquer compra adicional na loja.'],
            ],
        ];
        $advantages = $plan_advantages[strtolower($user_plan)] ?? $plan_advantages['essencia'];
        ?>
        <div class="profile-card-luxe fade-in" style="animation-delay: 0.1s">
            <div class="card-luxe-header">
                <h3><i class="fas fa-gem"></i> As Suas Vantagens — Plano <?= ucfirst($user_plan) ?></h3>
            </div>
            <div class="card-luxe-body">
                <div class="advantages-grid">
                    <?php foreach ($advantages as $adv): ?>
                    <div class="adv-item">
                        <div class="adv-icon"><i class="<?= $adv['icon'] ?>"></i></div>
                        <div class="adv-text">
                            <h4><?= $adv['title'] ?></h4>
                            <p><?= $adv['desc'] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>

<!-- Modal de Confirmação de Cancelamento -->
<?php if ($user_plan): ?>
<div class="cancel-modal-overlay" id="cancelModal">
    <div class="cancel-modal">
        <div class="cancel-modal-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Cancelar Subscrição?</h3>
        <p>Tem a certeza que deseja cancelar o seu <strong>Plano <?= ucfirst($user_plan) ?></strong>? Irá perder acesso a:</p>
        <ul class="cancel-losses">
            <li><i class="fas fa-times"></i> Entregas automáticas mensais</li>
            <li><i class="fas fa-times"></i> Descontos exclusivos de membro</li>
            <li><i class="fas fa-times"></i> Portes de envio gratuitos</li>
        </ul>
        <div class="cancel-modal-actions">
            <button class="btn-cancel-confirm" id="btnConfirmCancel" onclick="handleCancelPlan(event)">
                <i class="fas fa-times-circle"></i> Sim, Cancelar Plano
            </button>
            <button class="btn-cancel-back" onclick="closeCancelModal()">
                <i class="fas fa-arrow-left"></i> Manter Subscrição
            </button>
        </div>
    </div>
</div>
<?php endif; ?>
