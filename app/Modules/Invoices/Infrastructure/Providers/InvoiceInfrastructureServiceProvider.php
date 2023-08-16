<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Providers;

use App\Modules\Invoices\Infrastructure\Database\Contracts\CompanyStorageStateInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceProductLineStorageStateInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageQueryInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageStateInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\ProductStorageStateInterface;
use App\Modules\Invoices\Infrastructure\Database\Models\Company;
use App\Modules\Invoices\Infrastructure\Database\Models\Invoice;
use App\Modules\Invoices\Infrastructure\Database\Models\InvoiceProductLine;
use App\Modules\Invoices\Infrastructure\Database\Models\Product;
use App\Modules\Invoices\Infrastructure\Database\Queries\InvoiceORMQuery;
use Illuminate\Support\ServiceProvider;

class InvoiceInfrastructureServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bindIf(InvoiceStorageStateInterface::class, Invoice::class);
        $this->app->bindIf(CompanyStorageStateInterface::class, Company::class);
        $this->app->bindIf(ProductStorageStateInterface::class, Product::class);
        $this->app->bindIf(InvoiceProductLineStorageStateInterface::class, InvoiceProductLine::class);
        $this->app->singletonIf(InvoiceStorageQueryInterface::class, InvoiceORMQuery::class);
    }

    /**
     * @return string[]
     */
    public function provides(): array
    {
        return [InvoiceStorageStateInterface::class,
            CompanyStorageStateInterface::class,ProductStorageStateInterface::class,
            InvoiceProductLineStorageStateInterface::class,InvoiceStorageQueryInterface::class];
    }
}
