<?php

namespace App\Auth\Error;

use Neoan\Helper\Terminate;
use Neoan\Response\Response;

class Api400
{
    public function __construct(string $error = 'Server Error')
    {
        $response = new Response();
        http_response_code(400);
        $response->json(['error' => $error]);
        Terminate::die();
    }
}