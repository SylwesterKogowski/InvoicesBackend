<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Factories;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Domain\Entities\CompanyInterface;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Entities\InvoiceInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageStateInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

/**
 * Creates {@see \App\Modules\Invoices\Domain\Entities\Invoice} entities
 * Tests: {@see \Tests\Unit\Modules\Invoices\Domain\Factories\InvoiceFactoryTest}
 */
class InvoiceFactory implements InvoiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function fromStorageState(InvoiceStorageStateInterface $invoiceStorageState): InvoiceInterface
    {
        return new Invoice($invoiceStorageState);
    }

    /**
     * @inheritDoc
     */
    public function createNew(
        string $number,
        Carbon $issueDate,
        Carbon $dueDate,
        CompanyInterface $issuingCompany,
        CompanyInterface $billedCompany,
        StatusEnum $status = StatusEnum::DRAFT
    ): InvoiceInterface {
        $invoiceORM = App::make(InvoiceStorageStateInterface::class);
        $newInvoice = new Invoice($invoiceORM);
        $newInvoice->setBilledCompany($billedCompany);
        $newInvoice->setIssuingCompany($issuingCompany);
        $newInvoice->setNumber($number);
        $newInvoice->setIssueDate($issueDate);
        $newInvoice->setDueDate($dueDate);
        $newInvoice->setStatus($status);
        return $newInvoice;
    }
}
