<?php

namespace App\Modules\Invoices\Api\Providers;

use App\Modules\Invoices\Api\InvoicesApi;
use App\Modules\Invoices\Api\InvoicesApiInterface;
use App\Modules\Invoices\Api\Mappers\CompanyMapper;
use App\Modules\Invoices\Api\Mappers\CompanyMapperInterface;
use App\Modules\Invoices\Api\Mappers\InvoiceMapper;
use App\Modules\Invoices\Api\Mappers\InvoiceMapperInterface;
use App\Modules\Invoices\Api\Mappers\InvoiceProductLineMapper;
use App\Modules\Invoices\Api\Mappers\InvoiceProductLineMapperInterface;
use App\Modules\Invoices\Api\Mappers\ProductMapper;
use App\Modules\Invoices\Api\Mappers\ProductMapperInterface;
use Illuminate\Support\ServiceProvider;

class InvoiceApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bindIf(InvoicesApiInterface::class, InvoicesApi::class);
        $this->app->bindIf(InvoiceMapperInterface::class, InvoiceMapper::class);
        $this->app->bindIf(CompanyMapperInterface::class, CompanyMapper::class);
        $this->app->bindIf(ProductMapperInterface::class, ProductMapper::class);
        $this->app->bindIf(InvoiceProductLineMapperInterface::class, InvoiceProductLineMapper::class);
    }

}
