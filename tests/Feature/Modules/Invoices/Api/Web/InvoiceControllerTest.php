<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Invoices\Api\Web;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Infrastructure\Database\Models\Invoice;
use App\Modules\Invoices\Infrastructure\Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\IntegrationTestCase as TestCase;

/**
 * e2e test for {@see InvoiceController}
 */
class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public $seeder = DatabaseSeeder::class;

    public function testApprove(): void
    {
        $this->refreshDatabase();
        /**
         * @var Invoice $some_invoice
         */
        $some_invoice = Invoice::query()->where('status', StatusEnum::DRAFT->value)->first();
        $response = $this->post('/api/invoices/' . $some_invoice->id . '/approve');
        $response->assertStatus(204);
        $some_invoice->refresh();
        $this->assertEquals(StatusEnum::APPROVED, $some_invoice->getStatus());
    }

    public function testGet(): void
    {
        $some_invoice = Invoice::first();
        $response = $this->get('/api/invoices/' . $some_invoice->id, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $invoiceDto = new \App\Modules\Invoices\Api\GeneratedDtos\Invoice();

        // if this parses without exception, then the output is valid
        $invoiceDto->mergeFromJsonString($response->streamedContent());
    }

    public function testReject(): void
    {
        $this->refreshDatabase();
        /**
         * @var Invoice $some_invoice
         */
        $some_invoice = Invoice::query()->where('status', StatusEnum::DRAFT->value)->first();
        $response = $this->post('/api/invoices/' . $some_invoice->id . '/reject');
        $response->assertStatus(204);
        $some_invoice->refresh();
        $this->assertEquals(StatusEnum::REJECTED, $some_invoice->getStatus());
    }
}
