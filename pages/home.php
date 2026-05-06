<?php
// Home Page
$featured = [];
$categories = [];
try {
    $featured = getProducts(4, null, null, 1);
    $categories = getCategories();
} catch (Exception $e) {}
?>

<!-- Hero Section -->
<section class="hero slide-up">
    <img src="<?= ASSETS_URL ?>/images/logo.png" alt="AcuSport Logo" class="floating-logo" style="height: 54px; margin-bottom: 24px; filter: brightness(0) invert(1);">
    <span class="section-label brand-label">A SUA SAÚDE NO ESTADO PURO</span>
    <h1>A sabedoria milenar, com a ciência de hoje.</h1>
    <p>Fórmulas de Medicina Tradicional Chinesa e suplementação natural de excelência.</p>
    <div class="hero-buttons">
        <a href="<?= BASE_URL ?>/?page=shop" class="btn btn-gold btn-sm">Explorar Loja</a>
        <a href="<?= BASE_URL ?>/?page=about" class="btn btn-outline btn-sm">A Nossa Essência</a>
    </div>
</section>

<!-- O Nosso Padrão -->
<section class="standards animate-on-scroll">
    <span class="section-label">O NOSSO PADRÃO</span>
    <h2 class="section-title">Rigor Científico &amp; Qualidade Europeia</h2>
    <div class="standards-grid">
        <div class="standard-card">
            <div class="icon-circle"><i class="fas fa-leaf"></i></div>
            <h3>100% Naturais</h3>
            <p>Extratos puros e isentos de químicos nocivos para máxima absorção.</p>
        </div>
        <div class="standard-card">
            <div class="icon-circle"><i class="fas fa-microscope"></i></div>
            <h3>Rigor Clínico</h3>
            <p>Lotes analisados em laboratórios independentes europeus.</p>
        </div>
        <div class="standard-card">
            <div class="icon-circle"><i class="fas fa-certificate"></i></div>
            <h3>Qualidade EU</h3>
            <p>Produzidos sob as normas de fabrico da União Europeia.</p>
        </div>
        <div class="standard-card">
            <div class="icon-circle"><i class="fas fa-yin-yang"></i></div>
            <h3>Sabedoria MTC</h3>
            <p>Formulados com base milenar da Medicina Tradicional Chinesa.</p>
        </div>
    </div>
</section>

<!-- Produtos em Destaque -->
<?php if (!empty($featured)): ?>
<section class="featured-section animate-on-scroll">
    <div class="featured-header">
        <div>
            <span class="section-label" style="margin-bottom:4px">SELEÇÃO PREMIUM</span>
            <h2 class="section-title" style="margin-bottom:0">Em Destaque</h2>
        </div>
        <a href="<?= BASE_URL ?>/?page=shop">Ver todos <i class="fas fa-arrow-right" style="font-size:0.7rem"></i></a>
    </div>
    <div class="featured-scroll">
        <?php foreach ($featured as $p): ?>
        <a href="<?= BASE_URL ?>/?page=product&id=<?= $p['id'] ?>" class="product-card">
            <div class="product-img">
                <span class="category-tag"><?= sanitize($p['categoria_nome']) ?></span>
                <?php $imgUrl = getProductImageUrl($p['imagem']); ?>
                <?php if (strpos($imgUrl, 'placeholder') !== false): ?>
                    <div class="product-placeholder"><i class="fas fa-leaf"></i></div>
                <?php else: ?>
                    <img src="<?= $imgUrl ?>" alt="<?= sanitize($p['nome']) ?>">
                <?php endif; ?>
            </div>
            <div class="product-info">
                <div class="product-name"><?= sanitize($p['nome']) ?></div>
                <div class="product-price-row">
                    <span class="product-price"><?= formatPrice($p['preco']) ?></span>
                    <span class="product-detail-link">Ver <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <!-- Barra indicadora de scroll -->
    <div class="featured-scroll-track">
        <div class="featured-scroll-thumb" id="featured-thumb"></div>
    </div>
</section>
<script>
(function(){
    var el = document.querySelector('.featured-scroll');
    var thumb = document.getElementById('featured-thumb');
    if (!el || !thumb) return;
    el.addEventListener('scroll', function(){
        var max = el.scrollWidth - el.clientWidth;
        var pct = max > 0 ? (el.scrollLeft / max) * 50 : 0;
        thumb.style.transform = 'translateX(' + pct + '%)';
    });
})();
</script>
<?php endif; ?>

<!-- Categorias -->
<?php if (!empty($categories)): ?>
<section class="categories-section animate-on-scroll">
    <span class="section-label">CATEGORIAS</span>
    <h2 class="section-title">Encontre a sua fórmula</h2>
    <div class="categories-grid">
        <?php 
        foreach (array_slice($categories, 0, 6) as $cat): 
            $slug = strtolower($cat['slug']);
            $icon = 'fas fa-leaf'; // Default
            
            if (strpos($slug, 'energia') !== false) $icon = 'fas fa-bolt';
            elseif (strpos($slug, 'emagrecimento') !== false || strpos($slug, 'peso') !== false) $icon = 'fas fa-weight';
            elseif (strpos($slug, 'articulac') !== false || strpos($slug, 'osso') !== false) $icon = 'fas fa-bone';
            elseif (strpos($slug, 'imun') !== false || strpos($slug, 'defesa') !== false) $icon = 'fas fa-shield-alt';
            elseif (strpos($slug, 'sono') !== false || strpos($slug, 'mente') !== false) $icon = 'fas fa-moon';
            elseif (strpos($slug, 'vitamina') !== false) $icon = 'fas fa-pills';
            elseif (strpos($slug, 'desporto') !== false || strpos($slug, 'muscul') !== false) $icon = 'fas fa-dumbbell';
            elseif (strpos($slug, 'fito') !== false) $icon = 'fas fa-seedling';
        ?>
        <a href="<?= BASE_URL ?>/?page=shop&category=<?= $cat['slug'] ?>" class="cat-card">
            <i class="<?= $icon ?> cat-icon"></i>
            <div class="cat-name"><?= sanitize($cat['nome']) ?></div>
        </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Reviews Section -->
<section class="reviews-section animate-on-scroll">
    <span class="section-label">COMUNIDADE</span>
    <h2 class="section-title">O que dizem os nossos atletas</h2>
    
    <div class="reviews-slider">
        <!-- Review 1 -->
        <div class="review-card">
            <div class="review-header">
                <div class="review-avatar">MR</div>
                <div class="review-meta">
                    <div class="review-author">Miguel R.</div>
                    <div class="review-role">ATLETA DE CROSSFIT</div>
                </div>
            </div>
            <div class="review-stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="review-text">"A dor lombar acompanhava-me em todos os treinos pesados. A fórmula F-25B mudou completamente a minha recuperação. Sinto-me limpo e sem a dependência de anti-inflamatórios de farmácia."</p>
        </div>
        
        <!-- Review 2 -->
        <div class="review-card">
            <div class="review-header">
                <div class="review-avatar">ST</div>
                <div class="review-meta">
                    <div class="review-author">Sofia T.</div>
                    <div class="review-role">EMPREENDEDORA</div>
                </div>
            </div>
            <div class="review-stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="review-text">"Sou assinante do plano Vitalidade. Receber as fórmulas em casa todos os meses sem me preocupar é fantástico. O foco que o Neuro Mais me dá no trabalho é inexplicável."</p>
        </div>
        
        <!-- Review 3 -->
        <div class="review-card">
            <div class="review-header">
                <div class="review-avatar">JP</div>
                <div class="review-meta">
                    <div class="review-author">João P.</div>
                    <div class="review-role">MARATONISTA</div>
                </div>
            </div>
            <div class="review-stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="review-text">"Finalmente uma marca que entende que a nutrição vai além da proteína. A abordagem da AcuSport à Medicina Tradicional Chinesa elevou o meu rendimento nas maratonas."</p>
        </div>
    </div>
</section>

<!-- Clube VIP Banner -->
<section class="vip-section animate-on-scroll">
    <div class="vip-banner">
        <span class="vip-label"><i class="fas fa-crown"></i> EXCLUSIVO</span>
        <h2>Junte-se ao Clube VIP</h2>
        <p>Subscreva os nossos planos mensais e receba as fórmulas em casa com portes grátis, descontos exclusivos e acompanhamento.</p>
        <a href="<?= BASE_URL ?>/?page=plans" class="btn btn-dark" style="width: auto; padding: 14px 28px; border-radius: 28px;">DESCOBRIR VANTAGENS</a>
    </div>
</section>

<!-- Redes Sociais -->
<section style="padding: 0 20px 24px; animation: slideUp 0.6s ease forwards; opacity: 0; animation-delay: 0.2s;">
    <div style="background: rgba(200, 165, 115, 0.05); border: 1px solid rgba(200, 165, 115, 0.15); border-radius: 16px; padding: 24px; text-align: center;">
        <h3 style="font-family: var(--font-serif); font-size: 1.15rem; color: var(--text-dark); margin-bottom: 6px;">Siga-nos nas Redes Sociais</h3>
        <p style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 20px;">Junte-se à nossa comunidade para conteúdo exclusivo e novidades.</p>
        <div style="display: flex; justify-content: center; gap: 16px;">
            <a href="https://www.instagram.com/acusport2025/?hl=pt" target="_blank" style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; text-decoration: none; box-shadow: 0 6px 15px rgba(220, 39, 67, 0.25); transition: transform 0.3s ease;">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://www.facebook.com/profile.php?id=61581165574974" target="_blank" style="width: 48px; height: 48px; border-radius: 14px; background: #1877F2; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; text-decoration: none; box-shadow: 0 6px 15px rgba(24, 119, 242, 0.25); transition: transform 0.3s ease;">
                <i class="fab fa-facebook-f"></i>
            </a>
        </div>
    </div>
</section>

<!-- Aviso Académico -->
<section style="padding: 0 20px 24px; text-align: center;">
    <div style="border-top: 1px solid rgba(200,165,115,0.15); padding-top: 24px;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 8px;">
            <div style="width: 28px; height: 28px; border-radius: 50%; background: rgba(200,165,115,0.08); display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-graduation-cap" style="font-size: 0.7rem; color: var(--gold);"></i>
            </div>
            <span style="font-size: 0.72rem; font-weight: 600; color: var(--text-light); letter-spacing: 0.3px;">Projeto Académico</span>
        </div>
        <p style="font-size: 0.68rem; color: var(--text-light); opacity: 0.7; line-height: 1.5;">
            Esta aplicação foi desenvolvida exclusivamente para fins académicos.<br>
            © <?= date('Y') ?> AcuSport — Todos os dados são fictícios.
        </p>
    </div>
</section>
