<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Database\Models;

use App\Modules\Invoices\Infrastructure\Database\Contracts\ProductStorageStateInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * A product that can be billed in an invoice
 * @property string $id UUID of product
 * @property string $name name of product
 * @property integer $price price brutto of product
 * @property string $currency currency code of price
 * @property Carbon $created_at time when product record was created
 * @property Carbon $updated_at time when product record was updated
 */
class Product extends Model implements ProductStorageStateInterface
{
    use HasUuids;

    protected $table = 'products';

    /**
     * @inheritDoc
     */
    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function getBruttoPrice(): int
    {
        return $this->price;
    }

    /**
     * @inheritDoc
     */
    public function setBruttoPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @inheritDoc
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @inheritDoc
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }


    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

}
