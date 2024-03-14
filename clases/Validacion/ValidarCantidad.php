<?php

namespace App\Validacion;

/**
 * Valida datos ingresados de la cantidad del item del carrito.
 */
class ValidarCantidad
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

    public function hayError(): bool /** Error */
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
        if(empty($this->dato['cantidad'])){
            $this->errores['cantidad'] = 'Debés ingresar una cantidad del producto.';
        }
        if($this->dato['cantidad'] > 3){
            $this->errores['cantidad'] = 'Supera el stock disponible.';
        }
        if($this->dato['cantidad'] >= 0){
            $this->errores['cantidad'] = 'Debés ingresar una cantidad.';
        }
    }
}