<?php

namespace App\User\Models;

use App\Auth\Error\Unauthenticated;
use App\Member\Models\Member;
use Neoan\Database\Database;
use Neoan\Model\Attributes\Computed;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Attributes\IsUnique;
use Neoan\Model\Attributes\Transform;
use Neoan\Model\Attributes\Type;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;
use Neoan\Model\Transformers\Hash;

class User extends Model
{
    #[IsPrimaryKey]
    public int $id;

    #[IsUnique]
    public string $email;

    #[Transform(Hash::class)]
    public string $password;

    #[Type('boolean')]
    public bool $isAdmin = false;



    use TimeStamps;

    public static function login($email, $password): ?User
    {
        $res = Database::easy('user.id user.password', ['email' => $email]);
        if (empty($res) || !password_verify($password, $res[0]['password'])) {
            new Unauthenticated();
        }
        return self::get($res[0]['id']);
    }

    public function member(): ?Member
    {
        return Member::retrieveOne(['userId' => $this->id, '^deletedAt']);
    }
}