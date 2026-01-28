<?php

namespace App\Auth\Permissions;

use App\Auth\Middleware\Auth;
use App\Auth\Middleware\RequiresLogin;
use Neoan\Errors\Unauthorized;
use Neoan\Routing\Interfaces\Routable;

class RequiresAdminPermission implements Routable
{
    public array $adminUser;
    public function __invoke(RequiresLogin $requiresLogin, Auth $auth): static
    {
        if(!$auth->get()['isAdmin']) {
            new Unauthorized();
        }
        $this->adminUser = $auth->getUser();
        return $this;
    }
}