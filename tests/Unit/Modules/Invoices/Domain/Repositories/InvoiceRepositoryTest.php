<?php

namespace Tests\Unit\Modules\Invoices\Domain\Repositories;

use App\Modules\Invoices\Domain\Repositories\InvoiceRepository;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageQueryInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageStateInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Tests for {@see InvoiceRepository}
 */
class InvoiceRepositoryTest extends TestCase
{

    /**
     * @test
     */
    public function shouldGetInvoiceById()
    {
        $invoiceStorageMock = $this->createMock(InvoiceStorageStateInterface::class);
        $storageQueryMock = $this->createMock(InvoiceStorageQueryInterface::class);
        $uuidTest = Uuid::uuid4();

        $storageQueryMock->expects($this->once())
            ->method('getById')->with($uuidTest)
            ->willReturn($invoiceStorageMock);

        $invoiceRepository = new InvoiceRepository($storageQueryMock);
        $invoiceRepository->getInvoiceById($uuidTest);
    }
}
