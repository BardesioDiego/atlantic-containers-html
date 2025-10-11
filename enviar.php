<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitización de datos
  $nombre   = htmlspecialchars($_POST["nombre"]);
  $email    = htmlspecialchars($_POST["email"]);
  $telefono = htmlspecialchars($_POST["telefono"]);
  $tipo     = isset($_POST["tipoContacto"]) ? htmlspecialchars($_POST["tipoContacto"]) : 'No especificado';
  $empresa  = htmlspecialchars($_POST["empresaNombre"]);
  $mensaje  = htmlspecialchars($_POST["mensaje"]);

  // Cuerpo del mensaje
  $contenido = "Nuevo mensaje desde el formulario de contacto:\n\n";
  $contenido .= "Nombre: $nombre\n";
  $contenido .= "Email: $email\n";
  $contenido .= "Teléfono: $telefono\n";
  $contenido .= "Tipo de contacto: $tipo\n";
  $contenido .= "Empresa: $empresa\n\n";
  $contenido .= "Mensaje:\n$mensaje\n";

  // 📬 Destinatario (podés poner el mismo o varios separados por coma)
  $destinatario = "federicobasile@gmail.com";

  // Asunto
  $asunto = "Nuevo mensaje desde el formulario de contacto";

  // 🧩 Encabezados configurados correctamente para SiteGround
  $headers  = "From: Formulario Web <ventas@alquilerdecontenedor.com>\r\n";  // <-- este es el tuyo ✅
  $headers .= "Reply-To: $email\r\n";  // así podés responder directamente al remitente
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

  // Envío
  if (mail($destinatario, $asunto, $contenido, $headers)) {
    echo "<script>alert('¡Mensaje enviado correctamente!'); window.location.href = 'index.html';</script>";
  } else {
    echo "<script>alert('Hubo un error al enviar el mensaje.'); window.location.href = 'index.html';</script>";
  }

} else {
  header("Location: index.html");
  exit();
}
?>



