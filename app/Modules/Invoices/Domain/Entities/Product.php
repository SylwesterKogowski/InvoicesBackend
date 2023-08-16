<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Entities;

use App\Modules\Invoices\Infrastructure\Database\Contracts\ProductStorageStateInterface;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\UuidInterface;

/**
 * Represents a product on an invoice.
 *  Database model: {@see \App\Modules\Invoices\Infrastructure\Database\Models\Product}
 *  Dto: {@link ../../Api/MessageSchemas/Product.proto}
 * Test: {@see \Tests\Unit\Modules\Invoices\Domain\Entities\ProductTest}
 *  Technical:
 *  Adapter over storage, lazy loads relations
 */
class Product implements ProductInterface
{
    public function __construct(
        private ProductStorageStateInterface $productStorageState
    ) {
    }

    public function getStorageState(): ProductStorageStateInterface
    {
        return $this->productStorageState;
    }


    /**
     * @return string currency code of price
     */
    public function getCurrency(): string
    {
        return $this->productStorageState->getCurrency();
    }

    /**
     * @param string $currency currency code of price
     */
    public function setCurrency(string $currency): void
    {
        $this->productStorageState->setCurrency($currency);
    }


    /**
     * @return Carbon time when product record was created
     */
    public function getCreatedAt(): Carbon
    {
        return $this->productStorageState->getCreatedAt();
    }

    /**
     * @param string $name name of product
     */
    public function setName(string $name): void
    {
        $this->productStorageState->setName($name);
    }

    /**
     * @return Carbon time when product record was updated
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->productStorageState->getUpdatedAt();
    }

    /**
     * @param int $price price brutto of product
     */
    public function setBruttoPrice(int $price): void
    {
        $this->productStorageState->setBruttoPrice($price);
    }

    /**
     * @return int price brutto of product
     */
    public function getBruttoPrice(): int
    {
        return $this->productStorageState->getBruttoPrice();
    }

    /**
     * @return UuidInterface UUID
     */
    public function getId(): UuidInterface
    {
        return $this->productStorageState->getId();
    }


    /**
     * @return string name of product
     */
    public function getName(): string
    {
        return $this->productStorageState->getName();
    }
}
