<?php

namespace App\Modules\Invoices\Api\Mappers;

use App\Modules\Invoices\Api\GeneratedDtos\InvoiceProductLine;
use App\Modules\Invoices\Domain\ValueObjects\InvoiceProductLineInterface;

class InvoiceProductLineMapper implements InvoiceProductLineMapperInterface
{
    public function __construct(private ProductMapperInterface $productMapper)
    {
    }

    public function convertDomainToDtoWithRelations(InvoiceProductLineInterface $productLine): InvoiceProductLine
    {
        $invoiceProductLineDto = new InvoiceProductLine();
        $invoiceProductLineDto->setQuantity($productLine->getQuantity());
        $invoiceProductLineDto->setProduct(
            $this->productMapper->convertDomainToDtoWithRelations($productLine->getProduct())
        );
        $invoiceProductLineDto->setCreatedAt($productLine->getCreatedAt()->getTimestamp());

        return $invoiceProductLineDto;
    }
}
