<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Database\Queries;

use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageQueryInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageStateInterface;
use App\Modules\Invoices\Infrastructure\Database\Models\Invoice;
use Ramsey\Uuid\UuidInterface;

/**
 * All queries regarding invoices are here
 * The purpose of this class is to translate the invoices query from the domain to the ORM specific for Eloquent.
 * This makes the domain independent of the ORM implementation.
 * Test: {@see InvoiceORMQueryTest}
 */
class InvoiceORMQuery implements InvoiceStorageQueryInterface
{
    public function getById(UuidInterface $id): InvoiceStorageStateInterface
    {
        /**
         * @var $result Invoice
         */
        $result = Invoice::query()->where('id', $id)->firstOrFail();
        return $result;
    }
}
