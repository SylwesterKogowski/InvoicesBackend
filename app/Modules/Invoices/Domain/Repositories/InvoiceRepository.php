<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Repositories;

use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Domain\Entities\InvoiceInterface;
use App\Modules\Invoices\Domain\Exceptions\InvoiceNotFoundException;
use App\Modules\Invoices\Domain\Factories\InvoiceFactoryInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageQueryInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageStateInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\App;
use Ramsey\Uuid\UuidInterface;

/**
 * Class InvoiceRepository
 * @package App\Modules\Invoices\Domain\Repositories
 * All invoice queries here
 * Test: {@see \Tests\Unit\Modules\Invoices\Domain\Repositories\InvoiceRepositoryTest}
 */
class InvoiceRepository implements InvoiceRepositoryInterface
{
    /**
     * @param InvoiceStorageQueryInterface $invoiceQuery Database queries for invoices
     */
    public function __construct(
        private InvoiceStorageQueryInterface $invoiceQuery
    ) {
    }

    /**
     * Gives invoice by UUID
     * @param UuidInterface $id
     * @throws InvoiceNotFoundException
     */
    public function getInvoiceById(UuidInterface $id): InvoiceInterface
    {
        try {
            return App::make(InvoiceFactoryInterface::class)->fromStorageState($this->invoiceQuery->getById($id));
        } catch (ModelNotFoundException $ex) {
            throw new InvoiceNotFoundException();
        }
    }
}
