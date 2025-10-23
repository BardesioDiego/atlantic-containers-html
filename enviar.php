<?php
// ====================================================================
// Desactivar la visualización de errores para mayor seguridad en producción
ini_set('display_errors', 0); 
ini_set('display_startup_errors', 0);
error_reporting(0); 
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

    try {
        // ------------------------------------------------------------------
        // MÉTODO DEFINITIVO: USAR EL ENVÍO NATIVO DEL SERVIDOR (BYPASS SMTP)
        $mail->isMail();
        // ------------------------------------------------------------------
        
        // **NOTA IMPORTANTE: Se comentan todas las líneas SMTP para este método:**
        // $mail->isSMTP();
        // $mail->SMTPAuth = true;
        // $mail->Host = SMTP_HOST;
        // $mail->Username = SMTP_USER;
        // $mail->Password = SMTP_PASS;
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        // $mail->Port = 465;
        // $mail->Timeout = 120;


        // Remitente y destinatario
        // ATENCIÓN: La dirección FROM debe ser una cuenta válida en tu dominio
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
        // Error en el envío (Se muestra el error de PHPMailer)
        error_log("PHPMailer Error Fatal: " . $mail->ErrorInfo); 
        
        // Código para evitar Quirks Mode
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
    }

} else {
    header("Location: index.html");
    exit();
}
?>






