<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Database\Queries;

use App\Modules\Invoices\Infrastructure\Database\Models\Invoice;
use App\Modules\Invoices\Infrastructure\Database\Queries\InvoiceORMQuery;
use App\Modules\Invoices\Infrastructure\Database\Seeders\DatabaseSeeder ;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\IntegrationTestCase;

/**
 * Integration test for {@see InvoiceORMQuery}
 */
class InvoiceORMQueryIntegrationTest extends IntegrationTestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function shouldGetInvoiceById(): void
    {
        $this->seed(DatabaseSeeder::class);
        /**
         * @var Invoice $some_invoice
         */
        $some_invoice = Invoice::first();
        $invoiceORMQuery = new InvoiceORMQuery();

        /**
         * @var Invoice $resulting_invoice
         */
        $resulting_invoice = $invoiceORMQuery->getById($some_invoice->getId());
        $this->assertNotEmpty($resulting_invoice);
        $this->assertEquals($some_invoice->toArray(), $resulting_invoice->toArray());
    }
}
