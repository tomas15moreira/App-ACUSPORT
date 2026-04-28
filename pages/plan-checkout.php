<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/?page=login&redirect=plans');
    exit;
}

$plan_id = isset($_GET['plan']) ? $_GET['plan'] : 'essencia';
$plans = [
    'essencia' => [
        'name' => 'Plano Essência',
        'price' => '29',
        'desc' => '1 Fórmula/mês + Portes Grátis'
    ],
    'vitalidade' => [
        'name' => 'Plano Vitalidade',
        'price' => '49',
        'desc' => '2 Fórmulas/mês + Portes Grátis + 15% Desconto'
    ],
    'mestre' => [
        'name' => 'Plano Mestre',
        'price' => '89',
        'desc' => '4 Fórmulas/mês + Consulta Online + 25% Desconto'
    ]
];
if (!isset($plans[$plan_id])) {
    $plan_id = 'essencia';
}
$plan = $plans[$plan_id];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user_plan'] = $plan_id;
    
    if (isset($_SESSION['user_id'])) {
        try {
            $db = getDB();
            $stmt = $db->prepare("UPDATE users SET plan = :plan WHERE id = :id");
            $stmt->execute([':plan' => $plan_id, ':id' => $_SESSION['user_id']]);
        } catch(Exception $e) {}
    }
    
    echo "<script>window.location.href = '" . BASE_URL . "/?page=plan-success&plan=" . $plan_id . "';</script>";
    exit;
}
?>
<div class="page-header">
    <a href="<?= BASE_URL ?>/?page=plans" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <h1 class="page-title">Checkout</h1>
</div>

<div class="app-content animate-on-scroll" style="padding-bottom: 40px;">
    <div class="checkout-form">
        <!-- Premium Plan Review Card -->
        <div style="background: linear-gradient(135deg, var(--sage-dark), #1a241f); border-radius: 16px; padding: 24px; color: var(--white); margin-bottom: 24px; box-shadow: 0 10px 30px rgba(30,40,30,0.15); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -10px; right: -10px; font-size: 120px; color: rgba(255,255,255,0.03); pointer-events: none;"><i class="fas fa-shield-alt"></i></div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--gold); letter-spacing: 1px; margin-bottom: 8px; text-transform: uppercase;">Resumo da Subscrição</div>
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px; position: relative;">
                <span style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 600;"><?= $plan['name'] ?></span>
                <span style="font-size: 1.8rem; font-weight: 700; color: var(--gold);"><?= $plan['price'] ?>€<span style="font-size: 0.9rem; color: rgba(255,255,255,0.6); font-weight: 500;">/mês</span></span>
            </div>
            <div style="font-size: 0.9rem; color: rgba(255,255,255,0.9); display: flex; align-items: center; gap: 8px; position: relative;">
                <i class="fas fa-check-circle" style="color: var(--gold);"></i> <?= $plan['desc'] ?>
            </div>
        </div>

        <form method="POST" action="<?= BASE_URL ?>/?page=plan-checkout&plan=<?= $plan_id ?>" onsubmit="handlePlanCheckout(event)">
            
            <div class="checkout-section">
                <h2><i class="fas fa-user-circle" style="color:var(--gold);margin-right:8px"></i>Os seus dados</h2>
                <div class="form-group">
                    <input type="text" name="nome" class="form-input" placeholder="Nome Completo" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-input" placeholder="E-mail" required>
                </div>
            </div>

            <div class="checkout-section">
                <h2><i class="fas fa-lock" style="color:var(--gold);margin-right:8px"></i>Pagamento Seguro</h2>
                <div class="payment-options">
                    <div class="payment-option selected" onclick="selectPayment(this)">
                        <input type="radio" name="pagamento" value="mbway" checked>
                        <span style="flex: 1; font-weight: 500; font-size: 0.95rem;">MB WAY</span>
                        <div style="width: 70px; display: flex; align-items: center; justify-content: flex-end; flex-shrink: 0;">
                            <img src="https://www.escolamagica.pt/uploads/payments/mbway.png" alt="MB WAY" style="height: 30px; object-fit: contain;">
                        </div>
                    </div>
                    <div class="payment-option" onclick="selectPayment(this)">
                        <input type="radio" name="pagamento" value="cartao">
                        <span style="flex: 1; font-weight: 500; font-size: 0.9rem; line-height: 1.2; margin-right: 8px;">Cartão de Crédito/Débito</span>
                        <div style="display: flex; gap: 6px; align-items: center; justify-content: flex-end; width: 70px; flex-shrink: 0;">
                            <img src="https://imagedelivery.net/5MYSbk45M80qAwecrlKzdQ/a0dbde26-08e5-456f-8c9c-77d607cdfc00/public" alt="Visa Mastercard" style="height: 35px; max-width: 70px; object-fit: contain;">
                        </div>
                    </div>
                    <div class="payment-option" onclick="selectPayment(this)">
                        <input type="radio" name="pagamento" value="multibanco">
                        <span style="flex: 1; font-weight: 500; font-size: 0.95rem;">Multibanco</span>
                        <div style="width: 70px; display: flex; align-items: center; justify-content: flex-end; flex-shrink: 0;">
                            <img src="https://portugalexpresso.pt/wp-content/uploads/2022/12/kisspng-vector-graphics-multibanco-computer-icons-logo-por-pegasus-mtodos-de-pagamento-5b6d61ae94ee21.76971059153389508661-600x600.png" alt="Multibanco" style="height: 30px; object-fit: contain;">
                        </div>
                    </div>
                </div>

                <!-- Dados MB WAY -->
                <div class="payment-details" id="payment-details-mbway" style="margin-top: 20px;">
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

                <!-- Dados do Cartão -->
                <div class="payment-details" id="payment-details-cartao" style="margin-top: 20px; display: none;">
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

                <!-- Dados Multibanco (informação antes de confirmar) -->
                <div class="payment-details" id="payment-details-multibanco" style="margin-top: 20px; display: none;">
                    <div style="background: linear-gradient(135deg, #f8f9fa, #fff); border-radius: 12px; padding: 20px; border: 1px solid rgba(0,0,0,0.05);">
                        <p style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 0; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-university" style="color: var(--gold);"></i>
                            <span>Ao finalizar a subscrição, será gerada uma referência Multibanco válida por <strong>10 minutos</strong> para efetuar o pagamento.</span>
                        </p>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-gold btn-block" id="btn-finalizar-subscricao" style="margin-top: 20px; height: 54px; font-size: 1.05rem; box-shadow: 0 8px 25px rgba(200, 164, 92, 0.3);">
                <i class="fas fa-shield-check"></i> Finalizar Subscrição
            </button>
            <p style="text-align: center; font-size: 0.75rem; color: var(--text-light); margin-top: 16px; display: flex; flex-direction: column; gap: 4px; align-items: center;">
                <span><i class="fas fa-lock" style="color:var(--sage); margin-right:4px;"></i> Ambiente 100% Seguro e Encriptado.</span>
                <span>Pode cancelar a sua subscrição a qualquer momento.</span>
            </p>
        </form>
    </div>
</div>

<script>
// Plan total for Multibanco
var checkoutTotal = <?= $plan['price'] ?>;
</script>
