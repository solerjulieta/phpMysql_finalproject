<?php

namespace App\Mails;

use App\Modelos\Usuario;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class RecuperarContrasenaEmails
{
    protected PHPMailer $mail;
    protected Usuario $usuario;
    protected string $token;

    public function __construct()
    {
        $this->email = new PHPMailer(true);
        $this->email->isSMTP();
        $this->email->Host = 'smtp.mailtrap.io';
        $this->email->SMTPAuth = true;
        $this->email->Port = 2525;
        $this->email->Username = '6408d532331326';
        $this->email->Password = 'cff0c4b5cd786f';
        //$this->email->Username = '';
        //$this->email->Password = '';
        $this->email->CharSet = 'UTF-8';
    }

    /**
     * @return void
     * @throws Exception
     */
    public function enviar()
    {
        try {
            if($this->usuario->getRolId() === 1){
                $link = 'http://localhost/Programacion/Final/soler-julieta_final_/sitio/panel/index.php?s=cambiar-contrasena&token=' . $this->token  . '&usuario=' . $this->usuario->getUsuarioId();
            } else {
                $link = 'http://localhost/Programacion/Final/soler-julieta_final_/sitio/index.php?s=cambiar-contrasena&token=' . $this->token  . '&usuario=' . $this->usuario->getUsuarioId();
            }
            $cuerpo = \file_get_contents(PATH_EMAIL_TEMPLATES . '/recuperar-contrasena.html');
            $cuerpo = str_replace(
                ['@@LINK@@', '@@USUARIO@@'],
                [$link, $this->usuario->getNombre()],
                $cuerpo
            );
    
            $this->email->addAddress($this->usuario->getEmail());
            $this->email->Subject = 'Recuperar Contraseña - Tu mate';
            $this->email->Body = $cuerpo;
            $this->email->setFrom('no-responder@tu-mate.com', 'Tú Mate');
            $this->email->isHTML();
            $this->email->send();
       } catch (\Throwable $th) {
            $filename = "recuperar-contrasena_" . $this->usuario->getEmail() . ".txt"; 
            \file_put_contents( PATH_EMAIL_FAILED . '/' . date('YmdHis') . '_' . $filename, $cuerpo);

            throw $th;
       }
    }

    /**
     * @return Usuario
     */
    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    /**
     * @param Usuario $usuario
     */
    public function setUsuario(Usuario $usuario): void 
    {
        $this->usuario = $usuario;
    }

    /**
     * @return string
     */
    public function getToken(): string 
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void 
    {
        $this->token = $token;
    }
}