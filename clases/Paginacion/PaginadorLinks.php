<?php

namespace App\Paginacion;

class PaginadorLinks
{
    public function __construct(Paginador $paginador)
    {
        require_once __DIR__ . '/../../layout/paginador.php';
    }
}