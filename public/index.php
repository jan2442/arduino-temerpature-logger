<?php
error_reporting(E_ALL);
define ('__ROOT__', __DIR__.'/..');
require_once __ROOT__.'/vendor/autoload.php';


$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];



$app = new \Slim\App($configuration);
require_once __ROOT__.'/app/routes.php';
try{
    $app->run();
}catch(\Exception $ex){
    echo $ex->getMessage().'asd';
}
?>