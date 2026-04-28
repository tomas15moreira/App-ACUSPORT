<?php
// Edição de Perfil
if (!isLoggedIn()) { header('Location: ' . BASE_URL . '/?page=login'); exit; }
$user = getCurrentUser();
?>

<div class="client-area">
    <div class="order-details-hero slide-up">
        <a href="<?= BASE_URL ?>/?page=profile" class="btn-back-link"><i class="fas fa-arrow-left"></i> Voltar ao Perfil</a>
        <h1>Detalhes da Conta</h1>
        <p>Atualize os seus dados pessoais, morada ou altere a sua palavra-passe de segurança.</p>
    </div>

    <div class="client-tab-content active" style="padding-top: 0;">
        <div class="profile-card-luxe fade-in">
            <div class="card-luxe-header">
                <h3><i class="fas fa-user-edit"></i> Dados Pessoais</h3>
            </div>
            <div class="card-luxe-body">
                <form onsubmit="handleUpdateProfile(event)" class="form-premium">
                    <div class="form-group-floating">
                        <input type="text" name="nome" id="nome_input" class="form-input-floating" value="<?= sanitize($user['nome']) ?>" required placeholder=" ">
                        <label for="nome_input">Nome Completo</label>
                    </div>
                    <div class="form-group-floating">
                        <input type="email" name="email" id="email_input" class="form-input-floating" value="<?= sanitize($user['email']) ?>" readonly placeholder=" ">
                        <label for="email_input">E-mail (Não editável)</label>
                    </div>
                    <div class="form-group-floating">
                        <input type="tel" name="telefone" id="telefone_input" class="form-input-floating" value="<?= sanitize($user['telefone'] ?? '') ?>" placeholder=" ">
                        <label for="telefone_input">Telefone de Contacto</label>
                    </div>
                    
                    <h4 style="margin: 24px 0 16px; font-family: var(--font-serif); color: var(--text-dark); font-size: 1.05rem;"><i class="fas fa-map-marker-alt" style="color: var(--gold); margin-right: 6px;"></i> Morada de Faturação e Envio</h4>
                    
                    <div class="form-group-floating">
                        <input type="text" name="morada" id="morada_input" class="form-input-floating" value="<?= sanitize($user['morada'] ?? '') ?>" placeholder=" ">
                        <label for="morada_input">Morada (Rua, Número, Andar)</label>
                    </div>
                    <div style="display: flex; gap: 12px;">
                        <div class="form-group-floating" style="flex: 1;">
                            <input type="text" name="codigo_postal" id="cp_input" class="form-input-floating" value="<?= sanitize($user['codigo_postal'] ?? '') ?>" placeholder=" ">
                            <label for="cp_input">Cód. Postal</label>
                        </div>
                        <div class="form-group-floating" style="flex: 2;">
                            <input type="text" name="cidade" id="cidade_input" class="form-input-floating" value="<?= sanitize($user['cidade'] ?? '') ?>" placeholder=" ">
                            <label for="cidade_input">Cidade</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-dark btn-block mt-3"><i class="fas fa-save"></i> Guardar Alterações</button>
                </form>
            </div>
        </div>

        <div class="profile-card-luxe fade-in" style="animation-delay: 0.1s">
            <div class="card-luxe-header">
                <h3><i class="fas fa-lock"></i> Segurança</h3>
            </div>
            <div class="card-luxe-body">
                <form onsubmit="handleChangePassword(event)" class="form-premium">
                    <div class="form-group-floating">
                        <input type="password" name="new_password" id="pass1_input" class="form-input-floating" placeholder=" " minlength="6">
                        <label for="pass1_input">Nova Palavra-passe</label>
                    </div>
                    <div class="form-group-floating">
                        <input type="password" name="confirm_password" id="pass2_input" class="form-input-floating" placeholder=" " minlength="6">
                        <label for="pass2_input">Confirmar Nova Palavra-passe</label>
                    </div>
                    
                    <button type="submit" class="btn btn-outline btn-block mt-3" style="border-color:var(--border)"><i class="fas fa-key"></i> Atualizar Segurança</button>
                </form>
            </div>
        </div>
        
        <div class="logout-wrapper fade-in" style="animation-delay: 0.2s" style="padding-bottom: 32px;">
            <button class="btn-logout-premium" onclick="logout()">
                <i class="fas fa-sign-out-alt"></i> Terminar Sessão de Forma Segura
            </button>
        </div>
    </div>
</div>
