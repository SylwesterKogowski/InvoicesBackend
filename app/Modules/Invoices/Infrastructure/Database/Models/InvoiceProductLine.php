<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Database\Models;

use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceProductLineStorageStateInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageStateInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\ProductStorageStateInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Exception\UnsupportedOperationException;

/**
 * Represents one line of items billed in an invoice
 * @property string $id UUID of invoice product line
 * @property string $invoice_id UUID of invoice on which this line is billed
 * @property string $product_id UUID of product billed
 * @property int $quantity amount of products billed
 * @property Carbon $created_at time when invoice product line record was created
 * @property Carbon $updated_at time when invoice product line record was updated
 * @property Invoice $invoice invoice on which this line is billed
 * @property Product $product product billed
 */
class InvoiceProductLine extends Model implements InvoiceProductLineStorageStateInterface
{
    protected $table = "invoice_product_lines";
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * @return ProductStorageStateInterface product billed
     */
    public function getProduct(): ProductStorageStateInterface
    {
        return $this->product;
    }

    /**
     * @return int amount of products billed
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity amount of products billed
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @inheritDoc
     */
    public function setProduct(ProductStorageStateInterface $productORM): void
    {
        if (!($productORM instanceof Product)) {
            throw new UnsupportedOperationException("InvoiceProductLineORM only supports ProductORM instances");
        }
        $this->product()->associate($productORM);
    }

    /**
     * @inheritDoc
     */
    public function setInvoice(InvoiceStorageStateInterface $invoice): void
    {
        if (!($invoice instanceof Invoice)) {
            throw new UnsupportedOperationException("InvoiceProductLineORM only supports InvoiceORM instances");
        }
        $this->invoice()->associate($invoice);
    }

    /**
     * Used for propagation of saving from invoice
     */
    public function saveProductLineAndRelationsWithoutInvoice(): void
    {
        DB::transaction(function (): void {
             $this->save();
            if ($this->relationLoaded('product')) {
                //If product was not loaded, then there was nothing changed
                $this->product->save();
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }
}
