<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        //Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '643b7a52e31b26';
        $mail->Password = '3e2ea941634264';
        //tls transport layer security, ssl es su antecesor
        //socket security layer 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 2525;

        //Configurar el contenido de mail 
        //Quien envia el correo, evitar bandeja de no deseados 
        $mail->setFrom('cuentas@appsalon.com');
        //A que correo va a llegar el email
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        //Asunto
        $mail->Subject = 'Confirma tu cuenta';

        //Habilitar HTML o sin formato
        $mail->isHTML(true);
        //Para mostrar los acentos de manera correcta 
        $mail->CharSet = 'UTF-8';

        //Definir el contenido 
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . " </strong> Has creado una cuenta en App Salón, debes confirmarla utilizando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'>Enlace</a> </p>";
        $contenido .= "<p> Si no solicitaste esta cuenta, puedes ignorar el mensaje </p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;
        //Alternativo cuando no soporta html
        $mail->AltBody = 'texto alternativo';

        $mail->send();
    }

    public function enviarRecuperar()
    {
        //Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '643b7a52e31b26';
        $mail->Password = '3e2ea941634264';
        //tls transport layer security, ssl es su antecesor
        //socket security layer 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 2525;

        //Configurar el contenido de mail 
        //Quien envia el correo, evitar bandeja de no deseados 
        $mail->setFrom('cuentas@appsalon.com');
        //A que correo va a llegar el email
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        //Asunto
        $mail->Subject = 'Restablecer contraseña';

        //Habilitar HTML o sin formato
        $mail->isHTML(true);
        //Para mostrar los acentos de manera correcta 
        $mail->CharSet = 'UTF-8';

        //Definir el contenido 
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . " </strong> Has solicitado un cambio de contraseña en tu cuenta de App Salón, utiliza el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'>Enlace</a> </p>";
        $contenido .= "<p> Si no solicitaste el cambio de contraseña, puedes ignorar el mensaje </p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;
        //Alternativo cuando no soporta html
        $mail->AltBody = 'texto alternativo';

        $mail->send();
    }
}
