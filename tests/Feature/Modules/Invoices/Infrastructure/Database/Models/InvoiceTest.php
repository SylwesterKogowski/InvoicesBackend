<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Invoices\Infrastructure\Database\Models;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Infrastructure\Database\Models\Company;
use App\Modules\Invoices\Infrastructure\Database\Models\Invoice;
use App\Modules\Invoices\Infrastructure\Database\Seeders\CompanySeeder;
use App\Modules\Invoices\Infrastructure\Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\IntegrationTestCase;

/**
 * Integration test for {@see Invoice} Eloquent model
 */
class InvoiceTest extends IntegrationTestCase
{
    use RefreshDatabase;
    public $seeder = DatabaseSeeder::class;

    public function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
    }

    /**
     * Check if model loads data.
     * @test
     */
    public function givenDatabaseSeededShouldLoadInvoiceModel(): void
    {
        /**
         * @var Invoice $model
         */
        $model = Invoice::first();
        $this->assertNotEmpty($model);
        $this->assertNotEmpty($model->id);
    }


    /**
     * Check if model inserts data.
     * @test
     * @testWith [1,2, "2020-01-01", "2020-01-01", "INV-01/01/2020", "draft"]
     *  [0,2, "2020-01-01", "2020-01-01", "INV-01/01/2020", "approved"]
     *  [1,2, "2020-01-01", "2020-01-01", "INV-01/01/2020", "rejected"]
     * @param $issuing_company_index int company row of this number will be used during testing as issuing company
     * @param $billed_company_index int company row of this number will be used during testing as billed company
     * @param $issue_date
     * @param $due_date
     * @param $invoice_number
     * @param $invoice_status
     */
    public function givenDatabaseSeededShouldInsertInvoiceModel(
        $issuing_company_index,
        $billed_company_index,
        $issue_date,
        $due_date,
        $invoice_number,
        $invoice_status
    ): void {

        $this->seed(CompanySeeder::class);

        $new_invoice_id = uuid_create();
        $model = new Invoice();
        $model->id = $new_invoice_id;
        $model->company_id = Company::query()->limit(1)->offset($issuing_company_index)->get('id')[0]['id'];
        $model->billed_company_id = Company::query()->limit(1)->offset($billed_company_index)->get('id')[0]['id'];
        $model->number = $invoice_number;
        $model->date = Carbon::createFromFormat('Y-m-d', $issue_date);
        $model->due_date = Carbon::createFromFormat('Y-m-d', $due_date);
        $model->status = $invoice_status;
        $model->save();

        $this->assertEquals(1, Invoice::query()->where('id', $new_invoice_id)->count());
    }

    /**
     * Check if model loads issuing company.
     * @test
     */
    public function givenDatabaseSeededShouldLoadIssuingCompanyModel(): void
    {
        /**
         * @var Invoice $model
         */
        $model = Invoice::first();
        $this->assertNotEmpty($model->getIssuingCompany());
    }

    /**
     * Check if model loads billing company.
     * @test
     */
    public function givenDatabaseSeededShouldLoadBillingCompanyModel(): void
    {
        /**
         * @var Invoice $model
         */
        $model = Invoice::first();
        $this->assertNotEmpty($model->getBilledCompany());
    }

    /**
     * Check if model loads product lines.
     * @test
     */
    public function givenDatabaseSeededShouldLoadProductLines(): void
    {
        /**
         * @var Invoice $model
         */
        $model = Invoice::query()->has('productLines')->inRandomOrder()->first();
        $this->assertNotEmpty($model->getProductLines());
    }
    /**
     * @test
     */
    public function whenChangeAndSaveInTransactionShouldCallCallbackInTransaction(): void
    {
        $initialTransactionLevel = DB::transactionLevel();
        /**
         * @var Invoice $model
         */
        $model = Invoice::first();
        $callback_succesfull = false;
        $model->changeAndSaveInTransaction(function () use ($initialTransactionLevel, &$callback_succesfull): void {
            if (DB::transactionLevel() == $initialTransactionLevel + 1) {
                $callback_succesfull = true;
            }
        });
        $this->assertEquals($initialTransactionLevel, DB::transactionLevel());
        $this->assertTrue($callback_succesfull);
    }
    /**
     * @test
     */
    public function whenChangeAndSaveInTransactionShouldSaveInvoiceAndRelations(): void
    {
        global $saveInvoiceAndRelationsCalled;
        $saveInvoiceAndRelationsCalled = false;
        $model = new class extends Invoice {
            public function saveInvoiceAndRelations(): void
            {
                global $saveInvoiceAndRelationsCalled;
                $saveInvoiceAndRelationsCalled = true;
            }
        };

        $model->changeAndSaveInTransaction(fn()=>null);
        $this->assertTrue($saveInvoiceAndRelationsCalled);
    }


    /**
     * @test
     */
    public function whenSaveInvoiceAndRelationsShouldSaveInTransaction(): void
    {
        global $initialTransactionLevel;
        $initialTransactionLevel = DB::transactionLevel();
        global $invoiceSaveCalledInTransaction;
        $invoiceSaveCalledInTransaction = false;
        $model = new class extends Invoice {
            public function save(array $options = []): void
            {
                global $initialTransactionLevel;
                if (DB::transactionLevel() == $initialTransactionLevel + 1) {
                    global $invoiceSaveCalledInTransaction;
                    $invoiceSaveCalledInTransaction = true;
                }
            }
        };

        $model->saveInvoiceAndRelations();

        $this->assertEquals($initialTransactionLevel, DB::transactionLevel());
        $this->assertTrue($invoiceSaveCalledInTransaction);
    }


    /**
     * @test
     */
    public function whenSaveInvoiceAndRelationsShouldSaveIssuingCompany(): void
    {

        $issuingCompany = $this->createMock(Company::class);
        $issuingCompany->expects($this->once())->method('save');

        $model = new class extends Invoice {
            public function save(array $options = []): void
            {
            }
        };

        $model->setIssuingCompany($issuingCompany);
        $model->saveInvoiceAndRelations();
    }

    /**
     * @test
     */
    public function whenSaveInvoiceAndRelationsShouldSaveBilledCompany(): void
    {

        $billedCompany = $this->createMock(Company::class);
        $billedCompany->expects($this->once())->method('save');

        $model = new class extends Invoice {
            public function save(array $options = []): void
            {
            }
        };

        $model->setBilledCompany($billedCompany);
        $model->saveInvoiceAndRelations();
    }

    /**
     * @test
     */
    public function whenSaveInvoiceAndRelationsShouldSaveProductLines(): void
    {
        global $saveProductLinesCalled;
        $saveProductLinesCalled = false;

        $model = new class extends Invoice {
            public function save(array $options = []): void
            {
            }

            protected function saveProductLines(): void
            {
                global $saveProductLinesCalled;
                $saveProductLinesCalled = true;
            }
        };
        $model->setProductLines([]);
        $model->saveInvoiceAndRelations();

        $this->assertTrue($saveProductLinesCalled);
    }


    /**
     * @test
     */
    public function shouldSetStatus() {
        $model = new Invoice();
        $model->setStatus(StatusEnum::APPROVED);
        $this->assertEquals(StatusEnum::APPROVED, $model->getStatus());
    }
}
