<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Api\Mappers;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Api\GeneratedDtos\Product;
use App\Modules\Invoices\Api\Mappers\InvoiceProductLineMapper;
use App\Modules\Invoices\Api\Mappers\ProductMapperInterface;
use App\Modules\Invoices\Domain\Entities\CompanyInterface;
use App\Modules\Invoices\Domain\Entities\ProductInterface;
use App\Modules\Invoices\Domain\ValueObjects\InvoiceProductLineInterface;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InvoiceProductLineMapperTest extends TestCase
{
    /**
     * @test
     * @testWith ["getProduct"]
     *  ["getQuantity"]
     *  ["getCreatedAt"]
     */
    public function whenConvertDomainToDtoWithRelationsShouldLoadGetter($testedPropertyGetter): void
    {
        $propertyValues = [
            "getProduct" => $this->createMock(ProductInterface::class),
            "getQuantity" => 20,
            "getCreatedAt" => Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00"),
        ];
        $invoiceProductLineMock = $this->createConfiguredMock(InvoiceProductLineInterface::class, $propertyValues);

        $invoiceProductLineMock->expects($this->once())
            ->method($testedPropertyGetter)
            ->willReturn($propertyValues[$testedPropertyGetter]);

        $invoiceProductLineMapper = new InvoiceProductLineMapper(
            $this->createMock(ProductMapperInterface::class)
        );

        $expectedInDto = [
            "getProduct" => $this->isInstanceOf(Product::class),
            "getQuantity" => $this->equalTo(20),
            "getCreatedAt" => $this->equalTo(Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00")
                ->getTimestamp()),
        ];
        $invoiceProductLineDto = $invoiceProductLineMapper->convertDomainToDtoWithRelations($invoiceProductLineMock);
        $this->assertThat($invoiceProductLineDto->{$testedPropertyGetter}(), $expectedInDto[$testedPropertyGetter]);
    }
}
