<?php

namespace App\Modules\Invoices\Domain\Factories;

use App\Modules\Invoices\Domain\Entities\Product;
use App\Modules\Invoices\Domain\Entities\ProductInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\ProductStorageStateInterface;

interface ProductFactoryInterface
{
    /**
     * Creates a domain product from ORM.
     * @param ProductStorageStateInterface $productStorageState
     */
    public function fromStorageState(ProductStorageStateInterface $productStorageState): ProductInterface;

    /**
     * Creates a new product. Doesn't save it yet
     * @param string $name
     * @param int $priceBrutto
     * @param string $currency
     */
    public function createNew(string $name, int $priceBrutto, string $currency = 'USD'): ProductInterface;
}
