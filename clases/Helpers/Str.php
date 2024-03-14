<?php

namespace App\Helpers;

use Cocur\Slugify\Slugify;

/**
 * Contiene métodos útiles para manejo de strings.
 */
class Str 
{
    /**
     * Retorna una versión "slugificada" del nombre del archivo provisto.
     * 
     * @param string $filename
     * @return string
     */
    public static function slugifyFilename(string $filename): string 
    {
        $filenameParts = explode('.', $filename);
        $extension = array_pop($filenameParts);
        $filename = implode('.', $filenameParts);

        $slugifier = new Slugify();
        return $slugifier
                 ->slugify($filename) . "." . $extension;
    }
}