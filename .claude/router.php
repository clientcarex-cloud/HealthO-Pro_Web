<?php
/**
 * Local dev router for `php -S` (the built-in server ignores .htaccess).
 * Mirrors the clean-URL rewrite so extensionless links work while previewing.
 * Not deployed — .claude/ never ships, and .htaccess blocks dotfile paths.
 */
// dirname(__DIR__), not getcwd(): the built-in server may be launched from any
// working directory, and every path below is resolved against this.
$root = dirname(__DIR__);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === '/') {
    require $root . '/index.php';
    return true;
}

// Single job opening — mirrors the /careers/{slug} rewrite in .htaccess.
if (preg_match('#^/careers/([A-Za-z0-9\-]+)/?$#', $path, $m)) {
    $_GET['j'] = $m[1];
    require $root . '/career.php';
    return true;
}

$target = $root . $path;

if (is_file($target)) {
    return false; // let the built-in server stream static assets
}

if (is_file($target . '.php')) {
    require $target . '.php';
    return true;
}

http_response_code(404);
echo '404 ' . htmlspecialchars($path, ENT_QUOTES, 'UTF-8');
return true;
