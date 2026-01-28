<?php

namespace App\Product\Models;

use Config\Transformers\PriceTransformation;
use Neoan\Model\Attributes\IsForeignKey;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Attributes\Transform;
use Neoan\Model\Attributes\Type;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;

class ProductSize extends Model
{
    #[IsPrimaryKey]
    public int $id;

    #[IsForeignKey(Product::class)]
    public int $productId;

    public string $size;

    #[Type('int'), Transform(PriceTransformation::class)]
    public float $price;

    use TimeStamps;

}