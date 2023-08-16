<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Api;

use App\Modules\Invoices\Api\GeneratedDtos\Invoice;
use App\Modules\Invoices\Api\InvoicesApi;
use App\Modules\Invoices\Api\Mappers\InvoiceMapperInterface;
use App\Modules\Invoices\Domain\Entities\InvoiceInterface;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use App\Modules\Invoices\Domain\ValueObjects\InvoiceProductLineInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\CreatesApplication;

/**
 * Tests for {@see InvoicesApi}
 */
class InvoicesApiTest extends TestCase
{
    use CreatesApplication;

    /**
     * @test
     */
    public function whenRejectInvoiceForPaymentsShouldQueryForInvoice(): void
    {
        $app = $this->createApplication();
        $repositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $app->instance(InvoiceRepositoryInterface::class, $repositoryMock);

        $uuid = Uuid::uuid4();
        $invoiceMock = $this->createMock(InvoiceInterface::class);
        $repositoryMock->expects($this->once())
            ->method('getInvoiceById')
            ->with($this->equalTo($uuid))
            ->willReturn($invoiceMock);

        (new InvoicesApi())->rejectInvoiceForPayments($uuid);
    }
    /**
     * @test
     */
    public function whenApproveInvoiceForPaymentsShouldQueryForInvoice(): void
    {
        $app = $this->createApplication();
        $repositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $app->instance(InvoiceRepositoryInterface::class, $repositoryMock);

        $uuid = Uuid::uuid4();
        $invoiceMock = $this->createMock(InvoiceInterface::class);
        $repositoryMock->expects($this->once())
            ->method('getInvoiceById')
            ->with($this->equalTo($uuid))
            ->willReturn($invoiceMock);

        (new InvoicesApi())->approveInvoiceForPayments($uuid);
    }

    /**
     * @test
     */
    public function whenGetInvoiceWithRelationsShouldQueryForInvoice(): void
    {
        $app = $this->createApplication();
        $repositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $app->instance(InvoiceRepositoryInterface::class, $repositoryMock);
        $invoiceMapperMock = $this->createMock(InvoiceMapperInterface::class);
        $app->instance(InvoiceMapperInterface::class, $invoiceMapperMock);

        $uuid = Uuid::uuid4();
        $invoiceMock = $this->createMock(InvoiceInterface::class);
        $repositoryMock->expects($this->once())
            ->method('getInvoiceById')
            ->with($this->equalTo($uuid))
            ->willReturn($invoiceMock);

        (new InvoicesApi())->getInvoiceWithRelations($uuid);
    }
    /**
     * @test
     */
    public function whenGetInvoiceWithRelationsShouldCallInvoiceMapper(): void
    {
        $app = $this->createApplication();
        $repositoryMock = $this->createMock(InvoiceRepositoryInterface::class);
        $app->instance(InvoiceRepositoryInterface::class, $repositoryMock);
        $invoiceMapperMock = $this->createMock(InvoiceMapperInterface::class);
        $app->instance(InvoiceMapperInterface::class, $invoiceMapperMock);

        $uuid = Uuid::uuid4();
        $invoiceMock = $this->createMock(InvoiceInterface::class);
        $repositoryMock->expects($this->once())
            ->method('getInvoiceById')
            ->with($this->equalTo($uuid))
            ->willReturn($invoiceMock);

        $invoiceMapperMock->expects($this->once())
            ->method('convertDomainToDtoWithRelations')
            ->willReturn($this->createMock(Invoice::class));

        (new InvoicesApi())->getInvoiceWithRelations($uuid);
    }
}
