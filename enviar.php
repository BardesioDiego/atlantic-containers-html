<?php
// ====================================================================
// Código de Depuración (Puedes borrar estas 3 líneas cuando funcione)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ====================================================================

// 1. INCLUSIÓN DE CREDENCIALES
require 'config.php'; 

// 2. INCLUSIÓN DE LIBRERÍAS DE PHPMailer
require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

// 3. Cargar clases de PHPMailer (Va después de los require)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // DATOS RECIBIDOS: Usamos el método seguro (Operador Ternario) para máxima compatibilidad
    $nombre  = isset($_POST["nombre"]) ? htmlspecialchars(trim($_POST["nombre"])) : '';
    $email   = isset($_POST["email"]) ? htmlspecialchars(trim($_POST["email"])) : '';
    $telefono = isset($_POST["telefono"]) ? htmlspecialchars(trim($_POST["telefono"])) : '';
    $tipo   = isset($_POST["tipoContacto"]) ? htmlspecialchars(trim($_POST["tipoContacto"])) : 'No especificado';
    $empresa = isset($_POST["empresaNombre"]) ? htmlspecialchars(trim($_POST["empresaNombre"])) : '';
    $mensaje = isset($_POST["mensaje"]) ? htmlspecialchars(trim($_POST["mensaje"])) : '';
    
    // Contenido del mensaje (Sin cambios, es correcto)
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
        // Configuración del servidor SMTP (Usando las constantes de config.php)
        $mail->isSMTP();
        $mail->SMTPAuth = true;
    $mail->Host    = SMTP_HOST;
    $mail->SMTPAuth  = true;
    $mail->Username  = SMTP_USER;
    $mail->Password  = SMTP_PASS;

    // CAMBIO CRÍTICO: Usamos TLS para el puerto 587
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
    
    $mail->Port    = SMTP_PORT; // // <-- Constante

        // Remitente y destinatario (Usamos la constante)
        $mail->setFrom(SMTP_USER, 'Formulario Web - Alquiler de Contenedores'); 
        $mail->addAddress(SMTP_USER, 'Alquiler de Contenedores'); 

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

        // Si el envío es exitoso
        echo "<script>alert('✅ Mensaje enviado correctamente. Gracias por contactarnos.'); window.location.href = 'index.html';</script>";

    } catch (Exception $e) {
    // ESTA LÍNEA MUESTRA EL ERROR DETALLADO DE PHPMailer
    error_log("Error al enviar correo: " . $e->getMessage() . " - " . $mail->ErrorInfo); 
    
    // CAMBIA ESTO (LÍNEA ORIGINAL):
    // echo "<script>alert('❌ Error al enviar el mensaje. Por favor, inténtelo de nuevo más tarde.'); window.location.href = 'index.html';</script>";
    
    // POR ESTO (MUESTRA EL ERROR EN PANTALLA):
    echo "<h1>ERROR AL ENVIAR EL MENSAJE:</h1>";
    echo "<h2>" . $mail->ErrorInfo . "</h2>";
    echo "<p>Vuelva a <a href='index.html'>la página principal</a>.</p>";
  }

} else {
    // Si alguien intenta acceder directamente a enviar.php
    header("Location: index.html");
    exit();
}
?>
?>





