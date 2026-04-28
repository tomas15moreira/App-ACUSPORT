<?php
require_once __DIR__ . '/../includes/functions.php';

// Enable error reporting for debug, remove in production
error_reporting(E_ALL);
ini_set('display_errors', 0);

$raw_data = file_get_contents('php://input');
$data = json_decode($raw_data, true);

if (!$data) {
    jsonResponse(['success' => false, 'message' => 'Dados inválidos.']);
}

$nome = $data['nome'] ?? '';
$email = $data['email'] ?? '';
$assunto = $data['assunto'] ?? '';
$mensagem = $data['mensagem'] ?? '';

if (empty($nome) || empty($email) || empty($assunto) || empty($mensagem)) {
    jsonResponse(['success' => false, 'message' => 'Preencha todos os campos.']);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(['success' => false, 'message' => 'Email inválido.']);
}

// In a real production app, use PHPMailer or a transactional email service (SendGrid, Mailgun)
$to = 'geral@acusport.pt';
$subject = "Formulário de Contacto: $assunto";
$body = "Recebeu uma nova mensagem do site AcuSport.\n\n";
$body .= "Nome: $nome\n";
$body .= "Email: $email\n";
$body .= "Assunto: $assunto\n\n";
$body .= "Mensagem:\n$mensagem\n";

$headers = "From: no-reply@acusport.pt\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Use @ to suppress warning if mail is not configured locally
$mailSent = @mail($to, $subject, $body, $headers);

// We'll return true even if it fails locally, or we could insert into a database table
// For this MVP, we log it to a text file in case mail() fails
if (!$mailSent) {
    $log = "=================================================\n";
    $log .= "Date: " . date('Y-m-d H:i:s') . "\n";
    $log .= "From: $nome <$email>\n";
    $log .= "Subject: $assunto\n";
    $log .= "Message:\n$mensagem\n\n";
    file_put_contents(__DIR__ . '/../emails_log.txt', $log, FILE_APPEND);
}

jsonResponse(['success' => true, 'message' => 'Mensagem enviada com sucesso!']);
