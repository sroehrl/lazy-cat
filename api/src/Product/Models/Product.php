<?php

namespace App\Product\Models;

use App\Product\Enums\ProductType;
use Config\Transformers\PriceTransformation;
use Neoan\Model\Attributes\HasMany;
use Neoan\Model\Attributes\IsEnum;
use Neoan\Model\Attributes\IsPrimaryKey;
use Neoan\Model\Attributes\Transform;
use Neoan\Model\Attributes\Type;
use Neoan\Model\Collection;
use Neoan\Model\Model;
use Neoan\Model\Traits\TimeStamps;

class Product extends Model
{
    #[IsPrimaryKey]
    public int $id;
    public string $name;
    public string $description;
    public ?string $imageLocation;
    #[Type('int'), Transform(PriceTransformation::class)]
    public float $price;

    #[IsEnum(ProductType::class)]
    public ?ProductType $type;
    public ?int $paws;

    #[HasMany(ProductAddon::class, ['productId' => 'id', '^deletedAt'])]
    public Collection $addons;

    #[HasMany(ProductSize::class, ['productId' => 'id', '^deletedAt'])]
    public Collection $sizes;


    use TimeStamps;

}