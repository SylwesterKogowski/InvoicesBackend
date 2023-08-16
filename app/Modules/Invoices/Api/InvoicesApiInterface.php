<?php

namespace App\Modules\Invoices\Api;

use App\Modules\Invoices\Api\Exceptions\RequestRejectedException;
use App\Modules\Invoices\Api\GeneratedDtos\Invoice;
use Ramsey\Uuid\UuidInterface;

interface InvoicesApiInterface
{
    /**
     * Request for Invoice data together with relations
     * @param UuidInterface $invoiceUuid
     * @throws RequestRejectedException
     */
    public function getInvoiceWithRelations(UuidInterface $invoiceUuid): Invoice;

    /**
     * Request for invoice approval for payments
     * @param UuidInterface $invoiceUuid
     * @throws RequestRejectedException
     */
    public function approveInvoiceForPayments(UuidInterface $invoiceUuid): void;

    /**
     * Request for invoice rejection for payments
     * @param UuidInterface $invoiceUuid
     * @throws RequestRejectedException
     */
    public function rejectInvoiceForPayments(UuidInterface $invoiceUuid): void;

}
