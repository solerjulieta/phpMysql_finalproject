<?php

namespace App\Validacion;

class Validador
{
    /** @var array Los datos a validar. */
    protected array $dato = [];

    /** @var array Las reglas de validación. */
    protected array $reglas = [];

    /** @var array Los mensajes de error */
    protected array $errores = [];

    public function __construct(array $dato, array $reglas)
    {
        $this->dato = $dato;
        $this->reglas = $reglas;

        $this->validar();
    }

    /**
     * Valida que los datos cumplan con las reglas de validación.
     * 
     * @return void
     */
    protected function validar()
    {
        foreach($this->reglas as $campo => $listaReglas){
            foreach($listaReglas as $regla){
                $this->aplicarRegla($campo, $regla);
            }
        }
    }

    /**
     * Aplica la $regla de validación al $campo indicado. 
     * 
     * @param string $campo
     * @param string $regla
     * @return void
     */
    protected function aplicarRegla(string $campo, string $regla)
    {
        if(str_contains($regla, ':')){
            $reglaDato = explode(':', $regla);
            $metodo = '_' . $reglaDato[0];
            if(!method_exists($this, $metodo)){
                throw new \Exception('La regla de validación "' . $regla . '"no existe.');
            }
            $this->{$metodo}($campo, $reglaDato[1]);
        } else {
            $metodo = '_' . $regla;
            if(!method_exists($this, $metodo)){
                throw new \Exception('La regla de validación "' . $regla . '"no existe.');
            }
            $this->{$metodo}($campo);
        }
    }

    /**
     * Agrega un mensaje de error.
     * 
     * @param string $campo.
     * @param string $mensaje.
     * @return void
     */
    protected function agregarError(string $campo, string $mensaje)
    {
        $this->errores[$campo] = $this->errores[$campo] ?? [];
        $this->errores[$campo][] = $mensaje;
    }

    public function hayErrores(): bool
    {
        return count($this->errores) > 0;
    }

    public function getErrores(): array 
    {
        return $this->errores;
    }

    /**
     * Valida que el $campo tenga un valor no vacío.
     * 
     * @param string $campo.
     * @return void
     */
    protected function _required(string $campo)
    {
        $data = $this->dato[$campo] ?? null;
        if(empty($data)){
            $this->agregarError($campo, ' El campo ' . $campo . ' no puede estar vacío.');
        }
    }

    /**
     * Valida que el $campo sea un valor numérico. 
     * 
     * @param string $campo.
     * @return void
     */
    protected function _numeric(string $campo)
    {
        $data = $this->dato[$campo] ?? null;
        if(!is_numeric($data)){
            $this->agregarError($campo, ' El campo ' . $campo . ' debe ser un valor numérico.');
        }
    }

    /**
     * Valida que el $campo tenga al menos $longitud caracteres.
     *
     * @param string $campo
     * @param int $longitud
     * @return void
     */
    protected function _min(string $campo, int $longitud)
    {
        $data = $this->dato[$campo] ?? null;
        if(strlen($data) < $longitud) {
            $this->agregarError($campo, ' El campo ' . $campo . ' debe tener al menos ' . $longitud . ' caracteres.');
        }
    }

    /**
     * Valida que el $campo tenga formato de email.
     *
     * @param string $campo
     * @return void
     */
    protected function _email(string $campo)
    {
        $data = $this->dato[$campo] ?? null;
        if(!filter_var($data, FILTER_VALIDATE_EMAIL)) {
            $this->agregarError($campo, ' El campo ' . $campo . ' debe tener formato de email. Ej: direccion@dominio.com');
        }
    }
}