<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Domain\Factories;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Domain\Factories\InvoiceFactory;
use App\Modules\Invoices\Infrastructure\Database\Contracts\CompanyStorageStateInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageStateInterface;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

/**
 * Tests {@see InvoiceFactory}
 */
class InvoiceFactoryTest extends TestCase
{
    use CreatesApplication;

    /**
     * @test
     * @testWith ["April/1/2020", "draft", "2020-01-01", "2020-01-31"]
     */
    public function shouldCreateNew($number, $status, $issueDate, $dueDate): void
    {
        $app = $this->createApplication();
        $issueDate = Carbon::make($issueDate);
        $dueDate = Carbon::make($dueDate);
        $companyIssuingStorageMock = $this->createMock(CompanyStorageStateInterface::class);
        $companyIssuingMock = $this->createConfiguredMock(Company::class, [
            'getStorageState' => $companyIssuingStorageMock,
        ]);
        $companyBilledStorageMock = $this->createMock(CompanyStorageStateInterface::class);
        $companyBilledMock = $this->createConfiguredMock(Company::class, [
            'getStorageState' => $companyBilledStorageMock,
        ]);
        $status = StatusEnum::from($status);
        $invoiceStorageMock = $this->createMock(InvoiceStorageStateInterface::class);

        $invoiceStorageMock->expects($this->once())->method('setNumber')->with($number);
        $invoiceStorageMock->expects($this->once())->method('setStatus')->with($status);
        $invoiceStorageMock->expects($this->once())->method('setIssueDate')->with($issueDate);
        $invoiceStorageMock->expects($this->once())->method('setDueDate')->with($dueDate);
        $invoiceStorageMock->expects($this->once())->method('setIssuingCompany')->with($companyIssuingStorageMock);
        $invoiceStorageMock->expects($this->once())->method('setBilledCompany')->with($companyBilledStorageMock);
        $invoiceStorageMock->expects($this->never())->method('saveInvoiceAndRelations');
        $app->instance(InvoiceStorageStateInterface::class, $invoiceStorageMock);

        $invoiceFactory = new InvoiceFactory();
        $invoice = $invoiceFactory->createNew(
            $number,
            $issueDate,
            $dueDate,
            $companyIssuingMock,
            $companyBilledMock,
            $status
        );


        $this->assertNotEmpty($invoice);
    }

    /**
     * @test
     */
    public function shouldCreateFromStorageState(): void
    {
        $app = $this->createApplication();
        $invoiceStorageMock = $this->createMock(InvoiceStorageStateInterface::class);
        $invoiceFactory = new InvoiceFactory();
        $invoice = $invoiceFactory->fromStorageState($invoiceStorageMock);
        $this->assertEquals($invoiceStorageMock, $invoice->getStorageState());
    }
}
