<?php
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

$path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/');
$segments = $path === '' ? [] : explode('/', $path);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    if (in_array($action, ['login','contact','booking'], true) && !rate_limit($action . ':' . ($_SERVER['REMOTE_ADDR'] ?? 'local'))) {
        flash('error', 'Please wait a few minutes before trying again.');
        redirect($action === 'login' ? '/admin/login' : ($action === 'booking' ? '/book' : '/contact'));
    }
    if ($action === 'login') {
        $user = User::attempt((string) ($_POST['username'] ?? ''), (string) ($_POST['password'] ?? ''));
        if ($user) { $_SESSION['admin_id'] = $user['id']; flash('success', 'Welcome back, ' . $user['name'] . '.'); redirect('/admin'); }
        flash('error', 'Those credentials do not match our records.'); redirect('/admin/login');
    }
    if ($action === 'logout') { session_destroy(); redirect('/'); }
    if ($action === 'contact') {
        Message::create(['name' => clean($_POST['name'] ?? ''), 'email' => clean($_POST['email'] ?? ''), 'phone' => clean($_POST['phone'] ?? ''), 'subject' => clean($_POST['subject'] ?? ''), 'message' => clean($_POST['message'] ?? '')]);
        flash('success', 'Thank you. Your note is with the studio.'); redirect('/contact');
    }
    if ($action === 'order') {
        Order::create(['customer_name'=>clean($_POST['name'] ?? ''),'phone'=>clean($_POST['phone'] ?? ''),'address'=>clean($_POST['address'] ?? ''),'items'=>clean($_POST['items'] ?? ''),'total'=>(float) ($_POST['total'] ?? 0)]);
        http_response_code(204); exit;
    }
    if ($action === 'booking') {
        try {
            Booking::create(['service' => clean($_POST['service'] ?? ''), 'name' => clean($_POST['name'] ?? ''), 'email' => clean($_POST['email'] ?? ''), 'phone' => clean($_POST['phone'] ?? ''), 'booking_date' => clean($_POST['booking_date'] ?? ''), 'booking_time' => clean($_POST['booking_time'] ?? ''), 'notes' => clean($_POST['notes'] ?? '')]);
            flash('success', 'Your consultation request has been received. We will confirm your slot shortly.');
        } catch (RuntimeException $e) { flash('error', $e->getMessage()); }
        redirect('/book');
    }
    if (str_starts_with($action, 'admin_')) {
        require_admin();
        $resource = substr($action, 6);
        if ($resource === 'save') {
            $table = clean($_POST['resource'] ?? '');
            $id = (int) ($_POST['id'] ?? 0);
            AdminResource::save($table, $_POST, $_FILES, $id);
            flash('success', ucfirst(rtrim($table, 's')) . ' saved.'); redirect('/admin/' . $table);
        }
        if ($resource === 'delete') {
            AdminResource::delete(clean($_POST['table'] ?? ''), (int) ($_POST['id'] ?? 0));
            flash('success', 'Item deleted.'); redirect('/admin/' . clean($_POST['table'] ?? 'projects'));
        }
        if ($resource === 'delete_gallery') {
            $projectId = (int) ($_POST['project_id'] ?? 0);
            Project::deleteImage((int) ($_POST['image_id'] ?? 0), $projectId);
            flash('success', 'Gallery image removed.'); redirect('/admin/projects?edit=' . $projectId);
        }
        if ($resource === 'booking_status') { Booking::updateStatus((int) $_POST['id'], clean($_POST['status'])); flash('success', 'Booking updated.'); redirect('/admin/bookings'); }
    }
}

$page = $segments[0] ?? 'home';
$slug = $segments[1] ?? null;
if ($page === 'admin') {
    $section = $segments[1] ?? 'dashboard';
    if ($section === 'login') { view('admin/login', ['title' => 'Admin login']); exit; }
    require_admin();
    if (isset($_GET['edit']) && ctype_digit((string) $_GET['edit'])) {
        view('admin/edit', ['section' => $section, 'id' => (int) $_GET['edit'], 'title' => 'Edit item']); exit;
    }
    view('admin/index', ['section' => $section, 'title' => 'Studio admin']); exit;
}

$routes = [
    'home' => 'home', 'portfolio' => 'portfolio', 'services' => 'services', 'shop' => 'shop', 'cart' => 'cart',
    'book' => 'book', 'blog' => 'blog', 'contact' => 'contact'
];
if ($page === 'project' && $slug) { view('project', ['project' => Project::findBySlug($slug), 'title' => 'Project']); exit; }
if ($page === 'article' && $slug) { view('article', ['article' => Blog::findBySlug($slug), 'title' => 'Journal']); exit; }
view($routes[$page] ?? '404', ['title' => $routes[$page] ?? 'Page not found']);
