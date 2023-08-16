<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Entities;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Invoices\Domain\Factories\CompanyFactoryInterface;
use App\Modules\Invoices\Domain\Factories\InvoiceProductLineFactoryInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceProductLineStorageStateInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageStateInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Ramsey\Uuid\UuidInterface;

/**
 * Represents an invoice in the domain.
 * This is the core of the invoice domain.
 * Repository: {@see \App\Modules\Invoices\Domain\Repositories\InvoiceRepository}
 * Database model: {@see \App\Modules\Invoices\Infrastructure\Database\Models\Invoice}
 * Dto: {@link ../../Api/MessageSchemas/Invoice.proto}
 * Tests: {@see \Tests\Unit\Modules\Invoices\Domain\Entities\InvoiceTest}
 * When changing read {@link InvoiceFuture.md}
 *  Technical:
 *  Adapter over storage, lazy loads relations
 */
class Invoice implements InvoiceInterface
{
    /**
     * @var CompanyInterface|null lazy loaded issuing company
     */
    private ?Company $issuingCompany = null;
    /**
     * @var CompanyInterface|null lazy loaded billed company
     */
    private ?Company $billedCompany = null;

    private $productLinesCache = [];

    public function __construct(
        private InvoiceStorageStateInterface $invoiceStorageState
    ) {
    }


    /**
     * @return CompanyInterface company that is billed by the invoice
     */
    public function getBilledCompany(): CompanyInterface
    {
        if (null === $this->billedCompany) {
            $companyFactory = App::make(CompanyFactoryInterface::class);
            $this->billedCompany = $companyFactory->fromStorageState($this->invoiceStorageState->getBilledCompany());
        }
        return $this->billedCompany;
    }

    /**
     * @param CompanyInterface $billedCompany company that is billed by the invoice
     */
    public function setBilledCompany(CompanyInterface $billedCompany): void
    {
        $this->invoiceStorageState->setBilledCompany($billedCompany->getStorageState());
        $this->billedCompany = $billedCompany;
    }

    /**
     * @return CompanyInterface company that issued the invoice
     */
    public function getIssuingCompany(): CompanyInterface
    {
        if (is_null($this->issuingCompany)) {
            $companyFactory = App::make(CompanyFactoryInterface::class);
            $this->issuingCompany = $companyFactory->fromStorageState($this->invoiceStorageState->getIssuingCompany());
        }
        return $this->issuingCompany;
    }

    /**
     * @param CompanyInterface $issuingCompany company that issued the invoice
     */
    public function setIssuingCompany(CompanyInterface $issuingCompany): void
    {
        $this->issuingCompany = $issuingCompany;
        $this->invoiceStorageState->setIssuingCompany($issuingCompany->getStorageState());
    }

    public function getStorageState(): InvoiceStorageStateInterface
    {
        return $this->invoiceStorageState;
    }

    /**
     * @return Carbon time when invoice record was updated
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->invoiceStorageState->getUpdatedAt();
    }

    /**
     * @return Carbon time when invoice record was created
     */
    public function getCreatedAt(): Carbon
    {
        return $this->invoiceStorageState->getCreatedAt();
    }

    /**
     * @return StatusEnum status of invoice approval
     */
    public function getStatus(): StatusEnum
    {
        return $this->invoiceStorageState->getStatus();
    }

    /**
     * @param StatusEnum $status status of invoice approval
     */
    public function setStatus(StatusEnum $status): void
    {
        $this->invoiceStorageState->setStatus($status);
    }

    /**
     * @return Carbon invoice due date
     */
    public function getDueDate(): Carbon
    {
        return $this->invoiceStorageState->getDueDate();
    }

    /**
     * @param Carbon $dueDate invoice due date
     */
    public function setDueDate(Carbon $dueDate): void
    {
        $this->invoiceStorageState->setDueDate($dueDate);
    }

    /**
     * @return Carbon invoice issuance date
     */
    public function getIssueDate(): Carbon
    {
        return $this->invoiceStorageState->getIssueDate();
    }

    /**
     * @param Carbon $issueDate invoice issuance date
     */
    public function setIssueDate(Carbon $issueDate): void
    {
        $this->invoiceStorageState->setIssueDate($issueDate);
    }

    /**
     * @return string invoice number
     */
    public function getNumber(): string
    {
        return $this->invoiceStorageState->getNumber();
    }

    /**
     * @param string $number invoice number
     */
    public function setNumber(string $number): void
    {
        $this->invoiceStorageState->setNumber($number);
    }

    /**
     * @return UuidInterface UUID
     */
    public function getId(): UuidInterface
    {
        return $this->invoiceStorageState->getId();
    }

    /**
     * Try to approve the invoice for payment and if succeded, save the state of invoice
     */
    public function approveForPayment(): void
    {
        //Save any changes
        $this->save();
        $approvalManager = App::make(ApprovalFacadeInterface::class);
        $this->invoiceStorageState->changeAndSaveInTransaction(
            function () use ($approvalManager): void {
                $this->reload();
                $currentApprovalStatus = new ApprovalDto(
                    $this->invoiceStorageState->getId(),
                    $this->getStatus(),
                    self::class
                );
                if ($approvalManager->approve($currentApprovalStatus)) {
                    $this->invoiceStorageState->setStatus(StatusEnum::APPROVED);
                }
            }
        );
    }
    /**
     * Try to reject payment for this invoice and if succeded, save the state of invoice
     */
    public function rejectForPayment(): void
    {
        //Save any changes
        $this->save();
        $approvalManager = App::make(ApprovalFacadeInterface::class);
        $this->invoiceStorageState->changeAndSaveInTransaction(
            function () use ($approvalManager): void {
                $this->reload();
                $currentApprovalStatus = new ApprovalDto(
                    $this->invoiceStorageState->getId(),
                    $this->getStatus(),
                    self::class
                );
                if ($approvalManager->reject($currentApprovalStatus)) {
                    $this->invoiceStorageState->setStatus(StatusEnum::REJECTED);
                }
            }
        );
    }

    public function save(): void
    {
        $this->invoiceStorageState->saveInvoiceAndRelations();
    }

    /**
     * Reload changes to this entity from storage, discarding any current changes
     */
    public function reload(): void
    {
        $this->invoiceStorageState->refreshInvoice();
    }
    /**
     * @inheritDoc
     */
    public function getProductLines(): array
    {
        $productLineFactory = App::make(InvoiceProductLineFactoryInterface::class);
        return array_map(
            /**
             * @param InvoiceProductLineStorageStateInterface $productLine
             */
            function ($productLine) use ($productLineFactory) {
                $productUuid = $productLine->getProduct()->getId()->toString();
                if (!isset($this->productLinesCache[$productUuid])) {
                    $this->productLinesCache[$productUuid] = $productLineFactory->fromStorageState($productLine);
                }
                return $this->productLinesCache[$productUuid];
            },
            $this->invoiceStorageState->getProductLines()
        );
    }
}
