<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payolution\Business;

use Spryker\Zed\Payolution\Business\Payment\Method\ApiConstants;
use SprykerTest\Zed\Payolution\Business\Api\Adapter\Http\CaptureAdapterMock;
use SprykerTest\Zed\Payolution\Business\Api\Adapter\Http\PreAuthorizationAdapterMock;
use SprykerTest\Zed\Payolution\Business\Api\Adapter\Http\RefundAdapterMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Payolution
 * @group Business
 * @group Facade
 * @group PayolutionFacadeRefundTest
 * Add your own group annotations below this line
 */
class PayolutionFacadeRefundTest extends AbstractFacadeTest
{
    /**
     * @return void
     */
    public function testRefundPaymentWithSuccessResponse()
    {
        $orderTransfer = $this->createOrderTransfer();
        $idPayment = $this->getPaymentEntity()->getIdPaymentPayolution();
        $preAuthorizationAdapterMock = new PreAuthorizationAdapterMock();
        $facade = $this->getFacadeMock($preAuthorizationAdapterMock);
        $preAuthorizationResponse = $facade->preAuthorizePayment($orderTransfer, $idPayment);

        $captureAdapterMock = new CaptureAdapterMock();
        $facade = $this->getFacadeMock($captureAdapterMock);
        $captureResponse = $facade->capturePayment($orderTransfer, $idPayment);

        $adapterMock = new RefundAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->refundPayment($orderTransfer, $idPayment);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionTransactionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getSuccessResponse();
        $expectedResponse = $this->getResponseConverter()->toTransactionResponseTransfer($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getPaymentCode(), $response->getPaymentCode());
        $this->assertEquals($expectedResponse->getProcessingResult(), $response->getProcessingResult());
        $this->assertEquals($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertEquals($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());
        $this->assertEquals(
            $preAuthorizationResponse->getIdentificationUniqueid(),
            $expectedResponse->getIdentificationReferenceid()
        );

        /** @var \Orm\Zed\Payolution\Persistence\Base\SpyPaymentPayolutionTransactionRequestLog $requestLog */
        $requestLog = $this->getRequestLogCollectionForPayment()->getLast();
        $this->assertEquals(3, $this->getRequestLogCollectionForPayment()->count());
        $this->assertEquals(ApiConstants::PAYMENT_CODE_REFUND, $requestLog->getPaymentCode());
        $this->assertEquals($orderTransfer->getTotals()->getGrandTotal() / 100, $requestLog->getPresentationAmount());
        $this->assertEquals($preAuthorizationResponse->getIdentificationUniqueid(), $requestLog->getReferenceId());

        /** @var \Orm\Zed\Payolution\Persistence\Base\SpyPaymentPayolutionTransactionStatusLog $statusLog */
        $statusLog = $this->getStatusLogCollectionForPayment()->getLast();
        $this->assertEquals(3, $this->getStatusLogCollectionForPayment()->count());
        $this->matchStatusLogWithResponse($statusLog, $expectedResponse);
        $this->assertNotNull($statusLog->getProcessingConnectordetailConnectortxid1());
        $this->assertNotNull($statusLog->getProcessingConnectordetailPaymentreference());
    }

    /**
     * @return void
     */
    public function testRefundPaymentWithFailureResponse()
    {
        $orderTransfer = $this->createOrderTransfer();
        $idPayment = $this->getPaymentEntity()->getIdPaymentPayolution();
        $preAuthorizationAdapterMock = new PreAuthorizationAdapterMock();
        $facade = $this->getFacadeMock($preAuthorizationAdapterMock);
        $preAuthorizationResponse = $facade->preAuthorizePayment($orderTransfer, $idPayment);

        $captureAdapterMock = new CaptureAdapterMock();
        $facade = $this->getFacadeMock($captureAdapterMock);
        $captureResponse = $facade->capturePayment($orderTransfer, $idPayment);

        $adapterMock = new RefundAdapterMock();
        $adapterMock->expectFailure();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->refundPayment($orderTransfer, $idPayment);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionTransactionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getFailureResponse();
        $expectedResponse = $this->getResponseConverter()->toTransactionResponseTransfer($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getPaymentCode(), $response->getPaymentCode());
        $this->assertEquals($expectedResponse->getProcessingResult(), $response->getProcessingResult());
        $this->assertEquals($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertEquals($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());
        $this->assertEquals(
            $preAuthorizationResponse->getIdentificationUniqueid(),
            $expectedResponse->getIdentificationReferenceid()
        );

        /** @var \Orm\Zed\Payolution\Persistence\Base\SpyPaymentPayolutionTransactionRequestLog $requestLog */
        $requestLog = $this->getRequestLogCollectionForPayment()->getLast();
        $this->assertEquals(3, $this->getRequestLogCollectionForPayment()->count());
        $this->assertEquals(ApiConstants::PAYMENT_CODE_REFUND, $requestLog->getPaymentCode());
        $this->assertEquals($orderTransfer->getTotals()->getGrandTotal() / 100, $requestLog->getPresentationAmount());
        $this->assertEquals($preAuthorizationResponse->getIdentificationUniqueid(), $requestLog->getReferenceId());

        /** @var \Orm\Zed\Payolution\Persistence\Base\SpyPaymentPayolutionTransactionStatusLog $statusLog */
        $statusLog = $this->getStatusLogCollectionForPayment()->getLast();
        $this->assertEquals(3, $this->getStatusLogCollectionForPayment()->count());
        $this->matchStatusLogWithResponse($statusLog, $expectedResponse);
    }
}
