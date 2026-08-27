# Crenova Studio

A PHP 8 / MySQL creative-agency site designed for conventional shared hosting. It includes a public portfolio, services, shop-to-WhatsApp flow, booking and contact forms, a journal, and a protected studio admin dashboard.

## Install on shared hosting

1. Create a MySQL database and database user in your hosting control panel.
2. Edit `config/config.php` with the database host, database name, username and password. For safer deployment, set matching server environment variables and move secrets outside the public web root where the host supports it.
3. Upload the contents of this folder to the site’s public folder (`public_html` or the configured document root).
4. Ensure the `uploads/` directory is writable by PHP (usually `755` or `775`, depending on host configuration).
5. Open `https://your-domain.com/install.php` and run the installer once.
6. Delete `install.php` from the server immediately after installation.
7. Sign in at `/admin/login` using `Oba` and `Jesusislord666`, then change the password in the database or add a password-change screen before handing over the site.

## Key paths

- `/admin/login` - secure admin sign-in
- `/admin` - dashboard
- `/portfolio` - project work and filters
- `/shop` - local cart with an order composed for WhatsApp
- `/book` - booking request form with duplicate pending/approved slot prevention

## Notes

- Placeholder portfolio and product imagery is sourced from Unsplash URLs; replace it from the admin dashboard with owned client work before launch.
- The WhatsApp order link is configured for `090239391` in `config/config.php`. Confirm the full Nigeria international WhatsApp number before public launch because the supplied number appears shorter than a standard mobile number.
- The first install generates a bcrypt password hash; no database password is stored in plain text.
