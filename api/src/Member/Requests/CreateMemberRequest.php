<?php

namespace App\Member\Requests;

use Neoan\Model\Helper\DateTimeProperty;
use Neoan\Request\RequestGuard;

class CreateMemberRequest extends RequestGuard
{
    public string $email;
    public string $firstName;
    public string $lastName;
    public string $password;
    public string $phone;
    public DateTimeProperty $dateOfBirth;


}