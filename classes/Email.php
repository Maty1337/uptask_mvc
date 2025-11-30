<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email  = $email;
        $this->nombre = $nombre;
        $this->token  = $token;
    }

    public function enviarConfirmacion(): bool
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración SMTP (Mailtrap)
            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth   = true;
            $mail->Port       = 2525;
            $mail->Username   = '2944b706bb8307';
            $mail->Password   = '5d9c0782a4cac4';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // Remitente y destinatario
            $mail->setFrom('2944b706bb8307@sandbox.mailtrap.io', 'UpTask');
            $mail->addAddress($this->email, $this->nombre);

            // Asunto
            $mail->Subject = 'Confirma tu cuenta en UpTask';

            // Contenido del email
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            $url = 'http://localhost:3000/confirmar?token=' . urlencode($this->token);

            $contenido  = '<html>';
            $contenido .= '<p><strong>Hola ' . htmlspecialchars($this->nombre) . '</strong>,</p>';
            $contenido .= '<p>Has creado tu cuenta en <strong>UpTask</strong>. Solo debes confirmarla haciendo clic en el siguiente enlace:</p>';
            $contenido .= '<p><a href="' . $url . '">Confirmar Cuenta</a></p>';
            $contenido .= '<p>Si tú no creaste esta cuenta, puedes ignorar este mensaje.</p>';
            $contenido .= '</html>';

            $mail->Body    = $contenido;
            $mail->AltBody = "Hola {$this->nombre}. Confirmá tu cuenta aquí: {$url}";

            // Envío
            return $mail->send();
        } catch (Exception $e) {
            error_log('Error al enviar correo: ' . $mail->ErrorInfo);
            return false;
        }
    }

    public function enviarInstrucciones(): bool
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración SMTP (Mailtrap)
            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth   = true;
            $mail->Port       = 2525;
            $mail->Username   = '2944b706bb8307';
            $mail->Password   = '5d9c0782a4cac4';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            // Remitente y destinatario
            $mail->setFrom('2944b706bb8307@sandbox.mailtrap.io', 'UpTask');
            $mail->addAddress($this->email, $this->nombre); 

            // Asunto
            $mail->Subject = 'Reestablece tu password en UpTask';

            // Contenido del email
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $url = 'http://localhost:3000/restablecer?token=' . urlencode($this->token);
            $contenido  = '<html>';
            $contenido .= '<p><strong>Hola ' . htmlspecialchars($this->nombre) . '</strong>,</p>';
            $contenido .= '<p>Has solicitado reestablecer tu password en <strong>UpTask</strong>. Sigue el siguiente enlace para hacerlo:</p>';
            $contenido .= '<p><a href="' . $url . '">Reestablecer Password</a></p>';
            $contenido .= '<p>Si tú no solicitaste este cambio, puedes ignorar este mensaje.</p>';
            $contenido .= '</html>';

            $mail->Body    = $contenido;
        
            // Envío
            return $mail->send();
        } catch (Exception $e) {
            error_log('Error al enviar correo: ' . $mail->ErrorInfo);
            return false;        
        } 
    }
}