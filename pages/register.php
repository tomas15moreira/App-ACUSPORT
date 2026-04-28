<?php if (isLoggedIn()) { header('Location: ' . BASE_URL . '/?page=profile'); exit; } ?>
<div class="auth-page">
    <div class="auth-header">
        <div class="logo-text">AcuSport</div>
        <p>Junte-se à revolução da suplementação natural.</p>
    </div>
    <div class="auth-form fade-in">
        <h2>Criar Conta</h2>
        <form onsubmit="handleRegister(event)">
            <div class="form-group">
                <label class="form-label"><i class="fas fa-user" style="color: var(--gold); margin-right: 6px; font-size: 0.75rem;"></i>Nome completo</label>
                <input type="text" name="nome" class="form-input" required placeholder="O seu nome">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-envelope" style="color: var(--gold); margin-right: 6px; font-size: 0.75rem;"></i>Email</label>
                <input type="email" name="email" class="form-input" required placeholder="email@exemplo.com">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-phone" style="color: var(--gold); margin-right: 6px; font-size: 0.75rem;"></i>Telefone</label>
                <input type="tel" name="telefone" class="form-input" placeholder="912 345 678">
            </div>
            <div class="form-group" style="position: relative;">
                <label class="form-label"><i class="fas fa-lock" style="color: var(--gold); margin-right: 6px; font-size: 0.75rem;"></i>Password</label>
                <input type="password" name="password" id="regPassword" class="form-input" required placeholder="Mínimo 6 caracteres" minlength="6" style="padding-right: 44px;">
                <button type="button" onclick="togglePassword('regPassword', this)" style="position: absolute; right: 12px; top: 36px; background: none; border: none; color: var(--text-light); font-size: 0.85rem; padding: 4px; cursor: pointer;"><i class="fas fa-eye"></i></button>
            </div>
            <div class="form-group" style="position: relative;">
                <label class="form-label"><i class="fas fa-lock" style="color: var(--gold); margin-right: 6px; font-size: 0.75rem;"></i>Confirmar Password</label>
                <input type="password" name="password_confirm" id="regPasswordConfirm" class="form-input" required placeholder="Confirme a password" minlength="6" style="padding-right: 44px;">
                <button type="button" onclick="togglePassword('regPasswordConfirm', this)" style="position: absolute; right: 12px; top: 36px; background: none; border: none; color: var(--text-light); font-size: 0.85rem; padding: 4px; cursor: pointer;"><i class="fas fa-eye"></i></button>
            </div>
            <button type="submit" class="btn btn-gold btn-block" style="border-radius: 12px; padding: 15px;">
                <i class="fas fa-user-plus"></i> Criar Conta
            </button>
        </form>
        <div class="auth-divider"><span>ou</span></div>
        <div class="auth-switch">
            Já tem conta? <a href="<?= BASE_URL ?>/?page=login">Entrar</a>
        </div>
        <a href="<?= BASE_URL ?>/?page=home" style="display:block;text-align:center;margin-top:12px;color:var(--text-medium);font-size:0.82rem;display:flex;align-items:center;justify-content:center;gap:6px;">
            <i class="fas fa-arrow-left" style="font-size:0.7rem;"></i> Voltar à loja
        </a>
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
