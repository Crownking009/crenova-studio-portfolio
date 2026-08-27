<?php
declare(strict_types=1);

session_name('crenova_session');
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')]);
session_start();

require __DIR__ . '/../config/config.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/models.php';

function view(string $name, array $data = []): void {
    extract($data, EXTR_SKIP);
    $file = __DIR__ . '/../views/' . $name . '.php';
    if (!is_file($file)) { http_response_code(404); $file = __DIR__ . '/../views/404.php'; }
    require $file;
}
