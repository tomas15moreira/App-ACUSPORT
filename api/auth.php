<?php
// API: Auth Operations
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

switch ($action) {
    case 'login':
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        if (!$email || !$password) jsonResponse(['success' => false, 'message' => 'Preencha todos os campos.'], 400);
        $result = loginUser($email, $password);
        jsonResponse($result);
        break;

    case 'register':
        $nome = trim($input['nome'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $telefone = trim($input['telefone'] ?? '');
        if (!$nome || !$email || !$password) jsonResponse(['success' => false, 'message' => 'Preencha os campos obrigatórios.'], 400);
        if (strlen($password) < 6) jsonResponse(['success' => false, 'message' => 'A password deve ter no mínimo 6 caracteres.'], 400);
        $result = registerUser($nome, $email, $password, $telefone ?: null);
        jsonResponse($result);
        break;

    case 'logout':
        logoutUser();
        jsonResponse(['success' => true]);
        break;

    case 'check':
        jsonResponse(['logged_in' => isLoggedIn(), 'user' => isLoggedIn() ? getCurrentUser() : null]);
        break;

    case 'recover':
        $email = trim($input['email'] ?? '');
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) jsonResponse(['success' => false, 'message' => 'Email inválido.'], 400);
        
        $db = getDB();
        $stmt = $db->prepare("SELECT id, nome FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $stmt = $db->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE id = :id");
            $stmt->execute([':token' => $token, ':expires' => $expires, ':id' => $user['id']]);
            
            $link = BASE_URL . '/?page=recover&token=' . $token;
            
            $to = $email;
            $subject = 'Recuperação de Password - AcuSport';
            $body = "Olá " . $user['nome'] . ",\n\n";
            $body .= "Recebemos um pedido para repor a sua password.\n";
            $body .= "Aceda ao link abaixo para criar uma nova password (válido por 1 hora):\n";
            $body .= $link . "\n\n";
            $body .= "Se não fez este pedido, por favor ignore este email.\n";
            
            $headers = "From: no-reply@acusport.pt\r\n";
            
            @mail($to, $subject, $body, $headers);
            
            // Log for dev environment
            $log = "=================================================\n";
            $log .= "Date: " . date('Y-m-d H:i:s') . "\n";
            $log .= "To: $email\n";
            $log .= "Subject: $subject\n";
            $log .= "Link: $link\n\n";
            file_put_contents(__DIR__ . '/../emails_log.txt', $log, FILE_APPEND);
        }
        
        jsonResponse(['success' => true, 'message' => 'Se o email existir, receberá um link de recuperação.']);
        break;

    case 'reset_password':
        $token = trim($input['token'] ?? '');
        $password = $input['password'] ?? '';
        if (!$token || strlen($password) < 6) jsonResponse(['success' => false, 'message' => 'Dados inválidos ou password demasiado curta.'], 400);
        
        $db = getDB();
        $stmt = $db->prepare("SELECT id FROM users WHERE reset_token = :token AND reset_expires > NOW()");
        $stmt->execute([':token' => $token]);
        $user = $stmt->fetch();
        
        if (!$user) {
            jsonResponse(['success' => false, 'message' => 'O link de recuperação é inválido ou expirou.']);
        }
        
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password = :hash, reset_token = NULL, reset_expires = NULL WHERE id = :id");
        $stmt->execute([':hash' => $hash, ':id' => $user['id']]);
        
        jsonResponse(['success' => true]);
        break;

    default:
        jsonResponse(['success' => false, 'message' => 'Ação inválida'], 400);
}
