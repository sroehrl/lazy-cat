<?php

namespace App\Member\Routes;

use App\Auth\Middleware\Auth;
use App\Auth\Middleware\RequiresLogin;
use App\Member\Models\MemberPaymentMethod;
use Neoan\Routing\Attributes\Get;
use Neoan\Routing\Interfaces\Routable;
use Neoan\Model\Collection;


#[Get('/api/member/payment-methods', RequiresLogin::class)]
class GetPaymentMethods implements Routable
{
    public function __invoke(Auth $auth): Collection
    {
        $member = (new \App\Auth\Routes\Me())($auth);
        return MemberPaymentMethod::retrieve(['memberId' => $member->id, '^deletedAt']);
    }
}