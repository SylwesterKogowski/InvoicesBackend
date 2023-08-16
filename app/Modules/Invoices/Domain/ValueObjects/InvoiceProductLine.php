<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\ValueObjects;

use App\Modules\Invoices\Domain\Entities\InvoiceInterface;
use App\Modules\Invoices\Domain\Entities\ProductInterface;
use App\Modules\Invoices\Domain\Factories\ProductFactoryInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceProductLineStorageStateInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

/**
 * One billed product line on the invoice
 * Contains the product billed and it's quantity
 * Database model: {@see \App\Modules\Invoices\Infrastructure\Database\Models\InvoiceProductLine}
 * Dto: {@link ../../Api/MessageSchemas/InvoiceProductLine.proto}
 * Technical:
 * Adapter over storage, lazy loads relations
 */
class InvoiceProductLine implements InvoiceProductLineInterface
{
    private ?ProductInterface $product = null;
    private ?InvoiceInterface $invoice = null;

    public function __construct(
        private InvoiceProductLineStorageStateInterface $invoiceProductLineStorageState,
        InvoiceInterface $invoice = null,
        ProductInterface $product = null
    ) {
        $this->product = $product;
        $this->invoice = $invoice;
    }

    public function getStorageState() {
        return $this->invoiceProductLineStorageState;
    }


    /**
     */
    public function getInvoice(): ?InvoiceInterface
    {
        return $this->invoice;
    }

    /**
     * @return ProductInterface product billed
     */
    public function getProduct(): ?ProductInterface
    {
        if (null === $this->product) {
            $this->product = App::make(ProductFactoryInterface::class)
                ->fromStorageState($this->invoiceProductLineStorageState->getProduct());
        }
        return $this->product;
    }
    /**
     * Returns the quantity of products billed
     */
    public function getQuantity(): int
    {
        return $this->invoiceProductLineStorageState->getQuantity();
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): Carbon
    {
        return $this->invoiceProductLineStorageState->getCreatedAt();
    }
}
