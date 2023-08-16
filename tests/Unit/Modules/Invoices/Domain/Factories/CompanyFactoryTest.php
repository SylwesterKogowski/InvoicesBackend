<?php

namespace Tests\Unit\Modules\Invoices\Domain\Factories;

use App\Modules\Invoices\Domain\Factories\CompanyFactory;
use App\Modules\Invoices\Infrastructure\Database\Contracts\CompanyStorageStateInterface;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

/**
 * Tests {@see CompanyFactory}
 */
class CompanyFactoryTest extends TestCase
{
    use CreatesApplication;

    /**
     * @test
     */
    public function shouldCreateFromStorageState(): void
    {
        $app = $this->createApplication();
        $companyStorageMock = $this->createMock(CompanyStorageStateInterface::class);
        $factory = new CompanyFactory();
        $company = $factory->fromStorageState($companyStorageMock);
        $this->assertEquals($companyStorageMock, $company->getStorageState());
    }

    /**
     * @test
     * @testWith ["Company Name", "Elm Street", "New York", "12345", "123456789","some@email.net"]
     */
    public function shouldCreateNew($name, $street, $city, $postcode, $phone, $email): void
    {
        $app = $this->createApplication();
        $companyStorageMock = $this->createMock(CompanyStorageStateInterface::class);
        $companyStorageMock->expects($this->once())->method('setName')
            ->with($this->equalTo($name));
        $companyStorageMock->expects($this->once())->method('setStreet')
            ->with($this->equalTo($street));
        $companyStorageMock->expects($this->once())->method('setCity')
            ->with($this->equalTo($city));
        $companyStorageMock->expects($this->once())->method('setEmail')
            ->with($this->equalTo($email));
        $companyStorageMock->expects($this->once())->method('setPhone')
            ->with($this->equalTo($phone));
        $companyStorageMock->expects($this->once())->method('setZip')
            ->with($this->equalTo($postcode));
        $app = $this->createApplication();
        $app->instance(CompanyStorageStateInterface::class, $companyStorageMock);
        $factory = new CompanyFactory();
        $company = $factory->createNew(
            $name,
            $street,
            $city,
            $postcode,
            $phone,
            $email
        );
        $this->assertNotEmpty($company);
    }
}
