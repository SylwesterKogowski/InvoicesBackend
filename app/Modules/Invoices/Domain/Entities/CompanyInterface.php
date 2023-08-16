<?php

namespace App\Modules\Invoices\Domain\Entities;


use App\Modules\Invoices\Infrastructure\Database\Contracts\CompanyStorageStateInterface;
use Illuminate\Support\Carbon;
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
interface CompanyInterface
{
    public function __construct(CompanyStorageStateInterface $companyStorageState);
    /**
     * @return UuidInterface UUID
     */
    public function getId(): UuidInterface;

    /**
     * @return Carbon time when was company created
     */
    public function getCreatedAt(): Carbon;

    /**
     * @return Carbon time when was company updated
     */
    public function getUpdatedAt(): Carbon;

    /**
     * @return string company phone
     */
    public function getPhone(): string;

    /**
     * @param string $phone company phone
     */
    public function setPhone(string $phone): void;

    /**
     * @return string company name
     */
    public function getName(): string;

    /**
     * @param string $name company name
     */
    public function setName(string $name): void;

    /**
     * @return string company street
     */
    public function getStreet(): string;

    /**
     * @param string $street company street
     */
    public function setStreet(string $street): void;

    /**
     * @return string company city
     */
    public function getCity(): string;

    /**
     * @param string $city company city
     */
    public function setCity(string $city): void;

    /**
     * @return string company zip
     */
    public function getZip(): string;

    /**
     * @param string $zip company zip
     */
    public function setZip(string $zip): void;

    /**
     * @return string company email
     */
    public function getEmail(): string;

    /**
     * @param string $email company email
     */
    public function setEmail(string $email): void;

    public function getStorageState(): CompanyStorageStateInterface;
}
