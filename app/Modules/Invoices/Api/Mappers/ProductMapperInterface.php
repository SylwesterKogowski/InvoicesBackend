<?php

namespace App\Modules\Invoices\Api\Mappers;

use App\Modules\Invoices\Api\GeneratedDtos\Product;
use App\Modules\Invoices\Domain\Entities\ProductInterface;

interface ProductMapperInterface
{
    public function convertDomainToDtoWithRelations(ProductInterface $product): Product;
}
