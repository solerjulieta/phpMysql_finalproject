<?php

namespace App\Auth;

use DateTime;
use App\DB\DBConexion;
use App\Hash\URLToken;
use App\Modelos\Usuario;
use App\Mails\RecuperarContrasenaEmails;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PDO;

class RecuperarContrasena
{
    protected Usuario $usuario;
    
    /** @var string El token de recuperación de la contraseña. */
    protected string $token;

    /** @var DateTime La fecha de expiración del token. */
    protected DateTime $expiracion;

    /**
     * @param Usuario $usuario
     * @return void
     * @throws Exception
     */
    public function enviarEmail(Usuario $usuario)
    {
        $this->usuario = $usuario;
        $this->token = $this->generarToken();
        $this->almacenarToken();
        $this->ejecutarEnvioDeEmail();
    }

    /**
     * Retorna true si existe el registro para el usuario y token indicados,
     * de lo contrario retorna false.
     * 
     * @return bool
     * @throws \Exception
     */
    public function esValido(): bool
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM recuperar_contrasena
                  WHERE usuario_id = ?
                  AND token = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$this->usuario->getUsuarioId(), $this->token]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$data){
            return false;
        }

        $this->expiracion = new DateTime($data['expiracion']);

        return true;
    }

    /**
     * Retorna false si el token para el usuario está en vigencia,
     * de lo contrario retorna true. 
     * 
     * @return bool
     */
    public function expirado(): bool
    {
       $fechaActual = new DateTime();

       return $fechaActual >= $this->expiracion;
    }

    /**
     * Actualiza la contraseña del usuario, y elimina el token asociado.
     * 
     * @param string $contrasena
     * @return void
     */
    public function actualizarContrasena(string $contrasena)
    {
        $this->usuario->editarContrasena(\password_hash($contrasena, PASSWORD_DEFAULT));
        $this->eliminarToken();
    }

    /**
     * Genera un token criptográficamente seguro. 
     * 
     * @return string
     */
    protected function generarToken(): string
    {  
        return (new URLToken())->generar();
    }

    protected function almacenarToken()
    {
        if($this->tokenExiste()){
            $this->actualizarToken();
        }else{
            $this->guardarToken();
        }
    }

    /**
     * @return false|mixed
     */
    protected function tokenExiste()
    {
        $db = DBConexion::getConexion();
        $query = "SELECT * FROM recuperar_contrasena
                  WHERE usuario_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$this->usuario->getUsuarioId()]);

        $fila = $stmt->fetch();

        if(!$fila) return false;

        return $fila;
    }

    /**
     * @return void
     */
    protected function actualizarToken()
    {
        $db = DBConexion::getConexion();
        $query = "UPDATE recuperar_contrasena 
                  SET token = :token, 
                      expiracion = :expiracion
                  WHERE usuario_id = :usuario_id";
        $stmt = $db->prepare($query);

        $fecha = new \DateTime();
        $fecha->modify('+1 hour');

        $stmt->execute([
             'usuario_id' => $this->usuario->getUsuarioId(),
             'token'      => $this->token,
             'expiracion' => $fecha->format('Y-m-d H:i:s'),
        ]);        
    }

    /**
     * Graba en la tabla de recuperar_contrasena el token con el usuario.
     * 
     * @return void
     * @throws PDOException
     */
    protected function guardarToken()
    {
        $db = DBConexion::getConexion();
        $query = "INSERT INTO recuperar_contrasena (usuario_id, token, expiracion)
                  VALUES (:usuario_id, :token, :expiracion)";
        $stmt = $db->prepare($query);

        $fecha = new \DateTime();
        $fecha->modify('+1 hour');

        $stmt->execute([
             'usuario_id' => $this->usuario->getUsuarioId(),
             'token'      => $this->token,
             'expiracion' => $fecha->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @return PHPMailer
     */
    protected function getMailInstance(): PHPMailer
    {
        $phpmailer = new PHPMailer(true);
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '';
        $phpmailer->Password = '';
        $phpmailer->CharSet = 'UTF-8';

        return $phpmailer;
    }

    /**
     * Envía el mail de recuperación. 
     * 
     * @return void
     * @throws Exception
     */
    protected function ejecutarEnvioDeEmail()
    {
        $email = new RecuperarContrasenaEmails();
        $email->setUsuario($this->usuario);
        $email->setToken($this->token);
        $email->enviar();
    }

    /**
     * Envía el mail de recuperación. 
     * Sin PHPMailer y sin testeo con servicios como Mailtrap.
     * 
     * @return void
     */
    protected function ejecutarEnvioDeEmailBasica()
    {
        $destinatario = $this->usuario->getEmail();
        $asunto = 'Recuperar Contraseña - Tu mate';
        $link = 'http://localhost/Programacion/Parcial%202/soler-julieta/sitio/panel/index.php?s=cambiar-contrasena&token=' . $this->token  . '&usuario=' . $this->usuario->getUsuarioId();
        $cuerpo = \file_get_contents(PATH_EMAIL_TEMPLATES . '/recuperar-contrasena.html');
        $cuerpo = str_replace('@@LINK@@', $link, $cuerpo);
        $headers  = 'From: no-responder@tu-mate.com' . '\r\n';
        $headers .= 'MIME-Version: 1.0' . '\r\n';
        $headers .= 'Content-Type: text/hmtl; charset=utf-8' . '\r\n';
 
        if(!mail($destinatario, $asunto, $cuerpo, $headers)){
            $filename = "recuperar-contrasena_" . $this->usuario->getEmail() . ".txt"; 
            \file_put_contents( PATH_EMAIL_FAILED . '/' . date('YmdHis') . '_' . $filename, $cuerpo);
        }        
    }

    /**
     * Elimina el token asociado al usuario.
     * 
     * @return void
     * @throws \PDOException
     */
    protected function eliminarToken()
    {
        $db = DBConexion::getConexion();
        $query = "DELETE FROM recuperar_contrasena
                  WHERE usuario_id = ?
                  AND token = ?";
        $db->prepare($query)->execute([$this->usuario->getUsuarioId(), $this->token]);
    }

    /**
     * Busca y asigna el usuario para el $id provisto.
     * 
     * @param int $id
     * @return void
     */
    public function setUsuarioPorId(int $id)
    {
        $this->usuario = (new Usuario())->traerPorId($id);
    }

    public function setToken(string $token)
    {
        $this->token = $token;
    }
}

