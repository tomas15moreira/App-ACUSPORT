<?php
// API: Profile Update
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn()) {
    jsonResponse(['success' => false, 'message' => 'Não autenticado'], 401);
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

switch ($action) {
    case 'update_profile':
        $nome = trim($input['nome'] ?? '');
        $telefone = trim($input['telefone'] ?? '');
        if (!$nome) jsonResponse(['success' => false, 'message' => 'O nome é obrigatório.'], 400);
        
        $db = getDB();
        $stmt = $db->prepare("UPDATE users SET nome = :nome, telefone = :telefone WHERE id = :id");
        $stmt->execute([':nome' => $nome, ':telefone' => $telefone ?: null, ':id' => $_SESSION['user_id']]);
        $_SESSION['user_nome'] = $nome;
        jsonResponse(['success' => true, 'message' => 'Dados atualizados com sucesso!']);
        break;

    case 'update_address':
        $morada = trim($input['morada'] ?? '');
        $codigo_postal = trim($input['codigo_postal'] ?? '');
        $cidade = trim($input['cidade'] ?? '');
        
        $db = getDB();
        $stmt = $db->prepare("UPDATE users SET morada = :morada, codigo_postal = :cp, cidade = :cidade WHERE id = :id");
        $stmt->execute([':morada' => $morada ?: null, ':cp' => $codigo_postal ?: null, ':cidade' => $cidade ?: null, ':id' => $_SESSION['user_id']]);
        jsonResponse(['success' => true, 'message' => 'Morada atualizada com sucesso!']);
        break;

    case 'change_password':
        $current = $input['current_password'] ?? '';
        $new = $input['new_password'] ?? '';
        $confirm = $input['confirm_password'] ?? '';
        
        if (!$current || !$new) jsonResponse(['success' => false, 'message' => 'Preencha todos os campos.'], 400);
        if (strlen($new) < 6) jsonResponse(['success' => false, 'message' => 'A nova password deve ter pelo menos 6 caracteres.'], 400);
        if ($new !== $confirm) jsonResponse(['success' => false, 'message' => 'As passwords não coincidem.'], 400);
        
        $db = getDB();
        $stmt = $db->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if (!password_verify($current, $user['password'])) {
            jsonResponse(['success' => false, 'message' => 'Password atual incorreta.'], 400);
        }
        
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->execute([':password' => $hash, ':id' => $_SESSION['user_id']]);
        jsonResponse(['success' => true, 'message' => 'Password alterada com sucesso!']);
        break;

    case 'cancel_plan':
        unset($_SESSION['user_plan']);
        jsonResponse(['success' => true, 'message' => 'Subscrição cancelada com sucesso!']);
        break;

    default:
        jsonResponse(['success' => false, 'message' => 'Ação inválida'], 400);
}
