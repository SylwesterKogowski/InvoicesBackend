<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api\Web;

use App\Modules\Invoices\Api\Exceptions\RequestRejectedException;
use App\Modules\Invoices\Api\InvoicesApiInterface;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

/**
 * Handles web requests for invoices
 * Unit tests in {@see \Tests\Unit\Modules\Invoices\Api\Web\InvoiceControllerTest}
 * E2e tests in {@see \Tests\Feature\Modules\Invoices\Api\Web\InvoiceControllerTest}
 */
class InvoiceController extends Controller
{
    //Output in proto buffer binary format maybe?
    const SERIALIZE_OUTPUT_PROTOBUFFER_BINARY = false;
    public function __construct(private InvoicesApiInterface $invoicesApi)
    {
    }


    /**
     * Gives an invoice and all it's relations
     * According to the scheme {@see ../MessageSchemas/Invoice.proto}
     * @param string $invoiceUuid
     */
    public function get(string $invoiceUuid): \Symfony\Component\HttpFoundation\Response
    {
        try {
            $result = $this->invoicesApi->getInvoiceWithRelations(Uuid::fromString($invoiceUuid));
        } catch (InvalidUuidStringException $e) {
            return Response::make('Request rejected. Id must be a UUID', 400, [
                'Content-Type' => 'text/plain',
            ]);
        } catch (RequestRejectedException $e) {
            return Response::make('Request rejected. ' . $e->getMessage(), 400, [
                'Content-Type' => 'text/plain',
            ]);
        } catch (\Exception $e) {
            report($e);
            return Response::make('Internal error. ' . $e->getMessage(), 500, [
                'Content-Type' => 'text/plain',
            ]);
        }
        if (self::SERIALIZE_OUTPUT_PROTOBUFFER_BINARY && Request::accepts('application/x-protobuf')) {
            return Response::stream(function () use ($result): void {
                echo $result->serializeToString();
            }, 200, [
                'Content-Type' => 'application/x-protobuf',
            ]);
        } else {
            return Response::stream(function () use ($result): void {
                echo $result->serializeToJsonString();
            }, 200, [
                'Content-Type' => 'application/json',
            ]);
        }
    }

    /**
     * Approve an invoice by it's uuid
     *  Approval or rejection can only be done once
     * @param string $invoiceUuid
     */
    public function approve(string $invoiceUuid): \Symfony\Component\HttpFoundation\Response
    {
        try {
            $this->invoicesApi->approveInvoiceForPayments(Uuid::fromString($invoiceUuid));
        } catch (InvalidUuidStringException $e) {
            return Response::make('Request rejected. Id must be a UUID', 400, [
                'Content-Type' => 'text/plain',
            ]);
        } catch (RequestRejectedException $e) {
            return Response::make('Request rejected. ' . $e->getMessage(), 400, [
                'Content-Type' => 'text/plain',
            ]);
        } catch (\Exception $e) {
            report($e);
            return Response::make('Internal error. ' . $e->getMessage(), 500, [
                'Content-Type' => 'text/plain',
            ]);
        }
        return Response::noContent();
    }

    /**
     * Reject an invoice by it's uuid
     * Approval or rejection can only be done once
     * @param string $invoiceUuid
     */
    public function reject(string $invoiceUuid): \Symfony\Component\HttpFoundation\Response
    {
        try {
            $this->invoicesApi->rejectInvoiceForPayments(Uuid::fromString($invoiceUuid));
        } catch (InvalidUuidStringException $e) {
            return Response::make('Request rejected. Id must be a UUID', 400, [
                'Content-Type' => 'text/plain',
            ]);
        } catch (RequestRejectedException $e) {
            return Response::make('Request rejected. ' . $e->getMessage(), 400, [
                'Content-Type' => 'text/plain',
            ]);
        } catch (\Exception $e) {
            report($e);
            return Response::make('Internal error. ' . $e->getMessage(), 500, [
                'Content-Type' => 'text/plain',
            ]);
        }
        return Response::noContent();
    }
}
