<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Body;

class ApiError
{
    public function __invoke($request, ResponseInterface $response, $next)
    {
        try{
            return $next($request, $response);
        }catch (\InvalidArgumentException $ex){
            return $response
                ->withStatus(400)
                ->withJson(['error' => $ex->getMessage(),'errorCode' => $ex->getCode()]);
        }
    }
}