<?php

namespace App\Modules\Invoices\Api\Mappers;

use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Entities\InvoiceInterface;

interface InvoiceMapperInterface
{
    public function convertDomainToDtoWithRelations(InvoiceInterface $invoice): \App\Modules\Invoices\Api\GeneratedDtos\Invoice;
}
