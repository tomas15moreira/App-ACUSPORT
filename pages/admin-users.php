<?php
if (!isLoggedIn() || !isAdmin()) { header('Location: ' . BASE_URL . '/?page=login'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $del_id = (int)$_POST['delete_user_id'];
    if (adminDeleteUser($del_id)) {
        header('Location: ' . BASE_URL . '/?page=admin-users&deleted=1');
    } else {
        header('Location: ' . BASE_URL . '/?page=admin-users&error=admin');
    }
    exit;
}

$users = getAllUsers();
?>

<div class="admin-header">
    <h1><i class="fas fa-shield-alt"></i> AcuSport</h1>
    <a href="<?= BASE_URL ?>/?page=home" class="admin-back-link"><i class="fas fa-store"></i> Ver Loja</a>
</div>

<nav class="admin-nav">
    <a href="<?= BASE_URL ?>/?page=admin"><i class="fas fa-chart-pie"></i> Dashboard</a>
    <a href="<?= BASE_URL ?>/?page=admin-orders"><i class="fas fa-box"></i> Encomendas</a>
    <a href="<?= BASE_URL ?>/?page=admin-products"><i class="fas fa-leaf"></i> Produtos</a>
    <a href="<?= BASE_URL ?>/?page=admin-users" class="active"><i class="fas fa-users"></i> Clientes</a>
</nav>

<div class="admin-page">
    <div class="admin-page-title">
        <span>Clientes (<?= count($users) ?>)</span>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
    <div class="admin-flash success"><i class="fas fa-check-circle"></i> Cliente eliminado com sucesso.</div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'admin'): ?>
    <div class="admin-flash error"><i class="fas fa-exclamation-circle"></i> Não é possível eliminar contas de administrador.</div>
    <?php endif; ?>

    <?php foreach ($users as $u): ?>
    <div class="user-admin-card">
        <div class="user-admin-top">
            <div class="user-avatar"><?= strtoupper(mb_substr($u['nome'], 0, 1)) ?></div>
            <div style="flex: 1;">
                <div class="user-admin-name">
                    <?= sanitize($u['nome']) ?>
                    <?php if ($u['is_admin']): ?><span class="admin-badge">ADMIN</span><?php endif; ?>
                    <?php if (!empty($u['plan'])): ?>
                        <span style="font-size: 0.65rem; background: rgba(200,165,115,0.15); color: var(--gold-dark); padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-left: 6px; vertical-align: middle;"><i class="fas fa-crown"></i> VIP</span>
                    <?php endif; ?>
                </div>
                <div class="user-admin-email"><?= sanitize($u['email']) ?><?= $u['telefone'] ? ' · ' . sanitize($u['telefone']) : '' ?></div>
                <div style="font-size: 0.7rem; color: var(--text-light); margin-top: 2px;">
                    <i class="far fa-calendar" style="font-size: 0.6rem;"></i> Registado: <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                    <?= $u['cidade'] ? ' · ' . sanitize($u['cidade']) : '' ?>
                </div>
            </div>
            <?php if (!$u['is_admin']): ?>
            <button type="button" class="btn-delete-user" title="Eliminar cliente" onclick="openDeleteModal(<?= $u['id'] ?>, '<?= sanitize($u['nome']) ?>', '<?= sanitize($u['email']) ?>')"><i class="fas fa-trash"></i></button>
            <?php endif; ?>
        </div>
        <div class="user-admin-stats">
            <div class="user-stat" style="flex: 1;">
                <div class="user-stat-value"><?= $u['total_orders'] ?></div>
                <div class="user-stat-label">Encomendas</div>
            </div>
            <div class="user-stat" style="flex: 1; border-left: 1px solid var(--border); padding-left: 15px;">
                <div class="user-stat-value"><?= formatPrice($u['total_spent']) ?></div>
                <div class="user-stat-label">Total Gasto</div>
            </div>
            <div class="user-stat" style="flex: 1; border-left: 1px solid var(--border); padding-left: 15px;">
                <?php
                $planName = 'Nenhum';
                $planColor = 'var(--text-light)';
                if (!empty($u['plan'])) {
                    if ($u['plan'] === 'essencia') { $planName = 'Essência'; $planColor = 'var(--sage)'; }
                    elseif ($u['plan'] === 'vitalidade') { $planName = 'Vitalidade'; $planColor = 'var(--sage-dark)'; }
                    elseif ($u['plan'] === 'mestre') { $planName = 'Mestre'; $planColor = 'var(--gold)'; }
                }
                ?>
                <div class="user-stat-value" style="color: <?= $planColor ?>; font-size: 0.95rem; margin-top: 1px;">
                    <?= $planName ?>
                </div>
                <div class="user-stat-label">Plano Subscrito</div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Delete User Modal -->
<div id="deleteUserModal" class="admin-modal-overlay">
    <div class="admin-modal">
        <div class="admin-modal-icon danger"><i class="fas fa-user-slash"></i></div>
        <h3>Eliminar Cliente</h3>
        <p>Tem a certeza que pretende eliminar a conta de</p>
        <p style="margin: 8px 0 0;">
            <strong id="delUserName" style="color: var(--text-dark); font-size: 0.92rem;"></strong><br>
            <span id="delUserEmail" style="font-size: 0.75rem; color: var(--text-light);"></span>
        </p>
        <div class="modal-warning"><i class="fas fa-exclamation-triangle"></i> Esta ação é permanente e não pode ser revertida.</div>
        <div class="modal-actions">
            <button onclick="closeDeleteModal()" class="btn-cancel">Cancelar</button>
            <form id="deleteUserForm" method="POST" style="flex:1; margin:0;">
                <input type="hidden" name="delete_user_id" id="deleteUserId" value="">
                <button type="submit" class="btn-danger" style="width:100%;">Eliminar</button>
            </form>
        </div>
    </div>
</div>

<script>
function openDeleteModal(id, name, email) {
    document.getElementById('deleteUserId').value = id;
    document.getElementById('delUserName').textContent = name;
    document.getElementById('delUserEmail').textContent = email;
    document.getElementById('deleteUserModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeDeleteModal() {
    document.getElementById('deleteUserModal').style.display = 'none';
    document.body.style.overflow = '';
}
document.getElementById('deleteUserModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
