<?php

namespace App\Modules\Invoices\Domain\Entities;


use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Exceptions\ApprovalAlreadyAssignedException;
use App\Modules\Invoices\Domain\ValueObjects\InvoiceProductLineInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageStateInterface;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\UuidInterface;

/**
 * Description in implementation: {@see Invoice}
 */
interface InvoiceInterface
{

    public function __construct(InvoiceStorageStateInterface $invoiceStorageState);
    /**
     * @return CompanyInterface company that is billed by the invoice
     */
    public function getBilledCompany(): CompanyInterface;

    /**
     * @param CompanyInterface $billedCompany company that is billed by the invoice
     */
    public function setBilledCompany(CompanyInterface $billedCompany): void;

    /**
     * @return CompanyInterface company that issued the invoice
     */
    public function getIssuingCompany(): CompanyInterface;

    /**
     * @param CompanyInterface $issuingCompany company that issued the invoice
     */
    public function setIssuingCompany(CompanyInterface $issuingCompany): void;

    /**
     * @return InvoiceProductLineInterface[] product lines billed on the invoice
     */
    public function getProductLines(): array;

    public function getStorageState(): InvoiceStorageStateInterface;

    /**
     * @return Carbon time when invoice record was updated
     */
    public function getUpdatedAt(): Carbon;

    /**
     * @return Carbon time when invoice record was created
     */
    public function getCreatedAt(): Carbon;

    /**
     * @return StatusEnum status of invoice approval
     */
    public function getStatus(): StatusEnum;

    /**
     * @param StatusEnum $status status of invoice approval
     */
    public function setStatus(StatusEnum $status): void;

    /**
     * @return Carbon invoice due date
     */
    public function getDueDate(): Carbon;

    /**
     * @param Carbon $dueDate invoice due date
     */
    public function setDueDate(Carbon $dueDate): void;

    /**
     * @return Carbon invoice issuance date
     */
    public function getIssueDate(): Carbon;

    /**
     * @param Carbon $issueDate invoice issuance date
     */
    public function setIssueDate(Carbon $issueDate): void;

    /**
     * @return string invoice number
     */
    public function getNumber(): string;

    /**
     * @param string $number invoice number
     */
    public function setNumber(string $number): void;

    /**
     * @return UuidInterface UUID
     */
    public function getId(): UuidInterface;

    /**
     * Try to approve the invoice for payment and if succeded, save the state of invoice
     * @throws ApprovalAlreadyAssignedException
     */
    public function approveForPayment(): void;

    /**
     * Try to reject payment for this invoice and if succeded, save the state of invoice
     * @throws ApprovalAlreadyAssignedException
     */
    public function rejectForPayment(): void;

    public function save(): void;

    /**
     * Reload changes to this entity from storage, discarding any current changes
     */
    public function reload(): void;
}
