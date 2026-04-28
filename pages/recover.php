<?php if (isLoggedIn()) { header('Location: ' . BASE_URL . '/?page=profile'); exit; } ?>
<div class="auth-page">
    <div class="auth-header">
        <div class="logo-text">AcuSport</div>
        <p>Recuperação de Password</p>
    </div>
    
    <div class="auth-form fade-in">
        <?php if (!isset($_GET['token'])): ?>
        <h2>Esqueceu-se da password?</h2>
        <p style="font-size: 0.85rem; color: var(--text-medium); margin-bottom: 20px;">Insira o seu email abaixo e enviaremos um link para criar uma nova password.</p>
        <form onsubmit="handleRecover(event)">
            <div class="form-group">
                <label class="form-label"><i class="fas fa-envelope" style="color: var(--gold); margin-right: 6px; font-size: 0.75rem;"></i>Email</label>
                <input type="email" name="email" class="form-input" required placeholder="email@exemplo.com">
            </div>
            <button type="submit" class="btn btn-gold btn-block" style="border-radius: 12px; padding: 15px;">
                Enviar Link <i class="fas fa-paper-plane" style="margin-left: 6px;"></i>
            </button>
        </form>
        <?php else: ?>
        <h2>Nova Password</h2>
        <form onsubmit="handleResetPassword(event)">
            <input type="hidden" name="token" value="<?= sanitize($_GET['token']) ?>">
            <div class="form-group" style="position: relative;">
                <label class="form-label"><i class="fas fa-lock" style="color: var(--gold); margin-right: 6px; font-size: 0.75rem;"></i>Nova Password</label>
                <input type="password" name="password" id="resetPassword" class="form-input" required placeholder="••••••••" minlength="6" style="padding-right: 44px;">
                <button type="button" onclick="togglePassword('resetPassword', this)" style="position: absolute; right: 12px; top: 36px; background: none; border: none; color: var(--text-light); font-size: 0.85rem; padding: 4px; cursor: pointer;"><i class="fas fa-eye"></i></button>
            </div>
            <button type="submit" class="btn btn-gold btn-block" style="border-radius: 12px; padding: 15px;">
                Atualizar Password <i class="fas fa-check" style="margin-left: 6px;"></i>
            </button>
        </form>
        <?php endif; ?>

        <div class="auth-switch" style="margin-top: 24px;">
            <a href="<?= BASE_URL ?>/?page=login" style="font-weight: 500;"><i class="fas fa-arrow-left" style="font-size: 0.8rem; margin-right: 4px;"></i> Voltar ao Login</a>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
