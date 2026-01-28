<?php

namespace App\Transaction\Models;

use App\Member\Models\Member;
use Neoan\Model\Attributes\IsForeignKey;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Model;

class PawTransaction extends Model
{
    #[IsPrimaryKey]
    public int $id;

    #[IsForeignKey(Member::class)]
    public int $memberId;

    public int $paws;

}