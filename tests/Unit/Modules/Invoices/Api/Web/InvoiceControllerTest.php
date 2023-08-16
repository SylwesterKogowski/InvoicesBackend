<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Api\Web;

use App\Modules\Invoices\Api\InvoicesApiInterface;
use App\Modules\Invoices\Api\Web\InvoiceController;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Tests {@see InvoiceController}
 */
class InvoiceControllerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldApproveInvoice(): void
    {
        $invoiceApiMock = $this->createMock(InvoicesApiInterface::class);
        $uuid = Uuid::uuid4();
        $invoiceApiMock->expects($this->once())
            ->method('approveInvoiceForPayments')
            ->with($this->equalTo($uuid));
        $controller = new InvoiceController($invoiceApiMock);

        $controller->approve($uuid->toString());
    }

    /**
     * @test
     */
    public function shouldRejectInvoice(): void
    {
        $invoiceApiMock = $this->createMock(InvoicesApiInterface::class);
        $uuid = Uuid::uuid4();
        $invoiceApiMock->expects($this->once())
            ->method('rejectInvoiceForPayments')
            ->with($this->equalTo($uuid));
        $controller = new InvoiceController($invoiceApiMock);

        $controller->reject($uuid->toString());
    }

    /**
     * @test
     */
    public function shouldGetInvoice(): void
    {
        $invoiceApiMock = $this->createMock(InvoicesApiInterface::class);
        $uuid = Uuid::uuid4();
        $invoiceApiMock->expects($this->once())
            ->method('getInvoiceWithRelations')
            ->with($this->equalTo($uuid));
        $controller = new InvoiceController($invoiceApiMock);

        $controller->get($uuid->toString());
    }
}
