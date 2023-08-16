<?php

namespace App\Modules\Invoices\Infrastructure\Database\Contracts;

use App\Modules\Invoices\Infrastructure\Database\Models\InvoiceProductLine;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

interface InvoiceProductLineStorageStateInterface
{

    /**
     * @param int $quantity amount of products billed
     */
    public function setQuantity(int $quantity): void;

    /**
     * @return int amount of products billed
     */
    public function getQuantity(): int;

    /**
     * @return ProductStorageStateInterface product billed
     */
    public function getProduct(): ProductStorageStateInterface;

    /**
     * Sets the product billed
     * @param ProductStorageStateInterface $productORM
     */
    public function setProduct(ProductStorageStateInterface $productORM): void;

    /**
     * Sets the invoice on which this line is billed
     * @param InvoiceStorageStateInterface $invoice
     */
    public function setInvoice(InvoiceStorageStateInterface $invoice): void;

    /**
     * @return Carbon time when product line record was created
     */
    public function getCreatedAt(): Carbon;


}
