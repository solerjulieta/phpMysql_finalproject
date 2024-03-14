<?php

namespace App\Hash;

class URLToken
{
    public function generar($length = 32): string
    {
        $token = openssl_random_pseudo_bytes($length);
        $token = bin2hex($token);
        return $token;
    }
}