<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Database\Models;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Infrastructure\Database\Contracts\CompanyStorageStateInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageStateInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Exception\UnsupportedOperationException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Database model for invoice entities.
 * This class is only responsible for the single invoice record management,
 * for queries go here: {@see \App\Modules\Invoices\Infrastructure\Database\Queries\InvoiceORMQuery}
 * Domain entity: {@see  \App\Modules\Invoices\Domain\Entities\Invoice}
 * Domain Repository: {@see  \App\Modules\Invoices\Domain\Repositories\InvoiceRepository}
 * Test: {@see \Tests\Feature\Modules\Invoices\Infrastructure\Database\Models\InvoiceTest}
 * @property string $id UUID
 * @property string $number invoice number
 * @property Carbon $date invoice issuance date
 * @property Carbon $due_date invoice due date
 * @property string $company_id UUID of the company that issued the invoice
 * @property string $billed_company_id UUID of the company that was billed by the invoice
 * @property string $status status of invoice approval
 * @property Carbon $created_at time when invoice record was created
 * @property Carbon $updated_at time when invoice record was updated
 * @property Company $issuingCompany invoice issuing company
 * @property Company $billedCompany invoice billed company
 * @property HasMany $productLines product lines {@see InvoiceProductLine}
 */
class Invoice extends Model implements InvoiceStorageStateInterface
{
    use HasUuids;

    protected $table = 'invoices';

    protected $casts = [
        'date' => 'datetime:Y-m-d',
        'due_date' => 'datetime:Y-m-d',
    ];

    /**
     * @var array default values for attributes
     */
    protected $attributes = [
        'status' => StatusEnum::DRAFT->value,
    ];

    /**
     * Contains changes to the product lines that have not yet been saved to the database.
     * @var Collection|null
     */
    private ?Collection $changedProductLines = null;

    /**
     * @inheritDoc
     */
    public function getId(): UuidInterface
    {
        return Uuid::fromString($this->id);
    }


    /**
     * @inheritDoc
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @inheritDoc
     */
    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    /**
     * @inheritDoc
     */
    public function getIssueDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @inheritDoc
     */
    public function setIssueDate(Carbon $date): void
    {
        $this->date = $date;
    }

    /**
     * @inheritDoc
     */
    public function getDueDate(): Carbon
    {
        return $this->due_date;
    }

    /**
     * @inheritDoc
     */
    public function setDueDate(Carbon $due_date): void
    {
        $this->due_date = $due_date;
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): StatusEnum
    {
        return StatusEnum::from($this->status);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(StatusEnum $status): void
    {
        $this->status = $status->value;
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    /**
     * Invoice issuing company
     */
    public function issuingCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    /**
     * Invoice billed company
     */
    public function billedCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'billed_company_id');
    }

    /**
     * @inheritDoc
     */
    public function getIssuingCompany(): CompanyStorageStateInterface
    {
        return $this->issuingCompany;
    }

    /**
     * @inheritDoc
     */
    public function getBilledCompany(): CompanyStorageStateInterface
    {
        return $this->billedCompany;
    }

    public function productLines(): HasMany
    {
        return $this->hasMany(InvoiceProductLine::class, 'invoice_id');
    }

    /**
     * @inheritDoc
     */
    public function getProductLines(): array
    {
        return !is_null($this->changedProductLines) ?
            $this->changedProductLines->all() : $this->productLines->all();
    }
    /**
     * @inheritDoc
     */
    public function setProductLines(array $productLines): void
    {
        $this->changedProductLines = Collection::make($productLines);
    }

    /**
     * @inheritDoc
     */
    public function setIssuingCompany(CompanyStorageStateInterface $issuingCompanyORM): void
    {
        if (!($issuingCompanyORM instanceof Company)) {
            throw new UnsupportedOperationException('InvoiceORM can only be issued by CompanyORM');
        }
        $this->issuingCompany()->associate($issuingCompanyORM);
    }


    /**
     * @inheritDoc
     */
    public function setBilledCompany(CompanyStorageStateInterface $billedCompanyORM): void
    {
        if (!($billedCompanyORM instanceof Company)) {
            throw new UnsupportedOperationException('InvoiceORM can only be billed by CompanyORM');
        }
        $this->billedCompany()->associate($billedCompanyORM);
    }

    /**
     * @inheritDoc
     */
    public function saveInvoiceAndRelations(): void
    {
        if (!DB::transactionLevel()) {
            DB::statement('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');
        }

        DB::transaction(function (): void {
            //region belongs to relations must be saved first in case they weren't created yet
            if ($this->relationLoaded('issuingCompany')) {
                //if the relation was not loaded, then it also wasn't changed
                //we won't force reloading of relations when it is not necessary at all
                $this->issuingCompany->save();
            }
            if ($this->relationLoaded('billedCompany')) {
                //if the relation was not loaded, then it also wasn't changed
                $this->billedCompany->save();
            }
            //endregion

            $this->save();

            //region other relation types must be saved after the invoice
            if ($this->relationLoaded('productLines') || !is_null($this->changedProductLines)) {
                $this->saveProductLines();
            }
            //endregion
        });
        $this->refreshInvoice();
    }

    /**
     * @inheritDoc
     */
    public function changeAndSaveInTransaction(callable $callback): void
    {
        if (!DB::transactionLevel()) {
            DB::statement('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');
        }
        DB::transaction(function () use ($callback): void {
            if ($this->isInStorage()) {
                DB::query()->from($this->table)
                    ->where('id', '=', $this->id)
                    ->lockForUpdate();
            }
            $callback();
            $this->saveInvoiceAndRelations();
        });
    }

    public function isInStorage(): bool
    {
        return $this->exists;
    }

    /**
     * @inheritDoc
     */
    public function refreshInvoice(): void
    {
        $this->refresh();
    }

    /**
     */
    protected function saveProductLines(): void
    {
        if ($this->changedProductLines) {
            $this->productLines()->where('id', 'not in', $this->changedProductLines->pluck('id'))->delete();
            foreach ($this->changedProductLines as $productLine) {
                /**
                 * @var InvoiceProductLine $productLine
                 */
                $productLine->saveProductLineAndRelationsWithoutInvoice();
            }
        } else {
            foreach ($this->productLines as $productLine) {
                /**
                 * @var InvoiceProductLine $productLine
                 */
                $productLine->saveProductLineAndRelationsWithoutInvoice();
            }
        }
    }
}
