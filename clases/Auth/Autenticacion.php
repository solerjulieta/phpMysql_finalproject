<?php

namespace App\Auth;

use App\Modelos\Usuario;

class Autenticacion
{
    public function iniciarSesion(string $email, string $contrasena): bool
    {   
        //Se corrobora si existe un usuario con este mail.
        $usuario = (new Usuario)->traerPorEmail($email);

        if(!$usuario){
            return false;
        } 

        // Comprueba si la contraseña es correcta.
        if(!password_verify($contrasena, $usuario->getContrasena())){
            return false;
        } 
        
        // Autentica al usuario
        $this->autenticar($usuario); 
        return true;
    }

    public function autenticar(Usuario $usuario)
    {
      $_SESSION['usuario_id'] = $usuario->getUsuarioId();
      $_SESSION['rol_id'] = $usuario->getRolId();
    }

    public function cerrarSesion()
    {
        unset($_SESSION['usuario_id'], $_SESSION['rol_id']);
    }

    public function estaAutenticado(): bool 
    {
        return isset($_SESSION['usuario_id']);
    }

    public function esAdmin(): bool 
    {
        return $_SESSION['rol_id'] === 1;
    }

    public function getId(): ?int
    {
        return $this->estaAutenticado() ? $_SESSION['usuario_id'] : null;
    }

    public function getUsuario(): ?Usuario 
    {
        return $this->estaAutenticado() ? (new Usuario())->traerPorId($_SESSION['usuario_id']) : null;
    }
}