<?php

namespace App\Modules\Invoices\Api\Mappers;

use App\Modules\Invoices\Api\GeneratedDtos\InvoiceProductLine;
use App\Modules\Invoices\Domain\ValueObjects\InvoiceProductLineInterface;

interface InvoiceProductLineMapperInterface
{
    public function convertDomainToDtoWithRelations(InvoiceProductLineInterface $productLine): InvoiceProductLine;
}
