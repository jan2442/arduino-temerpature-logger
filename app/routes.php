<?php

require_once __DIR__ . '/config/config.php';

$container = $app->getContainer();

$container['db'] = function ($c) {
    $pdo = new PDO(PDO_DSN, PDO_USERNAME, PDO_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// Register component on container

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../app/templates', [
        //'cache' => __DIR__.'/../var/cache'
    ]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    return $view;
};


$container[\App\Controller\ApiController::class] = function ($c) {
    return new \App\Controller\ApiController($c['db']);
};

$container[\App\Controller\DashboardController::class] = function ($c) {
    return new \App\Controller\DashboardController($c['db'], $c['view']);
};

$container[\App\Controller\ExportController::class] = function ($c) {
    return new \App\Controller\ExportController($c['db'], $c['view']);
};

$container['validDate'] = function ($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
};

$container['jsonError'] = function ($message) {
    echo json_encode(array('state' => 'error', 'message' => $message));
};


$app->get('/', \App\Controller\DashboardController::class . ':index');
$app->get('/export/[{from}[/{to}]]', \App\Controller\ExportController::class . ':index');
$app->get('/api/{humidity}/{temperature}/{co2}', \App\Controller\ApiController::class . ':insert')->add(new \App\Middleware\TokenMiddleware());
$app->get('/api/ping', \App\Controller\ApiController::class . ':ping');

$app->add(new \App\Middleware\ApiError());
