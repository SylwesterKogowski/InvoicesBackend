<?php

namespace App\Modules\Invoices\Api\Mappers;

use App\Modules\Invoices\Api\GeneratedDtos\Company;
use App\Modules\Invoices\Domain\Entities\CompanyInterface;

/**
 * Test {@see \Tests\Unit\Modules\Invoices\Api\Mappers\CompanyMapperTest}
 */
class CompanyMapper implements CompanyMapperInterface
{

    public function convertDomainToDtoWithRelations(CompanyInterface $company): Company
    {
        $companyDto = new Company();
        $companyDto->setId($company->getId()->toString());
        $companyDto->setName($company->getName());
        $companyDto->setCreatedAt($company->getCreatedAt()->getTimestamp());
        $companyDto->setUpdatedAt($company->getUpdatedAt()->getTimestamp());
        $companyDto->setCity($company->getCity());
        $companyDto->setEmail($company->getEmail());
        $companyDto->setPhone($company->getPhone());
        $companyDto->setZip($company->getZip());

        return $companyDto;
    }
}
