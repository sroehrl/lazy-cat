<?php

namespace App\Auth\Error;

use Neoan\Helper\Terminate;
use Neoan\Response\Response;

class Api404
{
    public function __construct(string $error = 'Not Found')
    {
        $response = new Response();
        http_response_code(404);
        $response->json(['error' => $error]);
        Terminate::die();
    }
}