<?php

namespace App\Product\Models;

use Config\Transformers\PriceTransformation;
use Neoan\Model\Attributes\IsForeignKey;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Attributes\Transform;
use Neoan\Model\Attributes\Type;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;

class ProductAddon extends Model
{
    #[IsPrimaryKey]
    public int $id;

    #[IsForeignKey(Product::class)]
    public int $productId;

    public string $name;
    public string $description;
    #[Type('int'), Transform(PriceTransformation::class)]
    public float $addonPrice;

    use TimeStamps;

}