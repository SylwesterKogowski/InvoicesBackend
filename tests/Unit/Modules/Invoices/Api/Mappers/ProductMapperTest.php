<?php

namespace Tests\Unit\Modules\Invoices\Api\Mappers;

use App\Modules\Invoices\Api\Mappers\ProductMapper;
use App\Modules\Invoices\Domain\Entities\ProductInterface;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Tests {@see ProductMapper}
 */
class ProductMapperTest extends TestCase
{
    /**
     * @test
     * @testWith ["getId"]
     * ["getCurrency"]
     * ["getCreatedAt"]
     * ["getUpdatedAt"]
     * ["getBruttoPrice"]
     * ["getName"]
     */
    public function whenConvertDomainToDtoWithRelationsShouldLoadGetter($testedPropertyGetter): void
    {
        $propertyValues = [
            "getId" => Uuid::fromString("f1df5de8-f032-4f4b-86f5-94f5378d54b7"),
            "getCurrency" => "USD",
            "getBruttoPrice" => 1500,
            "getName" => "Apples",
            "getCreatedAt" => Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00"),
            "getUpdatedAt" => Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00"),
        ];
        $productMock = $this->createConfiguredMock(ProductInterface::class, $propertyValues);

        $productMock->expects($this->once())
            ->method($testedPropertyGetter)
            ->willReturn($propertyValues[$testedPropertyGetter]);
        $productMapper = new ProductMapper();

        $expectedInDto = [
            "getId" => "f1df5de8-f032-4f4b-86f5-94f5378d54b7",
            "getCurrency" => "USD",
            "getBruttoPrice" => 1500,
            "getName" => "Apples",
            "getCreatedAt" => Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00")->getTimestamp(),
            "getUpdatedAt" => Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00")->getTimestamp(),
        ];
        $productDto = $productMapper->convertDomainToDtoWithRelations($productMock);
        $this->assertEquals($expectedInDto[$testedPropertyGetter], $productDto->{$testedPropertyGetter}());
    }

}
