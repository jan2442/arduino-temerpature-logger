<?php

namespace App\Controller;


use PDO;
use Slim\Views\Twig;

class DashboardController
{
    private $pdo;
    private $view;

    public function __construct(PDO $pdo, Twig $view)
    {
        $this->pdo = $pdo;
        $this->view = $view;
    }

    public function index($request, $response, $args)
    {
        $statement = $this->pdo->prepare("Select created_at, temperature, humidity, co2 from telemetria where 1 ORDER BY id DESC LIMIT 1 ");
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $this->view->render($response, 'dashboard/index.html.twig', array(
            'temperature' => number_format($rows[0]['temperature'], 1),
            'humidity' => number_format($rows[0]['humidity'], 1),
            'co2' => number_format($rows[0]['co2'], 1),
            'createdAt' => substr($rows[0]['created_at'], 10)
        ));
    }
}