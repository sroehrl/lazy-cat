<?php

namespace App\Member\Routes;

use App\Auth\Middleware\RequiresLogin;
use App\Member\Models\MemberPaymentMethod;
use App\Member\Requests\PaymentMethodRequest;
use Neoan\Routing\Attributes\Post;
use Neoan\Routing\Interfaces\Routable;

use App\Auth\Middleware\Auth;

#[Post('/api/member/payment-method', RequiresLogin::class)]
class CreatePaymentMethod implements Routable
{
    public function __invoke(Auth $auth, PaymentMethodRequest $request): MemberPaymentMethod
    {
        $member = (new \App\Auth\Routes\Me())($auth);
        $request->memberId = $member->id;
        return (new MemberPaymentMethod((array) $request))->store();
    }
}