<?php

namespace App\Modules\Invoices\Domain\Factories;

use App\Modules\Invoices\Domain\Entities\Product;
use App\Modules\Invoices\Domain\Entities\ProductInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\ProductStorageStateInterface;
use Illuminate\Support\Facades\App;

/**
 * Creates {@see Product} entities
 * Test: {@see \Tests\Unit\Modules\Invoices\Domain\Factories\ProductFactoryTest}
 */
class ProductFactory implements ProductFactoryInterface
{

    /**
     * Creates a domain product from ORM.
     * @param ProductStorageStateInterface $productStorageState
     */
    public function fromStorageState(ProductStorageStateInterface $productStorageState): ProductInterface
    {
        return new Product($productStorageState);
    }
    /**
     * Creates a new product. Doesn't save it yet
     * @param string $name
     * @param int $priceBrutto
     * @param string $currency
     */
    public function createNew(
        string $name,
        int $priceBrutto,
        string $currency = 'USD'
    ): ProductInterface {
        $productStorageState = App::make(ProductStorageStateInterface::class);
        $productStorageState->setName($name);
        $productStorageState->setBruttoPrice($priceBrutto);
        $productStorageState->setCurrency($currency);
        return new Product($productStorageState);
    }

}
