<?php
namespace App\Controller;
use DateInterval;
use DateTime;
use Exception;
use InvalidArgumentException;
use PDO;

class ExportController
{
    protected $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index(\Psr\Http\Message\RequestInterface $request, \Psr\Http\Message\ResponseInterface $response, $args){
        if (!$to = DateTime::createFromFormat('Y-m-d', $args['to'])) {
            $to = new DateTime('now');
        }
        if (!$from = DateTime::createFromFormat('Y-m-d', $args['from'])) {
            $from = new DateTime('now');
            $from->sub(new DateInterval('PT144H'));
        }

        if($from > $to){
            throw new InvalidArgumentException('From is greather than to');
        }

        if (abs($to->diff($from)->days) > 10) {
            throw new Exception('Date range too hight');
        }


        $q =  "Select created_at, temperature, humidity,co2 from telemetria where created_at <='".$to->format('Y-m-d H:i:s')."' AND created_at >= '".$from->format('Y-m-d H:i:s')."'  ORDER BY id DESC LIMIT 2016 ";
        $q = "Select created_at, temperature, humidity,co2 from telemetria where 1 ORDER BY id DESC LIMIT 2016 ";
        $statement = $this->pdo->prepare($q);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_NUM);
        sort($rows);
        echo json_encode($rows);
    }
}