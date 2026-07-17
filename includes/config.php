<?php
$root = str_replace('\\', '/', realpath(dirname(__DIR__)));
$docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));

if (stripos($root, $docRoot) === 0) {
    define('BASE_URL', substr($root, strlen($docRoot)));
} else {
    define('BASE_URL', '');
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'courierms');
?>
