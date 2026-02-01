<?php

namespace App\Member\Routes;

use App\Auth\Error\Api404;
use App\Auth\Middleware\Auth;
use App\Auth\Middleware\RequiresLogin;
use App\Member\Models\MemberPaymentMethod;
use Neoan\Request\Request;
use Neoan\Routing\Attributes\Delete;
use Neoan\Routing\Interfaces\Routable;

#[Delete('/api/member/payment-method/:id', RequiresLogin::class)]
class DeletePaymentMethod implements Routable
{
    public function __invoke(Auth $auth): array|Api404
    {
        $member = (new \App\Auth\Routes\Me())($auth);
        $paymentMethod = MemberPaymentMethod::retrieveOne(['memberId' => $member->id, 'id' => Request::getParameter('id'), '^deletedAt']);
        if (!$paymentMethod) {
            return new Api404('Invalid payment token');
        }
        $paymentMethod->delete();

        return ['name' => 'DeletePaymentMethod'];
    }
}