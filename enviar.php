<?php
// ====================================================================
// Mostrar errores PHP en el navegador (opcional para producción)
// Se recomienda DESACTIVAR en producción para mayor seguridad.
ini_set('display_errors', 0); // Desactivar la visualización de errores
ini_set('display_startup_errors', 0);
error_reporting(0); // No reportar errores al usuario
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

    // Datos recibidos (Usando el operador de fusión de null ?? para seguridad)
    $nombre     = htmlspecialchars(trim($_POST["nombre"] ?? ''));
    $email      = htmlspecialchars(trim($_POST["email"] ?? ''));
    $telefono   = htmlspecialchars(trim($_POST["telefono"] ?? ''));
    $tipo       = htmlspecialchars(trim($_POST["tipoContacto"] ?? 'No especificado'));
    $empresa    = htmlspecialchars(trim($_POST["empresaNombre"] ?? ''));
    $mensaje    = htmlspecialchars(trim($_POST["mensaje"] ?? ''));

    // Cuerpo del mensaje (Formato de texto plano)
    $contenido  = "Nuevo mensaje desde el formulario de contacto:\n\n";
    $contenido .= "Nombre: $nombre\n";
    $contenido .= "Email: $email\n";
    $contenido .= "Teléfono: $telefono\n";
    $contenido .= "Tipo de contacto: $tipo\n";
    $contenido .= "Empresa: $empresa\n\n";
    $contenido .= "Mensaje:\n$mensaje\n";

    // Crear PHPMailer (el 'true' habilita excepciones para manejo de errores)
    $mail = new PHPMailer(true);

    try {
        // --- CONFIGURACIÓN SMTP (Opción Hostinger/465/SSL) ---
        $mail->isSMTP();
        $mail->SMTPAuth   = true;
        $mail->Host       = SMTP_HOST; // smtp.hostinger.com
        $mail->Username   = SMTP_USER; // ventas@...
        $mail->Password   = SMTP_PASS; // La clave
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL/TLS
        $mail->Port       = 465;
        $mail->Timeout    = 120; // Tiempo de espera aumentado a 120s

        // --------------------------------------------------------------------------------
        // ** NOTA:** Si el envío sigue fallando, cambie las 3 líneas de arriba por:
        // $mail->isMail();
        // --------------------------------------------------------------------------------

        // Remitente y destinatario
        $mail->setFrom(SMTP_USER, 'Formulario Web - Atlantic Containers');
        $mail->addAddress(EMAIL_TO); // Destinatario final

        // Responder al remitente (Si el usuario proporcionó un email)
        if (!empty($email)) {
            $mail->addReplyTo($email, $nombre);
        }

        // Contenido del correo
        $mail->isHTML(false); // Envío en formato de texto plano
        $mail->Subject = 'Nuevo mensaje desde el formulario de contacto';
        $mail->Body    = $contenido;

        // Enviar mensaje
        $mail->send();

        // Éxito: Mensaje de alerta y redirección
        echo "<script>alert('✅ Mensaje enviado correctamente. Gracias por contactarnos.'); window.location.href = 'index.html';</script>";

    } catch (Exception $e) {
        echo '<!DOCTYPE html>
              <html lang="es">
              <head>
                  <meta charset="UTF-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1.0">
                  <title>Error de Envío</title>
                  <style>
                      body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                      h1 { color: #cc0000; }
                      h2 { color: #333; font-size: 1em; }
                      a { color: #007bff; text-decoration: none; }
                  </style>
              </head>
              <body>';

        echo "<h1>ERROR AL ENVIAR EL MENSAJE:</h1>";
        echo "<h2>" . htmlspecialchars($mail->ErrorInfo) . "</h2>";
        echo "<p><a href='index.html'>Volver a la página principal</a></p>";

        echo '</body></html>';
        // -----------------------------------------------------------------
    }

} else {
    // Si acceden directamente al script, redirigir a la página principal
    header("Location: index.html");
    exit();
}
?>






