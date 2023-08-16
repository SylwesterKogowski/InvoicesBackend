<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Database\Seeders;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Seeder;

/**
 * Adding random billed company id to invoices
 */
class InvoiceBilledCompanySeeder extends Seeder
{
    public function __construct(
        private ConnectionInterface $db
    ) {
    }

    public function run(): void
    {
        $companies = $this->db->table('companies')->get();
        $invoices = $this->db->table('invoices')->get('id');

        foreach ($invoices as $invoice) {
            $this->db->table('invoices')
                ->where('id', $invoice->id)
                ->update(['billed_company_id' => $companies->random()->id]);
        }
    }
}
