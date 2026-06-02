<?php
/**
 * StockVision - Front Controller
 * Single entry point: routes all requests to appropriate controllers
 */

// ── Session Configuration ──────────────────────────────────
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Lax');
session_start();

// ── Load Environment Variables ───────────────────────────────
require_once __DIR__ . '/../app/helpers/env.php';
loadEnv(__DIR__ . '/../.env');

// ── Load Configuration ─────────────────────────────────────
require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../app/config/database.php';

// ── Load Helpers ────────────────────────────────────────────
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/helpers/validation.php';
require_once __DIR__ . '/../app/helpers/logger.php';
require_once __DIR__ . '/../app/helpers/response.php';
require_once __DIR__ . '/../app/helpers/format.php';

// ── Parse Request ───────────────────────────────────────────
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip the base URL prefix (for subdirectory installations)
if (BASE_URL !== '' && str_starts_with($requestUri, BASE_URL)) {
    $requestUri = substr($requestUri, strlen(BASE_URL));
}

// Ensure path starts with / and remove trailing slash (except root)
$requestUri = '/' . ltrim($requestUri, '/');
if ($requestUri !== '/' && str_ends_with($requestUri, '/')) {
    $requestUri = rtrim($requestUri, '/');
}

// ── Load Routes ─────────────────────────────────────────────
$routes = require __DIR__ . '/../app/routes.php';

// ── Route Matching ──────────────────────────────────────────
$matchedRoute  = null;
$routeParams   = [];

foreach ($routes as $route) {
    [$method, $path, $controller, $action] = $route;

    // Check HTTP method
    if ($method !== $requestMethod) {
        continue;
    }

    // Convert route pattern to regex: {id} → (\d+)
    $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(\d+)', $path);
    $pattern = '#^' . $pattern . '$#';

    if (preg_match($pattern, $requestUri, $matches)) {
        $matchedRoute = $route;
        // Extract named parameters (skip full match at index 0)
        array_shift($matches);
        $routeParams = $matches;
        break;
    }
}

// ── Handle 404 ──────────────────────────────────────────────
if ($matchedRoute === null) {
    http_response_code(404);
    $pageTitle = '404 Not Found';
    $content = __DIR__ . '/../views/errors/404.php';
    if (isLoggedIn()) {
        require __DIR__ . '/../views/layouts/app.php';
    } else {
        echo '<!DOCTYPE html><html><head><title>404</title></head><body>';
        echo '<h1>404 - Page Not Found</h1>';
        echo '<p>The page you are looking for does not exist.</p>';
        echo '<a href="' . BASE_URL . '/login">Go to Login</a>';
        echo '</body></html>';
    }
    exit;
}

// ── Authentication Check ────────────────────────────────────
[, , $controllerName, $actionName] = $matchedRoute;

// Public routes (no auth required)
$publicRoutes = ['AuthController@showLogin', 'AuthController@login'];
$currentRoute = $controllerName . '@' . $actionName;

if (!in_array($currentRoute, $publicRoutes)) {
    if (!isLoggedIn()) {
        redirect('/login');
    }
}

// ── Load and Execute Controller ─────────────────────────────
$controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(500);
    die('Controller not found: ' . htmlspecialchars($controllerName));
}

require_once $controllerFile;

// Load model files that the controller might need
$modelDir = __DIR__ . '/../app/models/';
foreach (glob($modelDir . '*.php') as $modelFile) {
    require_once $modelFile;
}

// Instantiate controller and call action
if (!class_exists($controllerName)) {
    http_response_code(500);
    die('Controller class not found: ' . htmlspecialchars($controllerName));
}

$controller = new $controllerName();

if (!method_exists($controller, $actionName)) {
    http_response_code(500);
    die('Action not found: ' . htmlspecialchars($actionName));
}

// Call the action with route parameters
call_user_func_array([$controller, $actionName], $routeParams);
