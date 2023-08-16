<?php

namespace App\Modules\Invoices\Domain\ValueObjects;


use App\Modules\Invoices\Domain\Entities\InvoiceInterface;
use App\Modules\Invoices\Domain\Entities\ProductInterface;
use Illuminate\Support\Carbon;

/**
 * One billed product line on the invoice
 * Contains the product billed and it's quantity
 * Database model: {@see \App\Modules\Invoices\Infrastructure\Database\Models\InvoiceProductLine}
 * Dto: {@link ../../Api/MessageSchemas/InvoiceProductLine.proto}
 */
interface InvoiceProductLineInterface
{
    /**
     */
    public function getInvoice(): ?InvoiceInterface;

    /**
     * @return ProductInterface product billed
     */
    public function getProduct(): ?ProductInterface;

    /**
     * Returns the quantity of products billed
     */
    public function getQuantity(): int;

    /**
     * @return Carbon time when product line record was created
     */
    public function getCreatedAt(): Carbon;
}
