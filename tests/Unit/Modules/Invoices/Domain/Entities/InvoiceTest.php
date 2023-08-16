<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Domain\Entities;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Invoices\Domain\Entities\Invoice;
use App\Modules\Invoices\Infrastructure\Database\Contracts\CompanyStorageStateInterface;
use App\Modules\Invoices\Infrastructure\Database\Contracts\InvoiceStorageStateInterface;
use PHPUnit\Framework\MockObject\Stub\ReturnCallback;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\CreatesApplication;
use Tests\Helpers\CombinedInvocationStub;
use Tests\Helpers\MethodSequenceCheck;
use Tests\Helpers\ValueCaster;

/**
 * Unit test for {@see Invoice} entity model
 */
class InvoiceTest extends TestCase
{
    use CreatesApplication;



    /**
     * Approve for payment should save the invoice before transaction and then after transaction
     * @test
     */
    public function whenApproveForPaymentShouldSaveInvoiceAndRelationsBeforeTransaction(): void
    {
        $app = $this->createApplication();
        $invoiceStorageMock = $this->createMock(InvoiceStorageStateInterface::class);
        $invoiceStorageMock->expects($this->once())->method('saveInvoiceAndRelations')
            ->id('saveInvoiceAndRelations');
        $invoiceStorageMock->expects($this->once())->method('changeAndSaveInTransaction')
            ->after('saveInvoiceAndRelations');
        $invoice = new Invoice($invoiceStorageMock);

        $invoice->approveForPayment();
    }
    /**
     * @test
     */
    public function whenApproveForPaymentShouldRefreshInvoiceInTransaction(): void
    {
        $app = $this->createApplication();
        $invoiceStorageMock = $this->createConfiguredMock(InvoiceStorageStateInterface::class, [
            'getStatus' => StatusEnum::DRAFT,
            'getId' => Uuid::uuid4(),
        ]);
        $invoiceStorageMock->expects($this->once())->method('refreshInvoice')->after('changeAndSaveInTransaction');
        $invoiceStorageMock->expects($this->once())->method('changeAndSaveInTransaction')
            ->id('changeAndSaveInTransaction')
            ->will(new ReturnCallback(function ($insideTransactionFunction): void {
                $insideTransactionFunction();
            }));

        $invoice = new Invoice($invoiceStorageMock);

        $invoice->approveForPayment();
    }
    /**
     * @test
     */
    public function whenApproveForPaymentShouldGetInvoiceStatusInTransaction(): void
    {
        $app = $this->createApplication();
        $invoiceStorageMock = $this->createConfiguredMock(InvoiceStorageStateInterface::class, [
            'getStatus' => StatusEnum::DRAFT,
            'getId' => Uuid::uuid4(),
        ]);
        $invoiceStorageMock->expects($this->once())->method('getStatus')
            ->willReturn(StatusEnum::DRAFT)
            ->after('changeAndSaveInTransaction');
        $invoiceStorageMock->expects($this->once())->method('changeAndSaveInTransaction')
            ->id('changeAndSaveInTransaction')
            ->will(new ReturnCallback(function ($insideTransactionFunction): void {
                $insideTransactionFunction();
            }));

        $invoice = new Invoice($invoiceStorageMock);

        $invoice->approveForPayment();
    }

    /**
     * @test
     */
    public function whenApproveForPaymentShouldSetInvoiceApprovedInTransaction(): void
    {
        $app = $this->createApplication();
        $invoiceStorageMock = $this->createConfiguredMock(InvoiceStorageStateInterface::class, [
            'getStatus' => StatusEnum::DRAFT,
            'getId' => Uuid::uuid4(),
        ]);
        $invoiceStorageMock->expects($this->once())->method('setStatus')
            ->with(StatusEnum::APPROVED)
            ->after('changeAndSaveInTransaction');
        $invoiceStorageMock->expects($this->once())->method('changeAndSaveInTransaction')
            ->id('changeAndSaveInTransaction')
            ->will(new ReturnCallback(function ($insideTransactionFunction): void {
                $insideTransactionFunction();
            }));

        $invoice = new Invoice($invoiceStorageMock);

        $invoice->approveForPayment();
    }


    /**
     * @test
     */
    public function whenApproveForPaymentShouldCallApprovalInTransaction(): void
    {
        $app = $this->createApplication();
        //region mock approval facade
        $approvalFacadeMock = $this->createMock(ApprovalFacadeInterface::class);
        $app->instance(ApprovalFacadeInterface::class, $approvalFacadeMock);
        //endregion

        //region mock invoice storage
        $invoiceStorageMock = $this->createConfiguredMock(InvoiceStorageStateInterface::class, [
            'getStatus' => StatusEnum::DRAFT,
            'getId' => Uuid::uuid4(),
        ]);
        //endregion

        //region assert function calls
        $sequenceChecker = new MethodSequenceCheck();
        $invoiceStorageMock->expects($this->once())->method('changeAndSaveInTransaction')
            ->will(new CombinedInvocationStub(
                $sequenceChecker,
                new ReturnCallback(function ($insideTransactionFunction) use ($sequenceChecker): void {
                    $insideTransactionFunction();
                })
            ));
        $approvalFacadeMock->expects($this->once())->method('approve')
            ->will(new CombinedInvocationStub(
                $sequenceChecker,
                new ReturnStub(true)
            ));
        //endregion

        $invoice = new Invoice($invoiceStorageMock);
        $invoice->approveForPayment();

        $this->assertEquals(['changeAndSaveInTransaction', 'approve'], $sequenceChecker->getSequence());
    }




    /**
     * Reject for payment should save the invoice before transaction and then after transaction
     * @test
     */
    public function whenRejectForPaymentShouldSaveInvoiceAndRelationsBeforeTransaction(): void
    {
        $app = $this->createApplication();
        $invoiceStorageMock = $this->createMock(InvoiceStorageStateInterface::class);
        $invoiceStorageMock->expects($this->once())->method('saveInvoiceAndRelations')
            ->id('saveInvoiceAndRelations');
        $invoiceStorageMock->expects($this->once())->method('changeAndSaveInTransaction')
            ->after('saveInvoiceAndRelations');
        $invoice = new Invoice($invoiceStorageMock);

        $invoice->rejectForPayment();
    }
    /**
     * @test
     */
    public function whenRejectForPaymentShouldRefreshInvoiceInTransaction(): void
    {
        $app = $this->createApplication();
        $invoiceStorageMock = $this->createConfiguredMock(InvoiceStorageStateInterface::class, [
            'getStatus' => StatusEnum::DRAFT,
            'getId' => Uuid::uuid4(),
        ]);
        $invoiceStorageMock->expects($this->once())->method('refreshInvoice')->after('changeAndSaveInTransaction');
        $invoiceStorageMock->expects($this->once())->method('changeAndSaveInTransaction')
            ->id('changeAndSaveInTransaction')
            ->will(new ReturnCallback(function ($insideTransactionFunction): void {
                $insideTransactionFunction();
            }));

        $invoice = new Invoice($invoiceStorageMock);

        $invoice->rejectForPayment();
    }
    /**
     * @test
     */
    public function whenRejectForPaymentShouldGetInvoiceStatusInTransaction(): void
    {
        $app = $this->createApplication();
        $invoiceStorageMock = $this->createConfiguredMock(InvoiceStorageStateInterface::class, [
            'getStatus' => StatusEnum::DRAFT,
            'getId' => Uuid::uuid4(),
        ]);
        $invoiceStorageMock->expects($this->once())->method('getStatus')
            ->willReturn(StatusEnum::DRAFT)
            ->after('changeAndSaveInTransaction');
        $invoiceStorageMock->expects($this->once())->method('changeAndSaveInTransaction')
            ->id('changeAndSaveInTransaction')
            ->will(new ReturnCallback(function ($insideTransactionFunction): void {
                $insideTransactionFunction();
            }));

        $invoice = new Invoice($invoiceStorageMock);

        $invoice->rejectForPayment();
    }

    /**
     * @test
     */
    public function whenRejectForPaymentShouldSetInvoiceRejectedInTransaction(): void
    {
        $app = $this->createApplication();
        $invoiceStorageMock = $this->createConfiguredMock(InvoiceStorageStateInterface::class, [
            'getStatus' => StatusEnum::DRAFT,
            'getId' => Uuid::uuid4(),
        ]);
        $invoiceStorageMock->expects($this->once())->method('setStatus')
            ->with(StatusEnum::REJECTED)
            ->after('changeAndSaveInTransaction');
        $invoiceStorageMock->expects($this->once())->method('changeAndSaveInTransaction')
            ->id('changeAndSaveInTransaction')
            ->will(new ReturnCallback(function ($insideTransactionFunction): void {
                $insideTransactionFunction();
            }));

        $invoice = new Invoice($invoiceStorageMock);

        $invoice->rejectForPayment();
    }


    /**
     * @test
     */
    public function whenRejectForPaymentShouldCallApprovalInTransaction(): void
    {
        $app = $this->createApplication();
        //region mock approval facade
        $approvalFacadeMock = $this->createMock(ApprovalFacadeInterface::class);
        $app->instance(ApprovalFacadeInterface::class, $approvalFacadeMock);
        //endregion

        //region mock invoice storage
        $invoiceStorageMock = $this->createConfiguredMock(InvoiceStorageStateInterface::class, [
            'getStatus' => StatusEnum::DRAFT,
            'getId' => Uuid::uuid4(),
        ]);
        //endregion

        //region assert function calls
        $sequenceChecker = new MethodSequenceCheck();
        $invoiceStorageMock->expects($this->once())->method('changeAndSaveInTransaction')
            ->will(new CombinedInvocationStub(
                $sequenceChecker,
                new ReturnCallback(function ($insideTransactionFunction) use ($sequenceChecker): void {
                    $insideTransactionFunction();
                })
            ));
        $approvalFacadeMock->expects($this->once())->method('reject')
            ->will(new CombinedInvocationStub(
                $sequenceChecker,
                new ReturnStub(true)
            ));
        //endregion

        $invoice = new Invoice($invoiceStorageMock);
        $invoice->rejectForPayment();

        $this->assertEquals(['changeAndSaveInTransaction', 'reject'], $sequenceChecker->getSequence());
    }

    /**
     * @test
     * @testWith ["getNumber", "getNumber", "INV-123/02/2023", "string"]
     *   ["getStatus", "getStatus", "draft", "App\\Domain\\Enums\\StatusEnum"]
     *   ["getIssueDate", "getIssueDate", "2021-12-15", "datetime:Y-m-d"]
     *   ["getDueDate", "getDueDate", "2021-12-15", "datetime:Y-m-d"]
     *   ["getCreatedAt", "getCreatedAt", "2023-12-02 02:54:45", "datetime"]
     *   ["getUpdatedAt", "getUpdatedAt", "2023-12-02 02:54:45", "datetime"]
     *   ["getId", "getId", "0d03e24c-47ac-438a-a63b-f7313538b836", "\\App\\Casts\\Uuid"]
     */
    public function shouldGetPropertyFromStorage($propertyGetterInEntity, $propertyGetterInStorage, $value, $castType): void
    {
        $valueCaster = new ValueCaster();
        $invoiceStorageMock = $this->createMock(InvoiceStorageStateInterface::class);
        $expectedValue = $valueCaster->cast($value, $castType);
        $invoiceStorageMock->expects($this->once())->method($propertyGetterInStorage)->willReturn($expectedValue);
        $invoice = new Invoice($invoiceStorageMock);

        $this->assertEquals($expectedValue, $invoice->$propertyGetterInEntity());
    }

    /**
     * @test
     */
    public function shouldGetBilledCompanyFromStorage() {
        $invoiceStorageMock = $this->createMock(InvoiceStorageStateInterface::class);
        $companyMock = $this->createMock(CompanyStorageStateInterface::class);
        $invoiceStorageMock->expects($this->once())->method('getBilledCompany')->willReturn($companyMock);
        $invoice = new Invoice($invoiceStorageMock);

        $this->assertEquals($companyMock, $invoice->getBilledCompany()->getStorageState());
    }
    /**
     * @test
     */
    public function shouldGetIssuingCompanyFromStorage() {
        $invoiceStorageMock = $this->createMock(InvoiceStorageStateInterface::class);
        $companyMock = $this->createMock(CompanyStorageStateInterface::class);
        $invoiceStorageMock->expects($this->once())->method('getIssuingCompany')->willReturn($companyMock);
        $invoice = new Invoice($invoiceStorageMock);

        $this->assertEquals($companyMock, $invoice->getIssuingCompany()->getStorageState());
    }
}
