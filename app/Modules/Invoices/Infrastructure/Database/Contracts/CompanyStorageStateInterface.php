<?php

namespace App\Modules\Invoices\Infrastructure\Database\Contracts;

use App\Modules\Invoices\Infrastructure\Database\Models\Company;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\UuidInterface;

interface CompanyStorageStateInterface
{

    /**
     * @returns string company phone
     */
    public function getPhone(): string;

    /**
     * @returns string company street
     */
    public function getStreet(): string;

    /**
     * @param string $zip company zip
     */
    public function setZip(string $zip): void;

    /**
     * @param string $city company city
     */
    public function setCity(string $city): void;

    /**
     * @param string $email company email
     */
    public function setEmail(string $email): void;


    /**
     * @returns Carbon time when company record was created
     */
    public function getCreatedAt(): Carbon;

    /**
     * @param string $phone company phone
     */
    public function setPhone(string $phone): void;

    /**
     * @returns string company zip
     */
    public function getZip(): string;

    /**
     * @param string $street company street
     */
    public function setStreet(string $street): void;

    /**
     * @returns string company city
     */
    public function getCity(): string;

    /**
     * @param string $name company name
     */
    public function setName(string $name): void;

    /**
     * @returns Carbon time when company record was updated
     */
    public function getUpdatedAt(): Carbon;

    /**
     * @return UuidInterface UUID
     */
    public function getId(): UuidInterface;


    /**
     * @returns string company email
     */
    public function getEmail(): string;

    /**
     * @returns string company name
     */
    public function getName(): string;
}
