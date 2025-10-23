<?php

// Cargar clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // datos recibidos del formulario
    $nombre   = htmlspecialchars($_POST["nombre"] ?? '');
    $email    = htmlspecialchars($_POST["email"] ?? '');
    $telefono = htmlspecialchars($_POST["telefono"] ?? '');
    $tipo     = htmlspecialchars($_POST["tipoContacto"] ?? 'No especificado');
    $empresa  = htmlspecialchars($_POST["empresaNombre"] ?? '');
    $mensaje  = htmlspecialchars($_POST["mensaje"] ?? '');

    // Contenido del mensaje
    $contenido  = "Nuevo mensaje desde el formulario de contacto:\n\n";
    $contenido .= "Nombre: $nombre\n";
    $contenido .= "Email: $email\n";
    $contenido .= "Teléfono: $telefono\n";
    $contenido .= "Tipo de contacto: $tipo\n";
    $contenido .= "Empresa: $empresa\n\n";
    $contenido .= "Mensaje:\n$mensaje\n";

    // Crear una nueva instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP (SiteGround)
        $mail->isSMTP();
        $mail->Host       = 'gtxm1126.siteground.biz';          // Servidor SMTP de SiteGround
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ventas@alquilerdecontenedor.com';  // Tu correo
        $mail->Password   = 'AQUI_TU_CONTRASEÑA_REAL';          // ⚠️ Contraseña del correo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;        // Usar SSL
        $mail->Port       = 465;                                // Puerto SSL

        // Remitente y destinatario
        $mail->setFrom('ventas@alquilerdecontenedor.com', 'Formulario Web - Alquiler de Contenedores');
        $mail->addAddress('ventas@alquilerdecontenedor.com', 'Alquiler de Contenedores');

        // Hacer que las respuestas vayan al correo del usuario
        if (!empty($email)) {
            $mail->addReplyTo($email, $nombre);
        }

        // Contenido del correo
        $mail->isHTML(false);
        $mail->Subject = 'Nuevo mensaje desde el formulario de contacto';
        $mail->Body    = $contenido;

        // Enviar
        $mail->send();

        echo "<script>alert('✅ Mensaje enviado correctamente. Gracias por contactarnos.'); window.location.href = 'index.html';</script>";

    } catch (Exception $e) {
        echo "<script>alert('❌ Error al enviar: {$mail->ErrorInfo}'); window.location.href = 'index.html';</script>";
    }

} else {
    header("Location: index.html");
    exit();
}
?>






