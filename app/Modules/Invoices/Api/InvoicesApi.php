<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Exceptions\ApprovalAlreadyAssignedException;
use App\Modules\Invoices\Api\Exceptions\RequestRejectedException;
use App\Modules\Invoices\Api\GeneratedDtos\Invoice;
use App\Modules\Invoices\Api\Mappers\InvoiceMapperInterface;
use App\Modules\Invoices\Domain\Entities\InvoiceInterface;
use App\Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Illuminate\Support\Facades\App;
use Ramsey\Uuid\UuidInterface;

/**
 * All API requests related to invoices handled here
 * Tests {@see \Tests\Unit\Modules\Invoices\Api\InvoicesApiTest}
 */
class InvoicesApi implements InvoicesApiInterface
{
    /**
     * @inheritDoc
     */
    public function getInvoiceWithRelations(UuidInterface $invoiceUuid): Invoice
    {
        $invoiceRepository = App::make(InvoiceRepositoryInterface::class);
        try {
            $invoice = $invoiceRepository->getInvoiceById($invoiceUuid);
        } catch (InvoiceNotFoundException $e) {
            throw new RequestRejectedException('Invoice not found with provided Id');
        }
        $mapper = App::make(InvoiceMapperInterface::class);
        return $mapper->convertDomainToDtoWithRelations($invoice);
    }

    /**
     * @inheritDoc
     */
    public function approveInvoiceForPayments(UuidInterface $invoiceUuid): void
    {
        $invoiceRepository = App::make(InvoiceRepositoryInterface::class);
        try {
            $invoice = $invoiceRepository->getInvoiceById($invoiceUuid);
        } catch (InvoiceNotFoundException $e) {
            throw new RequestRejectedException('Invoice not found with provided Id');
        }
        try {
            $invoice->approveForPayment();
        } catch (ApprovalAlreadyAssignedException $ex) {
            $this->onInvoiceAlreadyHasApproval($invoice);
        }
    }

    /**
     * @inheritDoc
     */
    public function rejectInvoiceForPayments(UuidInterface $invoiceUuid): void
    {
        $invoiceRepository = App::make(InvoiceRepositoryInterface::class);
        try {
            $invoice = $invoiceRepository->getInvoiceById($invoiceUuid);
        } catch (InvoiceNotFoundException $e) {
            throw new RequestRejectedException('Invoice not found with provided Id');
        }
        try {
            $invoice->rejectForPayment();
        } catch (ApprovalAlreadyAssignedException $ex) {
            $this->onInvoiceAlreadyHasApproval($invoice);
        }
    }

    private function onInvoiceAlreadyHasApproval(InvoiceInterface $invoice): void
    {
        switch($invoice->getStatus()) {
            case StatusEnum::APPROVED:
                throw new RequestRejectedException('Invoice was already approved for payments');
            case StatusEnum::REJECTED:
                throw new RequestRejectedException('Invoice was already rejected for payments');
            default:
                throw new \LogicException("Unsupported status {$invoice->getStatus()->name}");
        }
    }
}
