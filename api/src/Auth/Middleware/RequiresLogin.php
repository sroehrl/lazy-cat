<?php

namespace App\Auth\Middleware;

use Neoan\Errors\Unauthorized;
use Neoan\Routing\Interfaces\Routable;

class RequiresLogin implements Routable
{

    public function __invoke(Auth $auth): static
    {
        if(!$auth->get()) {
            new Unauthorized();
        }
        return $this;
    }
}