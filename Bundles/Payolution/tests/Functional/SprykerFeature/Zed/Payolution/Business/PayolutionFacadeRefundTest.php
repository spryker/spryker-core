<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Payolution\Business;

use Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http\CaptureAdapterMock;
use Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http\PreAuthorizationAdapterMock;
use Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http\RefundAdapterMock;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Persistence\Propel\Base\SpyPaymentPayolutionTransactionRequestLog;
use SprykerFeature\Zed\Payolution\Persistence\Propel\Base\SpyPaymentPayolutionTransactionStatusLog;

class PayolutionFacadeRefundTest extends AbstractFacadeTest
{

    public function testRefundPaymentWithSuccessResponse()
    {
        $idPayment = $this->getPaymentEntity()->getIdPaymentPayolution();
        $preAuthorizationAdapterMock = new PreAuthorizationAdapterMock();
        $facade = $this->getFacadeMock($preAuthorizationAdapterMock);
        $facade->preAuthorizePayment($idPayment);

        $captureAdapterMock = new CaptureAdapterMock();
        $facade = $this->getFacadeMock($captureAdapterMock);
        $captureResponse = $facade->capturePayment($idPayment);

        $adapterMock = new RefundAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->refundPayment($idPayment);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getSuccessResponse();
        $expectedResponse = $this->getResponseConverter()->fromArray($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getPaymentCode(), $response->getPaymentCode());
        $this->assertEquals($expectedResponse->getProcessingResult(), $response->getProcessingResult());
        $this->assertEquals($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertEquals($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());
        $this->assertEquals(
            $captureResponse->getIdentificationUniqueid(),
            $expectedResponse->getIdentificationReferenceid()
        );

        /** @var SpyPaymentPayolutionTransactionRequestLog $requestLog */
        $requestLog = $this->getRequestLogCollectionForPayment()->getLast();
        $this->assertEquals(3, $this->getRequestLogCollectionForPayment()->count());
        $this->assertEquals(Constants::PAYMENT_CODE_REFUND, $requestLog->getPaymentCode());
        $this->assertEquals($this->getOrderEntity()->getGrandTotal() / 100, $requestLog->getPresentationAmount());
        $this->assertEquals($captureResponse->getIdentificationUniqueid(), $requestLog->getReferenceId());

        /** @var SpyPaymentPayolutionTransactionStatusLog $statusLog */
        $statusLog = $this->getStatusLogCollectionForPayment()->getLast();
        $this->assertEquals(3, $this->getStatusLogCollectionForPayment()->count());
        $this->matchStatusLogWithResponse($statusLog, $expectedResponse);
        $this->assertNotNull($statusLog->getProcessingConnectordetailConnectortxid1());
        $this->assertNotNull($statusLog->getProcessingConnectordetailPaymentreference());
    }

    public function testRefundPaymentWithFailureResponse()
    {
        $idPayment = $this->getPaymentEntity()->getIdPaymentPayolution();
        $preAuthorizationAdapterMock = new PreAuthorizationAdapterMock();
        $facade = $this->getFacadeMock($preAuthorizationAdapterMock);
        $facade->preAuthorizePayment($idPayment);

        $captureAdapterMock = new CaptureAdapterMock();
        $facade = $this->getFacadeMock($captureAdapterMock);
        $captureResponse = $facade->capturePayment($idPayment);

        $adapterMock = new RefundAdapterMock();
        $adapterMock->expectFailure();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->refundPayment($idPayment);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getFailureResponse();
        $expectedResponse = $this->getResponseConverter()->fromArray($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getPaymentCode(), $response->getPaymentCode());
        $this->assertEquals($expectedResponse->getProcessingResult(), $response->getProcessingResult());
        $this->assertEquals($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertEquals($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());
        $this->assertEquals(
            $captureResponse->getIdentificationUniqueid(),
            $expectedResponse->getIdentificationReferenceid()
        );

        /** @var SpyPaymentPayolutionTransactionRequestLog $requestLog */
        $requestLog = $this->getRequestLogCollectionForPayment()->getLast();
        $this->assertEquals(3, $this->getRequestLogCollectionForPayment()->count());
        $this->assertEquals(Constants::PAYMENT_CODE_REFUND, $requestLog->getPaymentCode());
        $this->assertEquals($this->getOrderEntity()->getGrandTotal() / 100, $requestLog->getPresentationAmount());
        $this->assertEquals($captureResponse->getIdentificationUniqueid(), $requestLog->getReferenceId());

        /** @var SpyPaymentPayolutionTransactionStatusLog $statusLog */
        $statusLog = $this->getStatusLogCollectionForPayment()->getLast();
        $this->assertEquals(3, $this->getStatusLogCollectionForPayment()->count());
        $this->matchStatusLogWithResponse($statusLog, $expectedResponse);
    }

}
