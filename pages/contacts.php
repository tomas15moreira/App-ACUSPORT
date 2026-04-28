<?php // Contactos ?>
<div class="page-header">
    <a href="<?= BASE_URL ?>/?page=home" class="back-btn"><i class="fas fa-arrow-left"></i></a>
    <h1 class="page-title">Contactos</h1>
</div>

<div class="contacts-hero slide-up">
    <div class="contacts-hero-content">
        <span class="section-label brand-label">APOIO PERSONALIZADO</span>
        <h2>Fale Connosco</h2>
    </div>
</div>

<div class="contacts-cards-wrapper fade-in">
    <!-- Canais Diretos -->
    <div class="contacts-direct">
        <h2 class="contact-block-title">Canais Diretos</h2>
        
        <div class="contact-item">
            <div class="contact-item-icon" style="color: var(--sage-dark);"><i class="fas fa-map-pin"></i></div>
            <div class="contact-item-info">
                <h3>Sede &amp; Expedição</h3>
                <p>Coimbra Business School - ISCAC<br>Quinta Agrícola - Bencanta<br>3045-601 Coimbra, Portugal</p>
            </div>
        </div>
        
        <div class="contact-item">
            <div class="contact-item-icon" style="color: var(--gold);"><i class="fas fa-envelope"></i></div>
            <div class="contact-item-info">
                <h3>E-mail</h3>
                <p>geral@acusport.pt</p>
                <span>Resposta média em menos de 24h.</span>
            </div>
        </div>
        
        <div class="contact-item">
            <div class="contact-item-icon" style="color: var(--sage);"><i class="fas fa-phone-alt"></i></div>
            <div class="contact-item-info">
                <h3>Atendimento Telefónico</h3>
                <p>+351 900 000 000</p>
                <span>Dias úteis das 09h às 18h.</span>
            </div>
        </div>
        <div class="contact-item">
            <div class="contact-item-icon" style="color: #E1306C;"><i class="fab fa-instagram"></i></div>
            <div class="contact-item-info">
                <h3>Instagram</h3>
                <p><a href="https://www.instagram.com/acusport2025/?hl=pt" target="_blank" style="color: var(--text-dark); text-decoration: none; font-weight: 500;">@acusport2025</a></p>
                <span>Inspiração e conteúdo exclusivo.</span>
            </div>
        </div>

        <div class="contact-item">
            <div class="contact-item-icon" style="color: #1877F2;"><i class="fab fa-facebook-f" style="margin-left: 2px;"></i></div>
            <div class="contact-item-info">
                <h3>Facebook</h3>
                <p><a href="https://www.facebook.com/profile.php?id=61581165574974" target="_blank" style="color: var(--text-dark); text-decoration: none; font-weight: 500;">AcuSport</a></p>
                <span>Acompanhe a nossa comunidade.</span>
            </div>
        </div>
        
        <div class="contact-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d24377.83705808363!2d-8.464504243709898!3d40.20395464731891!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd22f90f59ac3fef%3A0xdb92ab2dc1309724!2sInstituto%20Polit%C3%A9cnico%20de%20Coimbra%20%7C%20Polytechnic%20University%20of%20Coimbra!5e0!3m2!1spt-PT!2spt!4v1776809535772!5m2!1spt-PT!2spt" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <!-- Mensagem Privada -->
    <div class="contacts-form-box">
        <h2 class="contact-block-title">Mensagem Privada</h2>
        <p class="contact-form-desc">Dúvidas sobre fórmulas ou aconselhamento técnico? Escreva-nos e um especialista entrará em contacto.</p>
        
        <form class="contact-form" onsubmit="handleContactForm(event)">
            <div class="form-group-clean">
                <input type="text" name="nome" class="form-input-clean" required placeholder="Nome Completo">
            </div>
            <div class="form-group-clean">
                <input type="email" name="email" class="form-input-clean" required placeholder="Endereço de E-mail">
            </div>
            <div class="form-group-clean">
                <input type="text" name="assunto" class="form-input-clean" required placeholder="Assunto">
            </div>
            <div class="form-group-clean" style="margin-bottom: 32px;">
                <textarea name="mensagem" class="form-input-clean" rows="2" required placeholder="Como podemos ajudar?"></textarea>
            </div>
            <button type="submit" class="btn btn-dark btn-block" style="border-radius: 12px; padding: 16px; font-size: 0.8rem; font-weight: 700; letter-spacing: 1px;" id="contact-submit-btn">
                <i class="fas fa-paper-plane" style="font-size: 0.75rem;"></i> ENVIAR MENSAGEM
            </button>
        </form>
    </div>
</div>
