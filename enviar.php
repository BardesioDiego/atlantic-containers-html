<?php

// 1. Incluir el archivo de configuración con las credenciales
require 'config.php'; 

// Cargar clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // datos recibidos del formulario (Sin cambios, esto es correcto)
 $nombre  = htmlspecialchars($_POST["nombre"] ?? '');
  $email  = htmlspecialchars($_POST["email"] ?? '');
  $telefono = htmlspecialchars($_POST["telefono"] ?? '');
  $tipo   = htmlspecialchars($_POST["tipoContacto"] ?? 'No especificado');
  $empresa = htmlspecialchars($_POST["empresaNombre"] ?? '');
  $mensaje = htmlspecialchars($_POST["mensaje"] ?? '');

  // Contenido del mensaje (Sin cambios, esto es correcto)
  $contenido = "Nuevo mensaje desde el formulario de contacto:\n\n";
  $contenido .= "Nombre: $nombre\n";
  $contenido .= "Email: $email\n";
  $contenido .= "Teléfono: $telefono\n";
  $contenido .= "Tipo de contacto: $tipo\n";
  $contenido .= "Empresa: $empresa\n\n";
  $contenido .= "Mensaje:\n$mensaje\n";

  // Crear una nueva instancia de PHPMailer
  $mail = new PHPMailer(true);

  try {
    // Configuración del servidor SMTP (Usando las constantes)
    $mail->isSMTP();
    $mail->Host    = SMTP_HOST; // 
    $mail->SMTPAuth  = true;
    $mail->Username  = SMTP_USER; // 
    $mail->Password  = SMTP_PASS; // 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port    = SMTP_PORT; //  (asumiendo que la definiste)

    // Remitente y destinatario (Puede usar la constante SMTP_USER para el remitente)
    $mail->setFrom(SMTP_USER, 'Formulario Web - Alquiler de Contenedores'); 
    $mail->addAddress(SMTP_USER, 'Alquiler de Contenedores'); 

    // Hacer que las respuestas vayan al correo del usuario
    if (!empty($email)) {
      $mail->addReplyTo($email, $nombre);
    }

    // Contenido del correo
    $mail->isHTML(false);
    $mail->Subject = 'Nuevo mensaje desde el formulario de contacto';
    $mail->Body  = $contenido;

    // Enviar
    $mail->send();

    echo "<script>alert('✅ Mensaje enviado correctamente. Gracias por contactarnos.'); window.location.href = 'index.html';</script>";

  } catch (Exception $e) {
    // NOTA DE SEGURIDAD: En producción, no muestres $mail->ErrorInfo al usuario final, solo un mensaje genérico.
    error_log("Error al enviar correo: " . $e->getMessage() . " - " . $mail->ErrorInfo);
    echo "<script>alert('❌ Error al enviar el mensaje. Por favor, inténtelo de nuevo más tarde.'); window.location.href = 'index.html';</script>";
  }

} else {
  header("Location: index.html");
  exit();
}
?>





