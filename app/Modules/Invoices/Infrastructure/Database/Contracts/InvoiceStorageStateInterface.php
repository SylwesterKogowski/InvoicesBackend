<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Database\Contracts;

use App\Domain\Enums\StatusEnum;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Interface InvoiceStorageStateInterface
 * Maintains the state of the entity, but saves the changes only on save() method call.
 */
interface InvoiceStorageStateInterface
{
    /**
     * @return string invoice number
     */
    public function getNumber(): string;

    /**
     * @return StatusEnum status of invoice approval
     */
    public function getStatus(): StatusEnum;

    /**
     * @return Carbon invoice issuance date
     */
    public function getIssueDate(): Carbon;

    /**
     * @param Carbon $date invoice issuance date
     */
    public function setIssueDate(Carbon $date): void;


    /**
     * @return Carbon invoice due date
     */
    public function getDueDate(): Carbon;

    /**
     * @return Carbon time when invoice record was created
     */
    public function getCreatedAt(): Carbon;

    /**
     * @return Carbon time when invoice record was updated
     */
    public function getUpdatedAt(): Carbon;

    /**
     * @param string $number invoice number
     */
    public function setNumber(string $number): void;

    /**
     * @return UuidInterface UUID
     */
    public function getId(): UuidInterface;

    /**
     * @param Carbon $due_date invoice due date
     */
    public function setDueDate(Carbon $due_date): void;

    /**
     * @param StatusEnum $status status of invoice approval
     */
    public function setStatus(StatusEnum $status): void;

    /**
     * @return CompanyStorageStateInterface company that issued the invoice
     */
    public function getIssuingCompany(): CompanyStorageStateInterface;

    /**
     * @return CompanyStorageStateInterface company that is billed
     */
    public function getBilledCompany(): CompanyStorageStateInterface;

    /**
     * Gets all the product lines for this invoice
     * @return InvoiceProductLineStorageStateInterface[]
     */
    public function getProductLines(): array;

    /**
     * Sets all the product lines for this invoice
     * @param InvoiceProductLineStorageStateInterface[] $productLines
     */
    public function setProductLines(array $productLines): void;

    /**
     * Sets the issuing company for the invoice
     * @param CompanyStorageStateInterface $issuingCompanyORM
     */
    public function setIssuingCompany(CompanyStorageStateInterface $issuingCompanyORM): void;

    /**
     * Sets the billed company for the invoice
     * @param CompanyStorageStateInterface $billedCompanyORM
     */
    public function setBilledCompany(CompanyStorageStateInterface $billedCompanyORM): void;

    /**
     * Saves changes to this invoice and all changed relations
     * if impossible, throw exception and don't save anything
     */
    public function saveInvoiceAndRelations(): void;

    /**
     * Load any changes from storage to this model
     */
    public function refreshInvoice(): void;

    /**
     * Changes this invoice in one unit of work
     */
    public function changeAndSaveInTransaction(callable $callback): void;

    /**
     * Checks if this invoice exists already in storage and has id assigned
     */
    public function isInStorage(): bool;
}
