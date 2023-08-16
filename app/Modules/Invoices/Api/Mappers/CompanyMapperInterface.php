<?php

namespace App\Modules\Invoices\Api\Mappers;

use App\Modules\Invoices\Api\GeneratedDtos\Company;
use App\Modules\Invoices\Domain\Entities\CompanyInterface;

interface CompanyMapperInterface
{
    public function convertDomainToDtoWithRelations(CompanyInterface $company): Company;
}
