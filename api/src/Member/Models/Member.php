<?php

namespace App\Member\Models;

use App\User\Models\User;
use Neoan\Database\Database;
use Neoan\Model\Attributes\Computed;
use Neoan\Model\Attributes\IsForeignKey;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Attributes\Transform;
use Neoan\Model\Attributes\Type;
use Neoan\Model\Helper\DateTimeProperty;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;
use Neoan\Model\Transformers\LockedTimeIn;

/**
 * @method static withUser()
 * @method User user()
 */
class Member extends Model
{
    #[IsPrimaryKey]
    public int $id;

    #[IsForeignKey(User::class)]
    public int $userId;

    public string $firstName;

    public string $lastName;

    public int $gender = 0;

    public string $phone;

    public int $paws = 0;

    #[
        Type('datetime'),
        Transform(LockedTimeIn::class)
    ]
    public DateTimeProperty $dateOfBirth;

    #[Computed]
    public function name(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    #[Computed]
    public function email(): string
    {
        if (isset($this->userId)) {
            return $this->user()->email;
        }
        return '';
    }

    #[Computed]
    public function activePlan(): ?Membership
    {
        if (!isset($this->id))
            return null;
        return Membership::retrieveOne(['^deletedAt', 'memberId' => $this->id]);
    }

    #[Computed]
    public function isAdmin(): bool
    {
        return (bool) Database::easy('user.isAdmin', ['id' => $this->userId])[0]['isAdmin'];
    }

    use TimeStamps;

}