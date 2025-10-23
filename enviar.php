<?php
// ====================================================================
// Mostrar errores PHP en el navegador (útil para depuración)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ====================================================================

// 1. Incluir configuración y librerías
require 'config.php'; 
require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

// Cargar clases
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 2. Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Datos recibidos
    $nombre     = htmlspecialchars(trim($_POST["nombre"] ?? ''));
    $email      = htmlspecialchars(trim($_POST["email"] ?? ''));
    $telefono   = htmlspecialchars(trim($_POST["telefono"] ?? ''));
    $tipo       = htmlspecialchars(trim($_POST["tipoContacto"] ?? 'No especificado'));
    $empresa    = htmlspecialchars(trim($_POST["empresaNombre"] ?? ''));
    $mensaje    = htmlspecialchars(trim($_POST["mensaje"] ?? ''));

    // Cuerpo del mensaje
    $contenido  = "Nuevo mensaje desde el formulario de contacto:\n\n";
    $contenido .= "Nombre: $nombre\n";
    $contenido .= "Email: $email\n";
    $contenido .= "Teléfono: $telefono\n";
    $contenido .= "Tipo de contacto: $tipo\n";
    $contenido .= "Empresa: $empresa\n\n";
    $contenido .= "Mensaje:\n$mensaje\n";

    // Crear PHPMailer
$mail = new PHPMailer(true);


    // Crear PHPMailer
    $mail = new PHPMailer(true);

    / ----------------------------------------------------
// AÑADE ESTO PARA VER EL DIÁLOGO COMPLETO CON EL SERVIDOR
$mail->SMTPDebug = 4; // 4: Muestra toda la conversación SMTP (DEBUG)
$mail->Debugoutput = 'html'; // Muestra el output en HTML para el navegador
// ----------------------------------------------------

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->SMTPAuth   = true;
        $mail->Host       = SMTP_HOST;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->Timeout    = 60;

        // Remitente y destinatario
        $mail->setFrom(SMTP_USER, 'Formulario Web - Atlantic Containers');
        $mail->addAddress(EMAIL_TO);

        // Responder al remitente
        if (!empty($email)) {
            $mail->addReplyTo($email, $nombre);
        }

        // Contenido del correo
        $mail->isHTML(false);
        $mail->Subject = 'Nuevo mensaje desde el formulario de contacto';
        $mail->Body    = $contenido;

        // Enviar mensaje
        $mail->send();

        // Éxito
        echo "<script>alert('✅ Mensaje enviado correctamente. Gracias por contactarnos.'); window.location.href = 'index.html';</script>";

    } catch (Exception $e) {
        // Error en el envío
        echo "<h1>ERROR AL ENVIAR EL MENSAJE:</h1>";
        echo "<h2>" . htmlspecialchars($mail->ErrorInfo) . "</h2>";
        echo "<p><a href='index.html'>Volver a la página principal</a></p>";
    }

} else {
    header("Location: index.html");
    exit();
}
?>






