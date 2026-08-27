<?php
declare(strict_types=1);
require __DIR__ . '/config/config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = db();
        if (!$pdo) throw new RuntimeException('Could not connect. Check the database settings in config/config.php first.');
        $sql = file_get_contents(__DIR__ . '/install.sql');
        foreach (array_filter(array_map('trim', explode(';', $sql))) as $query) $pdo->exec($query);
        $stmt = $pdo->prepare('INSERT INTO users (name,username,password,role) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE name=VALUES(name),password=VALUES(password),role=VALUES(role)');
        $stmt->execute(['Oba', 'Oba', password_hash('Jesusislord666', PASSWORD_DEFAULT), 'admin']);
        $message = 'Installation complete. Delete install.php now, then sign in at /admin/login.';
    } catch (Throwable $e) { $message = $e->getMessage(); }
}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Install Crenova Studio</title><style>body{margin:0;min-height:100vh;display:grid;place-items:center;background:#003333;color:#fff;font-family:Arial,sans-serif}.card{width:min(500px,88vw);padding:35px;background:#0d4949}.brand{color:#99cc33;letter-spacing:.1em;font-size:12px}.message{background:#99cc33;color:#003333;padding:13px;line-height:1.5}.button{border:0;background:#99cc33;color:#003333;padding:12px 16px;font-weight:bold;cursor:pointer}</style></head><body><main class="card"><p class="brand">CRENOVA STUDIO</p><h1>Install the studio site</h1><p>This will create the database tables and securely generate the admin password hash for user <strong>Oba</strong>.</p><?php if($message): ?><p class="message"><?=htmlspecialchars($message,ENT_QUOTES,'UTF-8')?></p><?php else: ?><form method="post"><button class="button">Run installer</button></form><?php endif; ?></main></body></html>
