<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Providers;

use App\Modules\Invoices\Domain\Entities\Company;
use App\Modules\Invoices\Domain\Entities\CompanyInterface;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Entities\InvoiceInterface;
use App\Modules\Invoices\Domain\Entities\Product;
use App\Modules\Invoices\Domain\Entities\ProductInterface;
use App\Modules\Invoices\Domain\Factories\CompanyFactory;
use App\Modules\Invoices\Domain\Factories\CompanyFactoryInterface;
use App\Modules\Invoices\Domain\Factories\InvoiceFactory;
use App\Modules\Invoices\Domain\Factories\InvoiceFactoryInterface;
use App\Modules\Invoices\Domain\Factories\InvoiceProductLineFactory;
use App\Modules\Invoices\Domain\Factories\InvoiceProductLineFactoryInterface;
use App\Modules\Invoices\Domain\Factories\ProductFactory;
use App\Modules\Invoices\Domain\Factories\ProductFactoryInterface;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepository;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use App\Modules\Invoices\Domain\ValueObjects\InvoiceProductLine;
use App\Modules\Invoices\Domain\ValueObjects\InvoiceProductLineInterface;
use Illuminate\Support\ServiceProvider;

class InvoiceDomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scopedIf(InvoiceRepositoryInterface::class, function () {
            return $this->app->make(InvoiceRepository::class);
        });

        $this->app->singletonIf(InvoiceFactoryInterface::class, InvoiceFactory::class);
        $this->app->bindIf(InvoiceInterface::class, Invoice::class);

        $this->app->singletonIf(ProductFactoryInterface::class, ProductFactory::class);
        $this->app->bindIf(ProductInterface::class, Product::class);


        $this->app->singletonIf(CompanyFactoryInterface::class, CompanyFactory::class);
        $this->app->bindIf(CompanyInterface::class, Company::class);


        $this->app->singletonIf(InvoiceProductLineFactoryInterface::class, InvoiceProductLineFactory::class);
        $this->app->bindIf(InvoiceProductLineInterface::class, InvoiceProductLine::class);
    }

    /**
     * @return string[]
     */
    public function provides(): array
    {
        return [
            InvoiceFactoryInterface::class,
            InvoiceInterface::class,
            ProductFactoryInterface::class,
            ProductInterface::class,
            CompanyInterface::class,
            CompanyFactoryInterface::class,
            InvoiceRepositoryInterface::class,
            InvoiceProductLineFactoryInterface::class,
            InvoiceProductLineInterface::class,
        ];
    }
}
