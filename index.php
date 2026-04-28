<?php
// =============================================
// AcuSport App - Router Principal
// =============================================

require_once __DIR__ . '/includes/functions.php';

// Determinar a página atual
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$allowed_pages = ['home', 'shop', 'product', 'cart', 'checkout', 'profile', 'profile-edit', 'login', 'register', 'recover', 'about', 'orders', 'order-success', 'contacts', 'plans', 'plan-checkout', 'plan-success', 'order-details', 'admin', 'admin-orders', 'admin-products', 'admin-product-edit', 'admin-users'];

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

$cart_count = 0;
try {
    $cart_count = getCartCount();
} catch (Exception $e) {
    $cart_count = 0;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#4A5A4A">
<?php
// Determine dynamic SEO tags
$seo_title = "AcuSport — Suplementação Natural";
$seo_desc = "Fórmulas de Medicina Tradicional Chinesa com rigor científico e qualidade europeia.";

if ($page === 'product' && isset($_GET['id'])) {
    try {
        $seo_product = getProduct($_GET['id']);
        if ($seo_product) {
            $seo_title = sanitize($seo_product['nome']) . " — AcuSport";
            if (!empty($seo_product['descricao_curta'])) {
                $seo_desc = sanitize($seo_product['descricao_curta']);
            }
        }
    } catch (Exception $e) {}
} elseif ($page === 'shop') {
    $seo_title = "Loja Online — AcuSport";
} elseif ($page === 'about') {
    $seo_title = "Sobre a AcuSport";
}
?>
    <meta name="description" content="<?= $seo_desc ?>">
    <meta name="author" content="AcuSport">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= $seo_title ?>">
    <meta property="og:description" content="<?= $seo_desc ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= BASE_URL ?>/?page=<?= $page ?>">
    <meta property="og:locale" content="pt_PT">
    <meta property="og:site_name" content="AcuSport">
    
    <title><?= $seo_title ?></title>
    
    <!-- Google Analytics (Exemplo de Integração) -->
    <!--
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-XXXXXXXXXX');
    </script>
    -->
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="192x192" href="<?= ASSETS_URL ?>/images/icon-192.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- App CSS -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/app.css">
    <?php if (strpos($page, 'admin') === 0): ?>
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/admin.css">
    <?php endif; ?>
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="<?= BASE_URL ?>/manifest.json">
    <link rel="apple-touch-icon" href="<?= ASSETS_URL ?>/images/icon-192.png">
</head>
<body>
<div class="mobile-frame">
    <!-- Status Bar Spacer -->
    <div class="status-bar-spacer"></div>

    <!-- Toast Notification Container -->
    <div id="toast-container"></div>

    <!-- Main Content -->
    <main id="app-content" class="app-content">
        <?php include __DIR__ . '/pages/' . $page . '.php'; ?>
    </main>

    <!-- Bottom Navigation -->
    <?php if (!in_array($page, ['login', 'register', 'checkout', 'order-success', 'plan-checkout', 'plan-success', 'admin', 'admin-orders', 'admin-products', 'admin-product-edit', 'admin-users'])): ?>
    <nav class="bottom-nav" id="bottom-nav" aria-label="Navegação principal">
        <a href="<?= BASE_URL ?>/?page=home" class="nav-item <?= $page === 'home' ? 'active' : '' ?>" id="nav-home" aria-label="Início">
            <div class="nav-icon">
                <i class="fas fa-home"></i>
            </div>
            <span>Início</span>
        </a>
        <a href="<?= BASE_URL ?>/?page=shop" class="nav-item <?= $page === 'shop' ? 'active' : '' ?>" id="nav-shop" aria-label="Loja">
            <div class="nav-icon">
                <i class="fas fa-store"></i>
            </div>
            <span>Loja</span>
        </a>
        <a href="<?= BASE_URL ?>/?page=cart" class="nav-item <?= $page === 'cart' ? 'active' : '' ?>" id="nav-cart" aria-label="Carrinho">
            <div class="nav-icon">
                <i class="fas fa-shopping-bag"></i>
                <?php if ($cart_count > 0): ?>
                <span class="cart-badge" id="cart-badge"><?= $cart_count ?></span>
                <?php endif; ?>
            </div>
            <span>Carrinho</span>
        </a>
        <a href="<?= BASE_URL ?>/?page=contacts" class="nav-item <?= $page === 'contacts' ? 'active' : '' ?>" id="nav-contacts" aria-label="Contactos">
            <div class="nav-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <span>Contactos</span>
        </a>
        <a href="<?= BASE_URL ?>/?page=profile" class="nav-item <?= in_array($page, ['profile', 'orders']) ? 'active' : '' ?>" id="nav-profile" aria-label="Perfil">
            <div class="nav-icon">
                <i class="fas fa-user"></i>
            </div>
            <span>Perfil</span>
        </a>
    </nav>
    <?php endif; ?>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner">
            <div class="spinner-ring"></div>
            <span class="spinner-text">A carregar...</span>
        </div>
    </div>

    <!-- Multibanco Payment Fullscreen Overlay -->
    <div id="mb-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: #fff; flex-direction: column; align-items: center; justify-content: center; padding: 24px; box-sizing: border-box;">
        <!-- Close / Back button -->
        <button onclick="closeMultibancoOverlay()" style="position: absolute; top: 20px; left: 20px; background: none; border: none; font-size: 1.3rem; color: var(--text-medium, #666); cursor: pointer; padding: 8px; z-index: 10; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i>
            <span style="font-size: 0.9rem; font-weight: 500;">Voltar</span>
        </button>

        <!-- Card Container -->
        <div style="width: 100%; max-width: 380px; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <!-- Success check icon -->
            <div style="margin-bottom: 24px; text-align: center;">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                    <i class="fas fa-check" style="font-size: 1.4rem; color: #2e7d32;"></i>
                </div>
                <h2 style="font-family: 'Playfair Display', serif; font-size: 1.3rem; font-weight: 600; color: #1a1a1a; margin: 0 0 4px;">Referência Gerada</h2>
                <p style="font-size: 0.8rem; color: #888; margin: 0;">Efetue o pagamento com os dados abaixo</p>
            </div>

            <!-- Multibanco Card -->
            <div id="mb-ref-box" style="background: linear-gradient(135deg, #1a3a5c, #0d2137); border-radius: 16px; padding: 24px; color: white; box-shadow: 0 10px 30px rgba(0,0,0,0.15); width: 100%;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <img src="https://portugalexpresso.pt/wp-content/uploads/2022/12/kisspng-vector-graphics-multibanco-computer-icons-logo-por-pegasus-mtodos-de-pagamento-5b6d61ae94ee21.76971059153389508661-600x600.png" alt="Multibanco" style="height: 30px; filter: brightness(0) invert(1); opacity: 0.9;">
                    <span style="font-weight: 700; font-size: 1rem; letter-spacing: 0.5px;">Referência Multibanco</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.75rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px;">Entidade</span>
                        <span id="mb-entidade" style="font-size: 1.1rem; font-weight: 700; font-family: 'Courier New', monospace; letter-spacing: 2px; white-space: nowrap;">11249</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.75rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px;">Referência</span>
                        <span id="mb-referencia" style="font-size: 1.1rem; font-weight: 700; font-family: 'Courier New', monospace; letter-spacing: 2px; white-space: nowrap;">---</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 14px;">
                        <span style="font-size: 0.75rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px;">Valor</span>
                        <span id="mb-valor" style="font-size: 1.2rem; font-weight: 800; color: #4ecb71; white-space: nowrap;">---</span>
                    </div>
                </div>
                <div style="margin-top: 20px; background: rgba(255,255,255,0.08); border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-clock" style="color: #ffc107; font-size: 1rem;"></i>
                    <div style="flex: 1;">
                        <span style="font-size: 0.75rem; color: rgba(255,255,255,0.6);">Válido durante</span>
                        <div id="mb-countdown" style="font-size: 1rem; font-weight: 700; color: #ffc107; font-family: 'Courier New', monospace;">10:00</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Payment Button -->
        <div style="width: 100%; max-width: 380px; padding-bottom: 24px;">
            <button id="btn-mb-confirm" onclick="confirmMultibancoPayment()" style="
                width: 100%;
                height: 58px;
                border: none;
                border-radius: 14px;
                background: linear-gradient(135deg, #2e7d32, #388e3c, #43a047);
                color: #fff;
                font-size: 1.1rem;
                font-weight: 700;
                letter-spacing: 0.5px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                box-shadow: 0 6px 20px rgba(46, 125, 50, 0.35), 0 2px 6px rgba(0,0,0,0.1);
                transition: all 0.25s ease;
                position: relative;
                overflow: hidden;
            " onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 8px 28px rgba(46,125,50,0.45), 0 3px 8px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 6px 20px rgba(46,125,50,0.35), 0 2px 6px rgba(0,0,0,0.1)'">
                <i class="fas fa-shield-alt" style="font-size: 1.15rem;"></i>
                <span>Confirmar Pagamento</span>
            </button>
            <p style="text-align: center; font-size: 0.7rem; color: #aaa; margin-top: 12px; display: flex; align-items: center; justify-content: center; gap: 5px;">
                <i class="fas fa-lock" style="font-size: 0.6rem;"></i> Pagamento seguro e encriptado
            </p>
        </div>
    </div>

    <!-- App JavaScript -->
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
        const API_URL = BASE_URL + '/api';
    </script>
    <script src="<?= ASSETS_URL ?>/js/app.js?v=<?= time() ?>"></script>
    </div>
</body>
</html>
