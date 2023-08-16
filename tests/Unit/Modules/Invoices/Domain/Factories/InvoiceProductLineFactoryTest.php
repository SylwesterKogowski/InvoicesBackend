<?php

namespace Tests\Unit\Modules\Invoices\Domain\Factories;

use App\Modules\Invoices\Domain\Factories\InvoiceProductLineFactory;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceProductLineStorageStateInterface;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

/**
 * Tests {@see InvoiceProductLineFactory}
 */
class InvoiceProductLineFactoryTest extends TestCase
{
    use CreatesApplication;

    /**
     * @test
     */
    public function shouldCreateFromStorageState(): void
    {
        $this->createApplication();
        $invoiceProductLineStorageStateMock = $this->createMock(InvoiceProductLineStorageStateInterface::class);
        $invoiceProductLineFactory = new InvoiceProductLineFactory();
        $invoiceProductLine = $invoiceProductLineFactory->fromStorageState($invoiceProductLineStorageStateMock);
        $this->assertEquals($invoiceProductLineStorageStateMock, $invoiceProductLine->getStorageState());
    }
}
