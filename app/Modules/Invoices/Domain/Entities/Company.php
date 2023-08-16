<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Entities;

use App\Modules\Invoices\Infrastructure\Database\Contracts\CompanyStorageStateInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Ramsey\Uuid\UuidInterface;

/**
 * Represents a company for invoices.
 * Can be an invoice issuing company or a recipient (billed) company or both at the same time.
 * Database model: {@see \App\Modules\Invoices\Infrastructure\Database\Models\Company}
 * Dto: {@link ../../Api/MessageSchemas/Company.proto}
 * Tests: {@see \Tests\Unit\Modules\Invoices\Domain\Entities\CompanyTest}
 * When changing read {@link CompanyFuture.md}
 *  Technical:
 *  Adapter over storage, lazy loads relations
 */
class Company implements CompanyInterface
{
    public function __construct(
        private CompanyStorageStateInterface $companyStorageState
    ) {
    }
    /**
     * @return UuidInterface UUID
     */
    public function getId(): UuidInterface
    {
        return $this->companyStorageState->getId();
    }

    /**
     * @return Carbon time when was company created
     */
    public function getCreatedAt(): Carbon
    {
        return $this->companyStorageState->getCreatedAt();
    }

    /**
     * @return Carbon time when was company updated
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->companyStorageState->getUpdatedAt();
    }
    /**
     * @return string company phone
     */
    public function getPhone(): string
    {
        return $this->companyStorageState->getPhone();
    }

    /**
     * @param string $phone company phone
     */
    public function setPhone(string $phone): void
    {
        $this->companyStorageState->setPhone($phone);
    }
    /**
     * @return string company name
     */
    public function getName(): string
    {
        return $this->companyStorageState->getName();
    }

    /**
     * @param string $name company name
     */
    public function setName(string $name): void
    {
        $this->companyStorageState->setName($name);
    }

    /**
     * @return string company street
     */
    public function getStreet(): string
    {
        return $this->companyStorageState->getStreet();
    }

    /**
     * @param string $street company street
     */
    public function setStreet(string $street): void
    {
        $this->companyStorageState->setStreet($street);
    }

    /**
     * @return string company city
     */
    public function getCity(): string
    {
        return $this->companyStorageState->getCity();
    }

    /**
     * @param string $city company city
     */
    public function setCity(string $city): void
    {
        $this->companyStorageState->setCity($city);
    }

    /**
     * @return string company zip
     */
    public function getZip(): string
    {
        return $this->companyStorageState->getZip();
    }

    /**
     * @param string $zip company zip
     */
    public function setZip(string $zip): void
    {
        $this->companyStorageState->setZip($zip);
    }
    /**
     * @return string company email
     */
    public function getEmail(): string
    {
        return $this->companyStorageState->getEmail();
    }

    /**
     * @param string $email company email
     */
    public function setEmail(string $email): void
    {
        $this->companyStorageState->setEmail($email);
    }

    public function getStorageState(): CompanyStorageStateInterface
    {
        return $this->companyStorageState;
    }
}
