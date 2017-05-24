<?php

namespace App\Controller;

use PDO;

class ApiController
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function insert(\Psr\Http\Message\RequestInterface $request, \Psr\Http\Message\ResponseInterface $response, $args)
    {
        $query = 'INSERT INTO telemetria (humidity, temperature,co2, created_at) VALUES (' . $args['humidity'] . ', ' . $args['temperature'] . ', ' . $args['co2'] . ', "' . date('Y-m-d H:i:s') . '")';
        $this->pdo->query($query);
        return $response->withJson(array('type' => 'success', 'message' => 'Record inserted'));
    }

    public function ping($request, $response){
        return $response->withJson([
            'time' => time()
        ]);
    }
}