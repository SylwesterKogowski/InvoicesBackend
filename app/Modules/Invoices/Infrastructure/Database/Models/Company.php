<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Database\Models;

use App\Modules\Invoices\Infrastructure\Database\Contracts\CompanyStorageStateInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Database model for company entities.
 * Domain entity: {@see  \App\Modules\Invoices\Domain\Entities\Company}
 *
 * @property string $id UUID
 * @property string $name company name
 * @property string $street company street
 * @property string $city company city
 * @property string $zip company zip
 * @property string $phone company phone
 * @property string $email company email
 * @property Carbon $created_at time when company record created
 * @property Carbon $updated_at time when company record updated
 */
class Company extends Model implements CompanyStorageStateInterface
{
    use HasUuids;

    protected $table = 'companies';

    /**
     * @return UuidInterface UUID
     */
    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }
    /**
     * @returns string company name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name company name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @returns string company street
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street company street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @returns string company city
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city company city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @returns string company zip
     */
    public function getZip(): string
    {
        return $this->zip;
    }

    /**
     * @param string $zip company zip
     */
    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @returns string company phone
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone company phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @returns string company email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email company email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @returns Carbon time when company record was created
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }


    /**
     * @returns Carbon time when company record was updated
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    /**
     * Invoices that this company issued
     */
    public function issuedInvoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'company_id');
    }

    /**
     * Invoices that this company was billed
     */
    public function billedInvoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'billed_company_id');
    }
}
