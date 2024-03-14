<?php

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

const PATH_IMG = __DIR__ . '/../imagenes';
const PATH_EMAIL_TEMPLATES = __DIR__ . '/../emails-plantilla';
const PATH_EMAIL_FAILED = __DIR__ . '/../emails-fallidos';