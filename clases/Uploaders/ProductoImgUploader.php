<?php

namespace App\Uploaders;

use App\Helpers\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ProductoImgUploader
{
    /**
     * Sube la imagen con tamaños necesarios para el producto.
     * Retonar el nombre de la imagen "slugficado" con el que se guardó.
     * 
     * @param array $file
     * @param string $filename
     * @return string
     */
    public static function upload(array $file, string $filename): string 
    {
        $filename = Str::slugifyFilename($filename);
        $imgGrande = Image::make($file['tmp_name']);
        $imgChica  = Image::make($file['tmp_name']);

        $imgGrande 
            ->fit(1638, 2048)
            ->save(\PATH_IMG . '/' . $filename);
        $imgChica
            ->fit(2560, 2560)
            ->save(\PATH_IMG . '/mobile-' . $filename);

        return $filename;
    }
}