<?php

namespace App\Modules\Invoices\Domain\Factories;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Domain\Entities\CompanyInterface;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Entities\InvoiceInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageStateInterface;
use Illuminate\Support\Carbon;

interface InvoiceFactoryInterface
{
    /**
     * Creates an invoice from ORM.
     * @param InvoiceStorageStateInterface $invoiceStorageState
     */
    public function fromStorageState(InvoiceStorageStateInterface $invoiceStorageState): InvoiceInterface;

    /**
     * Creates a new invoice. Doesn't save it yet
     * @param string $number invoice number
     * @param Carbon $issueDate invoice issuance date
     * @param Carbon $dueDate invoice due date
     * @param CompanyInterface $issuingCompany invoice issuing company
     * @param CompanyInterface $billedCompany invoice billed company
     * @param StatusEnum $status invoice status
     */
    public function createNew(
        string $number,
        Carbon $issueDate,
        Carbon $dueDate,
        CompanyInterface $issuingCompany,
        CompanyInterface $billedCompany,
        StatusEnum $status = StatusEnum::DRAFT
    ): InvoiceInterface;
}
