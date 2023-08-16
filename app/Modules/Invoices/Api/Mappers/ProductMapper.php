<?php

namespace App\Modules\Invoices\Api\Mappers;

use App\Modules\Invoices\Api\GeneratedDtos\Product;
use App\Modules\Invoices\Domain\Entities\ProductInterface;

/**
 * Tests: {@see \Tests\Unit\Modules\Invoices\Api\Mappers\ProductMapperTest}
 */
class ProductMapper implements ProductMapperInterface
{

    public function convertDomainToDtoWithRelations(ProductInterface $product): Product
    {
        $protuctDto = new Product();
        $protuctDto->setId($product->getId()->toString());
        $protuctDto->setName($product->getName());
        $protuctDto->setBruttoPrice($product->getBruttoPrice());
        $protuctDto->setCurrency($product->getCurrency());
        $protuctDto->setUpdatedAt($product->getUpdatedAt()->getTimestamp());
        $protuctDto->setCreatedAt($product->getCreatedAt()->getTimestamp());

        return $protuctDto;
    }
}
