<?php

// Mostrar errores para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Obtener los datos del formulario
  $nombre = htmlspecialchars($_POST["nombre"]);
  $email = htmlspecialchars($_POST["email"]);
  $telefono = htmlspecialchars($_POST["telefono"]);
  $tipo = isset($_POST["tipoContacto"]) ? $_POST["tipoContacto"] : 'No especificado';
  $empresa = htmlspecialchars($_POST["empresaNombre"]);
  $mensaje = htmlspecialchars($_POST["mensaje"]);

  // Construir el contenido del mensaje
  $contenido = "
    Nombre: $nombre\n
    Email: $email\n
    Teléfono: $telefono\n
    Tipo de contacto: $tipo\n
    Empresa: $empresa\n
    Mensaje:\n$mensaje
  ";

  // Dirección de destino (tu correo)
$destinatario = "federicobasile@gmail.com";  // 
$asunto = "Nuevo mensaje desde el formulario de contacto";

// Encabezados del correo
$headers = "From: $nombre <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Enviar correo
if (mail($destinatario, $asunto, $contenido, $headers)) {
  echo "<script>alert('¡Mensaje enviado correctamente!'); window.location.href = 'index.html';</script>";
} else {
  echo "<script>alert('Hubo un error al enviar el mensaje.'); window.location.href = 'index.html';</script>";
}


  // Alerta y redirección
  echo "<script>alert('¡Mensaje simulado! Formulario funcionando.'); window.location.href = 'index.html';</script>";
} else {
  // Si alguien accede directamente al PHP sin enviar datos
  header("Location: index.html");
  exit();
}

