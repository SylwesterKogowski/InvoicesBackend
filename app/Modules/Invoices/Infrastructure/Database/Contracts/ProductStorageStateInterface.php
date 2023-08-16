<?php

namespace App\Modules\Invoices\Infrastructure\Database\Contracts;

use Illuminate\Support\Carbon;
use Ramsey\Uuid\UuidInterface;

interface ProductStorageStateInterface
{

    /**
     * @return string currency code of price
     */
    public function getCurrency(): string;

    /**
     * @param string $currency currency code of price
     */
    public function setCurrency(string $currency): void;

    /**
     * @return Carbon time when product record was created
     */
    public function getCreatedAt(): Carbon;

    /**
     * @param string $name name of product
     */
    public function setName(string $name): void;

    /**
     * @return Carbon time when product record was updated
     */
    public function getUpdatedAt(): Carbon;

    /**
     * @param int $price price brutto of product
     */
    public function setBruttoPrice(int $price): void;

    /**
     * @return int price brutto of product
     */
    public function getBruttoPrice(): int;

    /**
     * @return UuidInterface UUID
     */
    public function getId(): UuidInterface;


    /**
     * @return string name of product
     */
    public function getName(): string;
}
