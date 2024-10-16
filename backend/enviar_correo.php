<?php
// Incluir los archivos necesarios de PHPMailer
require '../lib/PHPmailer/src/PHPMailer.php';
require '../lib/PHPmailer/src/SMTP.php';
require '../lib/PHPmailer/src/Exception.php';

// Usar los namespaces correspondientes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true); // Crear una nueva instancia de PHPMailer

try {
    // Configuración del servidor SMTP de Gmail
    $mail->isSMTP();                                    // Usar SMTP
    $mail->Host = 'smtp.gmail.com';                     // Servidor SMTP de Gmail
    $mail->SMTPAuth = true;                             // Habilitar autenticación SMTP
    $mail->Username = 'cristianpalacios935@gmail.com';              // Tu correo de Gmail
    $mail->Password = 'slai ajhh hgcv pyct';                   // Contraseña de tu correo de Gmail
    $mail->SMTPSecure = 'tls';                          // Habilitar encriptación TLS
    $mail->Port = 587;                                  // Puerto SMTP para TLS

    // Configuración del remitente y destinatario
    $mail->setFrom('cristianpalacios935@gmail.com', 'Tu nombre');   // Remitente
    $mail->addAddress('assasincpc935@gmail.com', 'Destinatario'); // Destinatario

    // Configuración del contenido del correo
    $mail->isHTML(true);                                // Formato HTML
    $mail->Subject = 'Asunto del correo';               // Asunto
    $mail->Body    = 'Este es el contenido del mensaje en HTML';  // Cuerpo del mensaje en HTML
    $mail->AltBody = 'Este es el cuerpo alternativo del mensaje'; // Cuerpo alternativo para clientes que no soportan HTML

    // Enviar el correo
    $mail->send();
    echo 'El mensaje ha sido enviado correctamente';
} catch (Exception $e) {
    // En caso de error
    echo "El mensaje no se pudo enviar. Error de PHPMailer: {$mail->ErrorInfo}";
}
?>
