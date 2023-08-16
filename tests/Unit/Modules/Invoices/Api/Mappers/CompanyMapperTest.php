<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Api\Mappers;

use App\Modules\Invoices\Api\Mappers\CompanyMapper;
use App\Modules\Invoices\Domain\Entities\CompanyInterface;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Tests {@see CompanyMapper}
 */
class CompanyMapperTest extends TestCase
{
    /**
     * @test
     * @testWith ["getId"]
     * ["getName"]
     * ["getCity"]
     * ["getEmail"]
     * ["getPhone"]
     * ["getZip"]
     * ["getCreatedAt"]
     * ["getUpdatedAt"]
     */
    public function whenConvertDomainToDtoWithRelationsShouldLoadGetter($testedPropertyGetter): void
    {
        $propertyValues = [
            "getId" => Uuid::fromString("f1df5de8-f032-4f4b-86f5-94f5378d54b7"),
            "getName" => "Andrew Smith",
            "getCity" => "New York",
            "getEmail" => "gorgonzola@und.fasola",
            "getPhone" => "+01 123 456 789",
            "getZip" => "5021JA",
            "getCreatedAt" => Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00"),
            "getUpdatedAt" => Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00"),
        ];
        $companyMock = $this->createConfiguredMock(CompanyInterface::class, $propertyValues);

        $companyMock->expects($this->once())
            ->method($testedPropertyGetter)
            ->willReturn($propertyValues[$testedPropertyGetter]);
        $companyMapper = new CompanyMapper();

        $expectedInDto = [
            "getId" => "f1df5de8-f032-4f4b-86f5-94f5378d54b7",
            "getName" => "Andrew Smith",
            "getCity" => "New York",
            "getEmail" => "gorgonzola@und.fasola",
            "getPhone" => "+01 123 456 789",
            "getZip" => "5021JA",
            "getCreatedAt" => Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00")->getTimestamp(),
            "getUpdatedAt" => Carbon::createFromFormat('Y-m-d H:i:s', "2023-08-15 15:00:00")->getTimestamp(),
        ];

        $companyDto = $companyMapper->convertDomainToDtoWithRelations($companyMock);
        $this->assertEquals($expectedInDto[$testedPropertyGetter], $companyDto->{$testedPropertyGetter}());
    }
}
