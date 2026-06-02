<?php
/**
 * ConstruHielo — Formulario de contacto
 * Requiere PHPMailer. Instalación:
 *   composer require phpmailer/phpmailer
 * O descarga manual en: https://github.com/PHPMailer/PHPMailer/releases
 * y sube la carpeta /PHPMailer/ junto a este archivo.
 */

declare(strict_types=1);

// ── Configuración ────────────────────────────────────────────────────────────
// ⚠️ Cambia estos valores antes de subir al servidor

const GMAIL_USER = 'construhielo@gmail.com';   // Cuenta Gmail que ENVÍA
const GMAIL_PASS = 'xxxx xxxx xxxx xxxx';       // App Password de Google (16 caracteres)
const MAIL_TO   = 'construhielo@gmail.com';     // Dónde llegan los correos
const MAIL_NAME = 'ConstruHielo Web';           // Nombre del remitente

// Dominio permitido (evita que otros sitios usen tu endpoint)
// Pon tu dominio real: 'https://construhielo.com.mx'
// Para pruebas locales usa '*'
const ALLOWED_ORIGIN = '*';

// ── CORS ─────────────────────────────────────────────────────────────────────
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . ALLOWED_ORIGIN);
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Responde preflight OPTIONS sin ejecutar nada más
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Solo acepta POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

// ── Leer y validar entrada ────────────────────────────────────────────────────
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Cuerpo inválido.']);
    exit;
}

// Sanitizar
function clean(mixed $val): string {
    return htmlspecialchars(strip_tags(trim((string)($val ?? ''))), ENT_QUOTES, 'UTF-8');
}

$nombre   = clean($data['nombre']   ?? '');
$empresa  = clean($data['empresa']  ?? '') ?: '—';
$email    = filter_var(trim($data['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$telefono = clean($data['telefono'] ?? '');
$producto = clean($data['producto'] ?? '');
$mensaje  = clean($data['mensaje']  ?? '');

// Validar campos requeridos
$errors = [];
if (!$nombre)   $errors[] = 'Nombre requerido.';
if (!$email)    $errors[] = 'Correo inválido.';
if (!$telefono) $errors[] = 'Teléfono requerido.';
if (!$producto) $errors[] = 'Producto requerido.';
if (!$mensaje)  $errors[] = 'Mensaje requerido.';

if ($errors) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// ── Cargar PHPMailer ──────────────────────────────────────────────────────────
// Opción A: Composer (recomendado en cPanel con Composer disponible)
$autoload = __DIR__ . '/../../vendor/autoload.php';

// Opción B: carpeta manual /api/PHPMailer/
$manual   = __DIR__ . '/PHPMailer/src/PHPMailer.php';

if (file_exists($autoload)) {
    require $autoload;
} elseif (file_exists($manual)) {
    require $manual;
    require __DIR__ . '/PHPMailer/src/SMTP.php';
    require __DIR__ . '/PHPMailer/src/Exception.php';
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'PHPMailer no encontrado en el servidor.']);
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ── Enviar correo ─────────────────────────────────────────────────────────────
try {
    $mail = new PHPMailer(true);

    // Servidor SMTP de Gmail
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = GMAIL_USER;
    $mail->Password   = GMAIL_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    // Remitente y destinatario
    $mail->setFrom(GMAIL_USER, MAIL_NAME);
    $mail->addAddress(MAIL_TO, 'ConstruHielo');

    // Reply-To para responder directamente al cliente
    $mail->addReplyTo((string)$email, $nombre);

    // Asunto
    $mail->Subject = "Cotización — {$producto} — ConstruHielo";

    // Cuerpo HTML
    $mail->isHTML(true);
    $mail->Body = "
    <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;color:#1c3348'>
      <div style='background:#102840;padding:24px 32px;'>
        <h2 style='color:#22bbd4;margin:0;font-size:20px;letter-spacing:1px'>
          NUEVA SOLICITUD DE COTIZACIÓN
        </h2>
        <p style='color:#7db8e0;margin:4px 0 0;font-size:13px'>ConstruHielo — Refrigeración Industrial</p>
      </div>

      <div style='padding:32px;background:#f8fbfd;border:1px solid #daeaf3'>
        <table style='width:100%;border-collapse:collapse;font-size:14px'>
          <tr>
            <td style='padding:10px 12px;font-weight:bold;color:#5a7a90;width:140px'>Nombre</td>
            <td style='padding:10px 12px;color:#1c3348'>{$nombre}</td>
          </tr>
          <tr style='background:#eef5f9'>
            <td style='padding:10px 12px;font-weight:bold;color:#5a7a90'>Empresa</td>
            <td style='padding:10px 12px;color:#1c3348'>{$empresa}</td>
          </tr>
          <tr>
            <td style='padding:10px 12px;font-weight:bold;color:#5a7a90'>Correo</td>
            <td style='padding:10px 12px'><a href='mailto:{$email}' style='color:#235a8c'>{$email}</a></td>
          </tr>
          <tr style='background:#eef5f9'>
            <td style='padding:10px 12px;font-weight:bold;color:#5a7a90'>Teléfono</td>
            <td style='padding:10px 12px'><a href='tel:{$telefono}' style='color:#235a8c'>{$telefono}</a></td>
          </tr>
          <tr>
            <td style='padding:10px 12px;font-weight:bold;color:#5a7a90'>Equipo</td>
            <td style='padding:10px 12px;color:#22bbd4;font-weight:bold'>{$producto}</td>
          </tr>
        </table>

        <div style='margin-top:24px;padding:16px;background:#fff;border-left:3px solid #22bbd4'>
          <p style='font-weight:bold;color:#5a7a90;font-size:12px;margin:0 0 8px'>DESCRIPCIÓN DEL PROYECTO</p>
          <p style='color:#1c3348;margin:0;line-height:1.6'>{$mensaje}</p>
        </div>
      </div>

      <div style='padding:16px 32px;background:#102840;text-align:center'>
        <p style='color:#7db8e0;font-size:12px;margin:0'>
          ConstruHielo · Guadalupe Victoria 4, Puebla · construhielo@gmail.com
        </p>
      </div>
    </div>
    ";

    // Versión texto plano (fallback)
    $mail->AltBody = "NUEVA COTIZACIÓN — ConstruHielo\n\n"
        . "Nombre:   {$nombre}\n"
        . "Empresa:  {$empresa}\n"
        . "Correo:   {$email}\n"
        . "Teléfono: {$telefono}\n"
        . "Equipo:   {$producto}\n\n"
        . "Proyecto:\n{$mensaje}";

    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Correo enviado correctamente.']);

} catch (Exception $e) {
    // No exponer detalles internos al cliente
    error_log('[ConstruHielo] Mailer error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al enviar. Intenta por WhatsApp o llámanos directamente.']);
}
