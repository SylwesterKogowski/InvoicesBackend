<?php

namespace App\Modules\Invoices\Domain\Factories;

use App\Modules\Invoices\Domain\Entities\CompanyInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\CompanyStorageStateInterface;

interface CompanyFactoryInterface
{
    /**
     * Creates a company from Storage.
     */
    public function fromStorageState(CompanyStorageStateInterface $companyStorageState): CompanyInterface;

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
    ): CompanyInterface;
}
