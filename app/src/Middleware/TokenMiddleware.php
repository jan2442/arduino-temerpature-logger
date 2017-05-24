<?php
namespace  App\Middleware;
use App\Exceptions\InvalidArgumentException;

class TokenMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $time = date('now');
        $token = $request->getParam('token');
        $timestamp = $request->getParam('timestamp');

        if($timestamp < strtotime('-5 seconds')){
            throw InvalidArgumentException::timestampNotPresent();
        }

        if(!$token){
            throw InvalidArgumentException::tokenNotPresent();
        }
        if(md5($timestamp.SHARED_SECRET) != $token){
            throw InvalidArgumentException::invalidToken();
        }

        $response = $next($request, $response);
        return $response;
    }

}