<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\FunnelController;
use App\Services\OrderService;
use App\Services\TrackingService;
use App\Support\Response;
use App\Support\Store;

session_start();

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strpos($class, $prefix) !== 0) {
        return;
    }

    $relative = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix)));
    $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $relative . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

$store = new Store(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'funnel.json');
$tracking = new TrackingService($store);
$orders = new OrderService($store);
$funnel = new FunnelController($tracking, $orders);
$admin = new AdminController($store);
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

switch ($path) {
    case '/':
        Response::redirect('/vsl?affiliate_id=demo-affiliate&click_id=click-123&campaign=interview-prep');
        break;
    case '/vsl':
        $funnel->vsl();
        break;
    case '/checkout':
        $funnel->checkout();
        break;
    case '/upsell':
        $funnel->upsell();
        break;
    case '/downsell':
        $funnel->downsell();
        break;
    case '/thank-you':
        $funnel->thankYou();
        break;
    case '/members':
        $funnel->members();
        break;
    case '/webhook/purchase':
        $funnel->webhook();
        break;
    case '/admin':
        $admin->dashboard();
        break;
    case '/qa':
        $admin->qa();
        break;
    default:
        http_response_code(404);
        echo 'Not found';
}

