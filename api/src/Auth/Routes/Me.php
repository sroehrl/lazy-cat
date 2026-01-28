<?php

namespace App\Auth\Routes;

use App\Auth\Middleware\Auth;
use App\Auth\Middleware\RequiresLogin;
use App\Member\Models\Member;
use App\User\Models\User;
use Neoan\Routing\Attributes\Get;
use Neoan\Routing\Interfaces\Routable;

#[Get('/api/auth/me', RequiresLogin::class)]
class Me implements Routable
{
    public function __invoke(Auth $auth): Member
    {
        return Member::retrieveOne(['userId' => $auth->get()['id'], '^deletedAt']);
    }
}