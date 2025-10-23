<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Asegurate de tener PHPMailer cargado
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
        // Configuración del servidor SMTP (Ethereal)
        $mail->isSMTP();
        $mail->Host       = 'smtp.ethereal.email';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'jon.heidenreich41@ethereal.email'; // tu usuario Ethereal
        $mail->Password   = 'N3zz9WEEEYp1P2tSq5';              // tu contraseña Ethereal
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('jon.heidenreich41@ethereal.email', 'Formulario Web');
        $mail->addAddress('jon.heidenreich41@ethereal.email', 'Prueba Ethereal'); // destino de prueba

        // Contenido del correo
        $mail->isHTML(false);
        $mail->Subject = 'Nuevo mensaje desde el formulario de contacto';
        $mail->Body    = $contenido;

        // Enviar
        $mail->send();

        echo "<script>alert('✅ Mensaje enviado correctamente (revisá tu bandeja en Ethereal)'); window.location.href = 'index.html';</script>";

    } catch (Exception $e) {
        echo "<script>alert('❌ Error al enviar: {$mail->ErrorInfo}'); window.location.href = 'index.html';</script>";
    }

} else {
    header("Location: index.html");
    exit();
}
?>





