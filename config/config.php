<?php
declare(strict_types=1);

const DB_HOST = 'localhost';
const DB_NAME = 'crenova_studio';
const DB_USER = 'root';
const DB_PASS = '';
const SITE_NAME = 'Crenova Studio';
const WHATSAPP_NUMBER = '2349048239391';
const PHONE_NUMBER = '09048239391';
const SITE_EMAIL = 'hello@crenovastudio.com';

function db(): ?PDO {
    static $pdo = false;
    if ($pdo !== false) return $pdo;
    try {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]);
    } catch (Throwable $e) { $pdo = null; }
    return $pdo;
}
