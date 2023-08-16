<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Factories;

use App\Modules\Invoices\Domain\Entities\InvoiceInterface;
use App\Modules\Invoices\Domain\Entities\ProductInterface;
use App\Modules\Invoices\Domain\ValueObjects\InvoiceProductLineInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceProductLineStorageStateInterface;

interface InvoiceProductLineFactoryInterface
{
    /**
     * Creates a domain product line from ORM.
     * @param InvoiceProductLineStorageStateInterface $invoiceProductLineStorageState
     */
    public function fromStorageState(InvoiceProductLineStorageStateInterface $invoiceProductLineStorageState): InvoiceProductLineInterface;

    /**
     * Creates a new product line.
     * @param ProductInterface $product
     * @param int $quantity
     */
    public function createNew(
        InvoiceInterface $invoice,
        ProductInterface $product,
        int $quantity
    ): InvoiceProductLineInterface;
}
