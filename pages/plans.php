<style>
    .plans-intro {
        text-align: center;
        margin-bottom: 40px;
        padding: 0 20px;
    }
    .plans-intro h2 {
        font-family: var(--font-serif);
        font-size: 2.4rem;
        color: var(--sage-dark);
        margin-bottom: 12px;
    }
    .plans-intro p {
        color: var(--text-medium);
        font-size: 1.05rem;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }
    .premium-plans-container {
        display: flex;
        flex-direction: column;
        gap: 24px;
        padding: 0 20px 60px;
        max-width: 1100px;
        margin: 0 auto;
    }
    .premium-plan {
        background: #fff;
        border-radius: 20px;
        padding: 36px 28px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.04);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    .premium-plan:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    }
    .premium-plan.vitalidade {
        border: 2px solid var(--gold);
        background: linear-gradient(to bottom, #ffffff, #fcfaf5);
        box-shadow: 0 15px 35px rgba(200,165,115,0.15);
    }
    .premium-plan.mestre {
        background: linear-gradient(145deg, #1e2823, #0d120f);
        color: #fff;
    }
    
    .plan-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: var(--gold);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        padding: 8px 20px;
        border-bottom-left-radius: 16px;
        text-transform: uppercase;
        box-shadow: -2px 2px 10px rgba(200,165,115,0.3);
    }
    
    .plan-icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 24px;
    }
    .premium-plan.essencia .plan-icon-wrapper {
        background: rgba(74, 139, 92, 0.08);
        color: var(--sage);
    }
    .premium-plan.vitalidade .plan-icon-wrapper {
        background: linear-gradient(135deg, rgba(200, 165, 115, 0.2), rgba(200, 165, 115, 0.05));
        color: var(--gold-dark);
        border: 1px solid rgba(200, 165, 115, 0.3);
    }
    .premium-plan.mestre .plan-icon-wrapper {
        background: rgba(255, 255, 255, 0.05);
        color: var(--gold);
        border: 1px solid rgba(200, 165, 115, 0.2);
    }

    .plan-header h3 {
        font-family: var(--font-serif);
        font-size: 1.9rem;
        margin-bottom: 6px;
    }
    .premium-plan.essencia h3 { color: var(--sage-dark); }
    .premium-plan.vitalidade h3 { color: var(--gold-dark); }
    .premium-plan.mestre h3 { color: #fff; }

    .plan-price-block {
        display: flex;
        align-items: baseline;
        gap: 4px;
        margin-bottom: 16px;
    }
    .price-currency { font-size: 1.4rem; font-weight: 600; }
    .price-amount { font-size: 3.2rem; font-weight: 800; letter-spacing: -1.5px; }
    .price-period { font-size: 1rem; opacity: 0.6; font-weight: 500; }
    
    .premium-plan.mestre .price-currency,
    .premium-plan.mestre .price-amount { color: var(--gold); }
    .premium-plan.essencia .price-currency,
    .premium-plan.essencia .price-amount,
    .premium-plan.vitalidade .price-currency,
    .premium-plan.vitalidade .price-amount { color: var(--text-dark); }
    
    .plan-description {
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 28px;
        padding-bottom: 28px;
        border-bottom: 1px solid rgba(0,0,0,0.06);
        min-height: 70px;
    }
    .premium-plan.mestre .plan-description {
        border-bottom-color: rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.7);
    }
    .premium-plan.essencia .plan-description,
    .premium-plan.vitalidade .plan-description {
        color: var(--text-medium);
    }

    .premium-plan-features {
        list-style: none;
        padding: 0;
        margin: 0 0 36px 0;
        display: flex;
        flex-direction: column;
        gap: 16px;
        flex-grow: 1;
    }
    .premium-plan-features li {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        font-size: 1rem;
        line-height: 1.4;
    }
    .premium-plan.mestre .premium-plan-features li { color: rgba(255,255,255,0.9); }
    .premium-plan.essencia .premium-plan-features li,
    .premium-plan.vitalidade .premium-plan-features li { color: var(--text-dark); }
    
    .premium-plan-features li i {
        margin-top: 4px;
        font-size: 1rem;
    }
    .premium-plan.essencia .premium-plan-features li i { color: var(--sage); }
    .premium-plan.vitalidade .premium-plan-features li i { color: var(--gold); }
    .premium-plan.mestre .premium-plan-features li i { color: var(--gold); }

    .premium-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        height: 56px;
        border-radius: 14px;
        font-weight: 600;
        font-size: 1.1rem;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-top: auto;
    }
    .premium-btn.essencia-btn {
        background: var(--sage-dark);
        color: #fff;
    }
    .premium-btn.essencia-btn:hover { background: #1a241f; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(30,40,30,0.2); }
    
    .premium-btn.vitalidade-btn {
        background: var(--gold);
        color: #fff;
        box-shadow: 0 8px 20px rgba(200,165,115,0.3);
    }
    .premium-btn.vitalidade-btn:hover { background: var(--gold-dark); transform: translateY(-2px); box-shadow: 0 10px 25px rgba(200,165,115,0.4); }
    
    .premium-btn.mestre-btn {
        background: var(--gold);
        color: #1a241f;
    }
    .premium-btn.mestre-btn:hover { background: #fff; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,255,255,0.2); }
</style>

<div class="page-header">
    <a href="<?= BASE_URL ?>/?page=home" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <h1 class="page-title">Clube AcuSport</h1>
</div>

<div class="animate-on-scroll">
    <div class="plans-intro">
        <h2>Escolha a sua Jornada</h2>
        <p>Junte-se ao nosso clube exclusivo e receba as suas fórmulas com vantagens premium, portes gratuitos e acompanhamento focado no seu bem-estar.</p>
    </div>

    <div class="premium-plans-container">
        <!-- PLANO ESSÊNCIA -->
        <div class="premium-plan essencia">
            <div class="plan-icon-wrapper"><i class="fas fa-leaf"></i></div>
            <div class="plan-header">
                <h3>Essência</h3>
                <div class="plan-price-block">
                    <span class="price-currency">€</span>
                    <span class="price-amount">29</span>
                    <span class="price-period">/mês</span>
                </div>
            </div>
            <div class="plan-description">
                Perfeito para quem está a dar os primeiros passos na Medicina Tradicional Chinesa e procura um ritmo equilibrado.
            </div>
            <ul class="premium-plan-features">
                <li><i class="fas fa-check-circle"></i> <span>1 Fórmula à escolha por mês</span></li>
                <li><i class="fas fa-check-circle"></i> <span>Portes de envio <strong>gratuitos</strong></span></li>
                <li><i class="fas fa-check-circle"></i> <span>Acesso à newsletter premium</span></li>
            </ul>
            <a href="<?= BASE_URL ?>/?page=plan-checkout&plan=essencia" class="premium-btn essencia-btn">
                Subscrever <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <!-- PLANO VITALIDADE -->
        <div class="premium-plan vitalidade">
            <div class="plan-badge"><i class="fas fa-star" style="margin-right:4px;"></i> Popular</div>
            <div class="plan-icon-wrapper"><i class="fas fa-bolt"></i></div>
            <div class="plan-header">
                <h3>Vitalidade</h3>
                <div class="plan-price-block">
                    <span class="price-currency">€</span>
                    <span class="price-amount">49</span>
                    <span class="price-period">/mês</span>
                </div>
            </div>
            <div class="plan-description">
                O equilíbrio ideal desenhado para atletas e utilizadores regulares focados na performance máxima.
            </div>
            <ul class="premium-plan-features">
                <li><i class="fas fa-check-circle"></i> <span>2 Fórmulas à escolha por mês</span></li>
                <li><i class="fas fa-check-circle"></i> <span>Portes de envio <strong>gratuitos</strong></span></li>
                <li><i class="fas fa-check-circle"></i> <span><strong>15% desconto</strong> em compras extra</span></li>
                <li><i class="fas fa-check-circle"></i> <span>Acesso antecipado a novidades</span></li>
            </ul>
            <a href="<?= BASE_URL ?>/?page=plan-checkout&plan=vitalidade" class="premium-btn vitalidade-btn">
                Subscrever <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <!-- PLANO MESTRE -->
        <div class="premium-plan mestre">
            <div class="plan-icon-wrapper"><i class="fas fa-crown"></i></div>
            <div class="plan-header">
                <h3>Mestre</h3>
                <div class="plan-price-block">
                    <span class="price-currency">€</span>
                    <span class="price-amount">89</span>
                    <span class="price-period">/mês</span>
                </div>
            </div>
            <div class="plan-description">
                A experiência mais luxuosa e personalizada para um acompanhamento e bem-estar absolutamente total.
            </div>
            <ul class="premium-plan-features">
                <li><i class="fas fa-check-circle"></i> <span>4 Fórmulas à escolha por mês</span></li>
                <li><i class="fas fa-check-circle"></i> <span>Portes de envio <strong>gratuitos</strong></span></li>
                <li><i class="fas fa-check-circle"></i> <span><strong>25% desconto</strong> em compras extra</span></li>
                <li><i class="fas fa-check-circle"></i> <span>Consulta online de aconselhamento</span></li>
            </ul>
            <a href="<?= BASE_URL ?>/?page=plan-checkout&plan=mestre" class="premium-btn mestre-btn">
                Subscrever VIP <i class="fas fa-crown"></i>
            </a>
        </div>
    </div>
</div>
