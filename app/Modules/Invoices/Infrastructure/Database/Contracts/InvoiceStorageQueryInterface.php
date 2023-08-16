<?php

namespace App\Modules\Invoices\Infrastructure\Database\Contracts;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

interface InvoiceStorageQueryInterface
{
    /**
     * @param UuidInterface $id
     * @throws ModelNotFoundException if invoice with given id does not exist
     */
    public function getById(UuidInterface $id): InvoiceStorageStateInterface;

}
