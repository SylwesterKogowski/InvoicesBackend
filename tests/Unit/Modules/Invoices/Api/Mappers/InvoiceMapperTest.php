<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Api\Mappers;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Api\GeneratedDtos\Company;
use App\Modules\Invoices\Api\GeneratedDtos\InvoiceProductLine;
use App\Modules\Invoices\Api\Mappers\CompanyMapperInterface;
use App\Modules\Invoices\Api\Mappers\InvoiceMapper;
use App\Modules\Invoices\Api\Mappers\InvoiceProductLineMapperInterface;
use App\Modules\Invoices\Domain\Entities\CompanyInterface;
use App\Modules\Invoices\Domain\Entities\InvoiceInterface;
use App\Modules\Invoices\Domain\ValueObjects\InvoiceProductLineInterface;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InvoiceMapperTest extends TestCase
{
    /**
     * We provide all the values (because we must) but test only one of those at the time
     * @test
     * @testWith ["getId"]
     *  ["getNumber"]
     *  ["getStatus"]
     *  ["getCreatedAt"]
     *  ["getUpdatedAt"]
     *  ["getIssueDate"]
     *  ["getDueDate"]
     *  ["getBilledCompany"]
     *  ["getIssuingCompany"]
     *  ["getProductLines"]
     */
    public function whenConvertDomainToDtoWithRelationsShouldLoadGetter($testedPropertyGetter): void
    {
        $propertyValues = [
            "getId" => Uuid::fromString("f1df5de8-f032-4f4b-86f5-94f5378d54b7"),
            "getBilledCompany" => $this->createMock(CompanyInterface::class),
            "getIssuingCompany" => $this->createMock(CompanyInterface::class),
            "getStatus" => StatusEnum::DRAFT,
            "getNumber" => '1/2/2023-INV',
            "getIssueDate" => Carbon::createFromFormat('Y-m-d', "2023-08-15"),
            "getDueDate" => Carbon::createFromFormat('Y-m-d', "2023-08-15"),
            "getCreatedAt" => Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00"),
            "getUpdatedAt" => Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00"),
            "getProductLines" => [$this->createMock(InvoiceProductLineInterface::class)],
        ];
        $invoiceMock = $this->createConfiguredMock(InvoiceInterface::class, $propertyValues);

        $invoiceMock->expects($this->once())
            ->method($testedPropertyGetter)
            ->willReturn($propertyValues[$testedPropertyGetter]);
        $invoiceMapper = new InvoiceMapper(
            $this->createMock(CompanyMapperInterface::class),
            $this->createMock(InvoiceProductLineMapperInterface::class)
        );

        $expectedInDto = [
            "getId" => $this->equalTo("f1df5de8-f032-4f4b-86f5-94f5378d54b7"),
            "getBilledCompany" => $this->isInstanceOf(Company::class),
            "getIssuingCompany" => $this->isInstanceOf(Company::class),
            "getNumber" => $this->equalTo('1/2/2023-INV'),
            "getStatus" => $this->equalTo(InvoiceMapper::STATUSES_MAP[StatusEnum::DRAFT->name]),
            "getIssueDate" => $this->equalTo(Carbon::createFromFormat('Y-m-d', "2023-08-15")->format('Y-m-d')),
            "getDueDate" => $this->equalTo(Carbon::createFromFormat('Y-m-d', "2023-08-15")->format('Y-m-d')),
            "getCreatedAt" => $this->equalTo(
                Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00")->getTimestamp()
            ),
            "getUpdatedAt" => $this->equalTo(
                Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00")->getTimestamp()
            ),
            "getProductLines" => $this->containsOnlyInstancesOf(InvoiceProductLine::class),
        ];
        $invoiceProductLineDto = $invoiceMapper->convertDomainToDtoWithRelations($invoiceMock);
        $this->assertThat($invoiceProductLineDto->{$testedPropertyGetter}(), $expectedInDto[$testedPropertyGetter]);
    }
}
