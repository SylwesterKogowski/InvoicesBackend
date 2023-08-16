<?php

namespace App\Modules\Invoices\Domain\Repositories;

use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Entities\InvoiceInterface;
use App\Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use Ramsey\Uuid\UuidInterface;

interface InvoiceRepositoryInterface
{

    /**
     * Gives invoice by UUID
     * @param UuidInterface $id
     * @throws InvoiceNotFoundException
     */
    public function getInvoiceById(UuidInterface $id): InvoiceInterface;
}
