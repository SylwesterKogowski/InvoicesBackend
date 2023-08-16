<?php

namespace App\Modules\Invoices\Domain\Factories;

use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Domain\Entities\CompanyInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\CompanyStorageStateInterface;
use Illuminate\Support\Facades\App;

/**
 * Creates {@see Company} entities
 * Test: {@see \Tests\Unit\Modules\Invoices\Domain\Factories\CompanyFactoryTest}
 */
class CompanyFactory implements CompanyFactoryInterface
{
    /**
     * Creates a company from Storage.
     */
    public function fromStorageState(CompanyStorageStateInterface $companyStorageState): CompanyInterface
    {
        return new Company($companyStorageState);
    }
    /**
     * Creates a new company. Doesn't save it yet
     * @param string $name company name
     * @param string $street company street
     * @param string $city company city
     * @param string $zip company zip
     * @param string $phone company phone
     * @param string $email company email
     */
    public function createNew(
        string $name,
        string $street,
        string $city,
        string $zip,
        string $phone,
        string $email
    ): CompanyInterface {
        $companyStorageState = App::make(CompanyStorageStateInterface::class);
        $companyStorageState->setName($name);
        $companyStorageState->setStreet($street);
        $companyStorageState->setCity($city);
        $companyStorageState->setZip($zip);
        $companyStorageState->setPhone($phone);
        $companyStorageState->setEmail($email);
        return new Company($companyStorageState);
    }

}
