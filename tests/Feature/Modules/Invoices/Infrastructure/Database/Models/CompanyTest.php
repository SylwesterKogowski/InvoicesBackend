<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Database\Models;

use App\Modules\Invoices\Infrastructure\Database\Models\Company;
use App\Modules\Invoices\Infrastructure\Database\Seeders\CompanySeeder;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\IntegrationTestCase;

/**
 * Integration test for {@see Company} Eloquent model
 */
class CompanyTest extends IntegrationTestCase
{
    use RefreshDatabase;


    /**
     * @test
     * Check if model loads data.
     */
    public function givenCompaniesSeededShouldLoadCompanyModel(): void
    {
        $this->seed(CompanySeeder::class);
        /**
         * @var Company $model
         */
        $model = Company::first();
        $this->assertNotEmpty($model);
        $this->assertNotEmpty($model->id);
    }
    /**
     * @test
     * Check if model inserts data.
     */
    public function shouldInsertCompanyModel(): void
    {
        $faker = Factory::create();

        $new_company_id = uuid_create();
        $model = new Company();
        $model->id = $new_company_id;
        $model->city = $faker->city();
        $model->name = $faker->name();
        $model->email = $faker->email();
        $model->phone = $faker->phoneNumber();
        $model->street = $faker->streetAddress();
        $model->zip = $faker->postcode();
        $model->save();

        $this->assertNotEmpty($model->id);

        $this->assertEquals(1, Company::query()->where('id', $new_company_id)->count());
    }
}
