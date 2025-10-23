<?php
// ====================================================================
// Código de Depuración - MUESTRA ERRORES PHP EN EL NAVEGADOR
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ====================================================================

// 1. INCLUSIÓN DE CREDENCIALES Y LIBRERÍAS
require 'config.php'; 
require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

// 2. Cargar clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // DATOS RECIBIDOS DEL FORMULARIO
    $nombre     = isset($_POST["nombre"]) ? htmlspecialchars(trim($_POST["nombre"])) : '';
    $email      = isset($_POST["email"]) ? htmlspecialchars(trim($_POST["email"])) : '';
    $telefono   = isset($_POST["telefono"]) ? htmlspecialchars(trim($_POST["telefono"])) : '';
    $tipo       = isset($_POST["tipoContacto"]) ? htmlspecialchars(trim($_POST["tipoContacto"])) : 'No especificado';
    $empresa    = isset($_POST["empresaNombre"]) ? htmlspecialchars(trim($_POST["empresaNombre"])) : '';
    $mensaje    = isset($_POST["mensaje"]) ? htmlspecialchars(trim($_POST["mensaje"])) : '';
    
    // Contenido del mensaje
    $contenido  = "Nuevo mensaje desde el formulario de contacto:\n\n";
    $contenido .= "Nombre: $nombre\n";
    $contenido .= "Email: $email\n";
    $contenido .= "Teléfono:$telefono\n"; // 
    $contenido .= "Tipo de contacto: $tipo\n";
    $contenido .= "Empresa: $empresa\n\n";
    $contenido .= "Mensaje:\n$mensaje\n";

    // Crear una nueva instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        
        $mail->SMTPDebug = 0; 

        // Configuración del servidor SMTP (Usando las constantes de config.php)
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        
        $mail->Host     = SMTP_HOST;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;

       
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port     = SMTP_PORT; 
        
        $mail->Timeout = 60; 
        $mail->send();

        // Remitente y Destinatario
        $mail->setFrom(SMTP_USER, 'Formulario Web - Atlantic Containers'); 
    var_dump(file_exists('config.php'));
require 'config.php';
var_dump(isset($destinatario), $destinatario);
die();

        $mail->addAddress(EMAIL_TO); 

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
        // Ocultamos la depuración y redirigimos
        echo "<script>alert('✅ Mensaje enviado correctamente. Gracias por contactarnos.'); window.location.href = 'index.html';</script>";

    } catch (Exception $e) {
        // Bloque de error: Muestra el error detallado de PHPMailer
        error_log("Error al enviar correo: " . $e->getMessage() . " - " . $mail->ErrorInfo); 
        
        echo "<h1>ERROR AL ENVIAR EL MENSAJE:</h1>";
        echo "<h2>" . $mail->ErrorInfo . "</h2>";
        echo "<p>Vuelva a <a href='index.html'>la página principal</a>.</p>";
    }

} else {
    // Si alguien intenta acceder directamente a enviar.php
    header("Location: index.html");
    exit();
}





