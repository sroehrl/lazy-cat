<?php

namespace App\Product\Routes;

use App\Product\Models\Product;
use Neoan\Model\Collection;
use Neoan\Routing\Attributes\Get;
use Neoan\Routing\Interfaces\Routable;

#[Get('/api/products')]
class AllProducts implements Routable
{
    public function __invoke(): Collection
    {
        return Product::retrieve(['^deletedAt']);
    }
}