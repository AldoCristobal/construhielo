<?php
/**
 * ConstruHielo — Endpoint de contacto
 *
 * Requiere PHPMailer (incluido en /api/PHPMailer/ o via Composer).
 * Requiere .env.php en este mismo directorio con las credenciales.
 * Ver .env.php.example para la plantilla.
 */

declare(strict_types=1);

// ── Constantes internas ───────────────────────────────────────────────────────
const MAX_REQUESTS = 3;    // Envíos permitidos por IP en la ventana de tiempo
const RATE_WINDOW  = 300;  // Ventana en segundos (5 minutos)
const SMTP_TIMEOUT = 10;   // Segundos antes de abortar conexión SMTP

// Productos válidos — deben coincidir exactamente con el select del formulario
const PRODUCTOS_VALIDOS = [
    'Máquina de Rolito',
    'Tanque de Barras',
    'Cámara Frigorífica',
    'Evaporador Industrial',
    'Mantenimiento / Reparación',
    'Otro / Asesoría',
];

// ── Cargar credenciales desde archivo externo al repo ─────────────────────────
$envFile = __DIR__ . '/.env.php';
if (!file_exists($envFile)) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Configuración del servidor incompleta.']);
    exit;
}
require $envFile;
// Espera que .env.php defina: GMAIL_USER, GMAIL_PASS, MAIL_TO, MAIL_NAME, ALLOWED_ORIGIN

// ── Cabeceras HTTP ────────────────────────────────────────────────────────────
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . ALLOWED_ORIGIN);
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

// ── Rate limiting por IP ──────────────────────────────────────────────────────
function getClientIp(): string {
    // Tomar el primer IP real aunque haya proxies
    $raw = $_SERVER['HTTP_X_FORWARDED_FOR']
        ?? $_SERVER['HTTP_CF_CONNECTING_IP']  // Cloudflare
        ?? $_SERVER['REMOTE_ADDR']
        ?? 'unknown';
    return trim(explode(',', $raw)[0]);
}

function checkRateLimit(string $ip): bool {
    $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ch_rl_' . md5($ip) . '.json';
    $now  = time();
    $data = ['requests' => [], 'blocked_until' => 0];

    if (file_exists($file)) {
        $decoded = json_decode((string) file_get_contents($file), true);
        if (is_array($decoded)) {
            $data = $decoded;
        }
    }

    // Sigue bloqueado
    if (isset($data['blocked_until']) && $data['blocked_until'] > $now) {
        return false;
    }

    // Descartar requests fuera de la ventana
    $data['requests'] = array_values(array_filter(
        $data['requests'] ?? [],
        fn(int $t): bool => ($now - $t) < RATE_WINDOW
    ));

    if (count($data['requests']) >= MAX_REQUESTS) {
        $data['blocked_until'] = $now + RATE_WINDOW;
        file_put_contents($file, json_encode($data), LOCK_EX);
        return false;
    }

    $data['requests'][] = $now;
    file_put_contents($file, json_encode($data), LOCK_EX);
    return true;
}

$clientIp = getClientIp();

if (!checkRateLimit($clientIp)) {
    http_response_code(429);
    echo json_encode([
        'success' => false,
        'message' => 'Demasiados intentos. Espera unos minutos o contáctanos por WhatsApp.',
    ]);
    exit;
}

// ── Leer body JSON ────────────────────────────────────────────────────────────
$raw  = (string) file_get_contents('php://input');
$body = json_decode($raw, true);

if (!is_array($body)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Cuerpo de solicitud inválido.']);
    exit;
}

// ── Honeypot anti-spam ────────────────────────────────────────────────────────
// El campo "website" está oculto en el HTML; los bots lo llenan, los humanos no.
if (!empty($body['website'])) {
    // Simular éxito sin enviar nada (no alertar al bot)
    echo json_encode(['success' => true, 'message' => 'Solicitud recibida.']);
    exit;
}

// ── Sanitización ──────────────────────────────────────────────────────────────
// Elimina tags HTML y codifica caracteres especiales
function clean(mixed $val): string {
    return htmlspecialchars(
        strip_tags(trim((string) ($val ?? ''))),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

// Sanitización extra para campos que van en cabeceras de email (evita header injection)
function cleanHeader(mixed $val): string {
    return preg_replace('/[\r\n\t]+/', ' ', clean($val)) ?? '';
}

$nombre   = cleanHeader($body['nombre']  ?? '');
$empresa  = clean($body['empresa']       ?? '') ?: '—';
$email    = filter_var(trim($body['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$telefono = clean($body['telefono']      ?? '');
$producto = clean($body['producto']      ?? '');
$mensaje  = clean($body['mensaje']       ?? '');

// ── Validación ────────────────────────────────────────────────────────────────
$errors = [];

if (!$nombre)               $errors[] = 'Nombre requerido.';
if (strlen($nombre) > 120)  $errors[] = 'Nombre demasiado largo.';

if (!$email)                $errors[] = 'Correo electrónico inválido.';

if (!$telefono)             $errors[] = 'Teléfono requerido.';
elseif (!preg_match('/^[\d\s\+\-\(\)]{7,20}$/', $telefono))
                            $errors[] = 'Teléfono con formato inválido (solo dígitos, espacios y +/-)';

if (!$producto)             $errors[] = 'Equipo de interés requerido.';
elseif (!in_array($producto, PRODUCTOS_VALIDOS, true))
                            $errors[] = 'Equipo seleccionado no válido.';

if (!$mensaje)              $errors[] = 'Descripción del proyecto requerida.';
elseif (strlen($mensaje) < 10)
                            $errors[] = 'El mensaje es demasiado corto.';
elseif (strlen($mensaje) > 3000)
                            $errors[] = 'El mensaje excede el límite de caracteres.';

if ($errors) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// ── Cargar PHPMailer ──────────────────────────────────────────────────────────
$autoload = __DIR__ . '/../../vendor/autoload.php';
$manual   = __DIR__ . '/PHPMailer/src/PHPMailer.php';

if (file_exists($autoload)) {
    require $autoload;
} elseif (file_exists($manual)) {
    require $manual;
    require __DIR__ . '/PHPMailer/src/SMTP.php';
    require __DIR__ . '/PHPMailer/src/Exception.php';
} else {
    error_log(sprintf('[ConstruHielo] %s — PHPMailer no encontrado', date('Y-m-d H:i:s')));
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de configuración del servidor.']);
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ── Enviar correo ─────────────────────────────────────────────────────────────
try {
    $mail = new PHPMailer(true);

    // SMTP Gmail
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = GMAIL_USER;
    $mail->Password   = GMAIL_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->Timeout    = SMTP_TIMEOUT;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom(GMAIL_USER, MAIL_NAME);
    $mail->addAddress(MAIL_TO, 'Construhielo');
    $mail->addReplyTo((string) $email, $nombre); // $nombre ya sin \r\n

    $mail->Subject = "Cotización — {$producto} — ConstruHielo";

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
    // Log detallado solo en servidor, nunca al cliente
    error_log(sprintf(
        '[ConstruHielo] %s | IP: %s | Email: %s | Producto: %s | Error: %s',
        date('Y-m-d H:i:s'),
        $clientIp,
        is_string($email) ? $email : 'inválido',
        $producto,
        $e->getMessage()
    ));
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al enviar. Intenta por WhatsApp o llámanos directamente.',
    ]);
}
