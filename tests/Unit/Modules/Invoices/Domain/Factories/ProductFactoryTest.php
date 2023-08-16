<?php

namespace Tests\Unit\Modules\Invoices\Domain\Factories;

use App\Modules\Invoices\Domain\Entities\ProductInterface;
use App\Modules\Invoices\Domain\Factories\ProductFactory;
use App\Modules\Invoices\Infrastructure\Database\Contracts\ProductStorageStateInterface;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

/**
 * Tests for {@see ProductFactory}
 */
class ProductFactoryTest extends TestCase
{
    use CreatesApplication;

    /**
     * @test
     */
    public function shouldCreateFromStorageState(): void
    {
        $app = $this->createApplication();
        $productStorageMock = $this->createMock(ProductStorageStateInterface::class);

        $factory = new ProductFactory();
        $product = $factory->fromStorageState($productStorageMock);
        $this->assertEquals($productStorageMock, $product->getStorageState());
        $this->assertInstanceOf(ProductInterface::class, $product);
    }

    /**
     * @test
     * @testWith ["Apples", 1100, "USD"]
     */
    public function shouldCreateNew($name, $priceBrutto, $currency): void
    {
        $app = $this->createApplication();
        $productStorageMock = $this->createMock(ProductStorageStateInterface::class);

        $productStorageMock->expects($this->once())->method('setName')->with($name);
        $productStorageMock->expects($this->once())->method('setBruttoPrice')->with($priceBrutto);
        $productStorageMock->expects($this->once())->method('setCurrency')->with($currency);
        $app->instance(ProductStorageStateInterface::class, $productStorageMock);
        $factory = new ProductFactory();
        $product = $factory->createNew(
            $name,
            $priceBrutto,
            $currency
        );


        $this->assertInstanceOf(ProductInterface::class, $product);
    }
}
