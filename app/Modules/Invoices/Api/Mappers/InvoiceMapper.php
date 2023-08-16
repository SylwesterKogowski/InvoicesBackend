<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api\Mappers;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Api\GeneratedDtos\Invoice;
use App\Modules\Invoices\Domain\Entities\InvoiceInterface;

/**
 * Converts Invoice domain object to Invoice DTO
 * Tests: {@see \Tests\Unit\Modules\Invoices\Api\Mappers\InvoiceMapperTest}
 */
class InvoiceMapper implements InvoiceMapperInterface
{
    public const STATUSES_MAP = [
        StatusEnum::DRAFT->name => 0,
        StatusEnum::APPROVED->name => 1,
        StatusEnum::REJECTED->name => 2,
    ];
    public function __construct(
        private CompanyMapperInterface $companyMapper,
        private InvoiceProductLineMapperInterface $invoiceProductLineMapper
    ) {
    }
    public function convertDomainToDtoWithRelations(InvoiceInterface $invoice): Invoice
    {
        $invoiceDto = new Invoice();
        $invoiceDto->setId($invoice->getId()->toString());
        $invoiceDto->setNumber($invoice->getNumber());
        $invoiceDto->setStatus(self::STATUSES_MAP[$invoice->getStatus()->name]);
        $invoiceDto->setCreatedAt($invoice->getCreatedAt()->getTimestamp());
        $invoiceDto->setUpdatedAt($invoice->getUpdatedAt()->getTimestamp());
        $invoiceDto->setIssueDate($invoice->getIssueDate()->format('Y-m-d'));
        $invoiceDto->setDueDate($invoice->getDueDate()->format('Y-m-d'));

        $invoiceDto->setBilledCompany(
            $this->companyMapper->convertDomainToDtoWithRelations($invoice->getBilledCompany())
        );
        $invoiceDto->setIssuingCompany(
            $this->companyMapper->convertDomainToDtoWithRelations($invoice->getIssuingCompany())
        );

        $invoiceDto->setProductLines(
            array_map(
                fn ($productLine) =>
                $this->invoiceProductLineMapper->convertDomainToDtoWithRelations($productLine),
                $invoice->getProductLines()
            )
        );

        return $invoiceDto;
    }
}
