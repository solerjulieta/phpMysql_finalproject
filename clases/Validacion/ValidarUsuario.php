<?php

namespace app\Validacion;

/**
 * Valida los datos ingresados del usuario.
 */
class ValidarUsuario
{
    /** @var array Los datos a validar */  
    protected $dato = [];
    /** @var array Los errores encontrados */
    protected $errores = [];

    /**
     * @param array $dato Los datos a validar.
     */

    public function __construct(array $dato)
    {
        $this->dato = $dato;
        $this->validar();
    }

    public function hayError(): bool
    {
        return !empty($this->errores);
    }

   /**
    * @return array
    */

    public function getError(): array
    {
       return $this->errores;
    }

    protected function validar()
    {
        if(empty($this->dato['email'])){
            $this->errores['email'] = 'Debés ingresar un email.';
        }

        if(empty($this->dato['contrasena'])){
            $this->errores['contrasena'] = 'Debés ingresar una contraseña.';
        } else if(strlen($this->dato['contrasena']) < 6){
            $this->errores['contrasena'] = 'Tu contraseña debe tener al menos 6 caracteres.';
        }
    }
}