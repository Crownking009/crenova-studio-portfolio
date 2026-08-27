<?php
declare(strict_types=1);

function e(mixed $value): string { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function clean(mixed $value): string { return trim(strip_tags((string) $value)); }
function url(string $path = ''): string { return '/' . ltrim($path, '/'); }
function redirect(string $path): never { header('Location: ' . url($path)); exit; }
function csrf(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . e(csrf()) . '">'; }
function verify_csrf(): void { if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Invalid request token. Please try again.'); } }
function rate_limit(string $key, int $max = 8, int $seconds = 600): bool {
    $now = time(); $events = $_SESSION['rate_limits'][$key] ?? [];
    $events = array_values(array_filter($events, fn(int $time) => $time > $now - $seconds));
    if (count($events) >= $max) return false;
    $events[] = $now; $_SESSION['rate_limits'][$key] = $events; return true;
}
function flash(string $key, ?string $message = null): ?string { if ($message !== null) { $_SESSION['flash'][$key] = $message; return null; } $message = $_SESSION['flash'][$key] ?? null; unset($_SESSION['flash'][$key]); return $message; }
function is_admin(): bool { return !empty($_SESSION['admin_id']); }
function require_admin(): void { if (!is_admin()) redirect('/admin/login'); }
function whatsapp_url(string $message = ''): string { return 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . rawurlencode($message); }
function fallback_image(string $seed, string $size = '1200/800'): string { return 'https://images.unsplash.com/' . $seed . '?auto=format&fit=crop&w=1400&q=82'; }
