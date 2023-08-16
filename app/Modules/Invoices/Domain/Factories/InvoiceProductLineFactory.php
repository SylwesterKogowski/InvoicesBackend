<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Factories;

use App\Modules\Invoices\Domain\Entities\InvoiceInterface;
use App\Modules\Invoices\Domain\Entities\ProductInterface;
use App\Modules\Invoices\Domain\ValueObjects\InvoiceProductLine;
use App\Modules\Invoices\Domain\ValueObjects\InvoiceProductLineInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceProductLineStorageStateInterface;
use Illuminate\Support\Facades\App;

/**
 * Creates {@see InvoiceProductLine} objects
 *
 */
class InvoiceProductLineFactory implements InvoiceProductLineFactoryInterface
{
    /**
     * Creates a domain product line from ORM.
     * @param InvoiceProductLineStorageStateInterface $invoiceProductLineStorageState
     */
    public function fromStorageState(
        InvoiceProductLineStorageStateInterface $invoiceProductLineStorageState
    ): InvoiceProductLineInterface {
        return new InvoiceProductLine($invoiceProductLineStorageState);
    }
    /**
     * Creates a new product line.
     * @param ProductInterface $product
     * @param int $quantity
     */
    public function createNew(
        InvoiceInterface $invoice,
        ProductInterface $product,
        int $quantity
    ): InvoiceProductLineInterface {
        $invoiceProductLineORM = App::make(InvoiceProductLineStorageStateInterface::class);
        $invoiceProductLineORM->setProduct($product->getStorageState());
        $invoiceProductLineORM->setInvoice($invoice->getStorageState());
        $invoiceProductLineORM->setQuantity($quantity);
        return new InvoiceProductLine($invoiceProductLineORM, $invoice, $product);
    }
}
