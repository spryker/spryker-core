<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payolution\Business;

use Spryker\Zed\Payolution\Business\Payment\Method\ApiConstants;
use SprykerTest\Zed\Payolution\Business\Api\Adapter\Http\CaptureAdapterMock;
use SprykerTest\Zed\Payolution\Business\Api\Adapter\Http\PreAuthorizationAdapterMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Payolution
 * @group Business
 * @group Facade
 * @group PayolutionFacadeCaptureTest
 * Add your own group annotations below this line
 */
class PayolutionFacadeCaptureTest extends AbstractFacadeTest
{
    /**
     * @return void
     */
    public function testCapturePaymentWithSuccessResponse()
    {
        $orderTransfer = $this->createOrderTransfer();

        $idPayment = $this->getPaymentEntity()->getIdPaymentPayolution();
        $adapterMock = new PreAuthorizationAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $preAuthorizationResponse = $facade->preAuthorizePayment($orderTransfer, $idPayment);

        $adapterMock = new CaptureAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->capturePayment($orderTransfer, $idPayment);

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

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLog $requestLog */
        $requestLog = $this->getRequestLogCollectionForPayment()->getLast();
        $this->assertEquals(2, $this->getRequestLogCollectionForPayment()->count());
        $this->assertEquals(ApiConstants::PAYMENT_CODE_CAPTURE, $requestLog->getPaymentCode());
        $this->assertEquals($orderTransfer->getTotals()->getGrandTotal() / 100, $requestLog->getPresentationAmount());
        $this->assertEquals($preAuthorizationResponse->getIdentificationUniqueid(), $requestLog->getReferenceId());

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog $statusLog */
        $statusLog = $this->getStatusLogCollectionForPayment()->getLast();
        $this->assertEquals(2, $this->getStatusLogCollectionForPayment()->count());
        $this->matchStatusLogWithResponse($statusLog, $expectedResponse);
        $this->assertNotNull($statusLog->getProcessingConnectordetailConnectortxid1());
        $this->assertNotNull($statusLog->getProcessingConnectordetailPaymentreference());
    }

    /**
     * @return void
     */
    public function testCapturePaymentWithFailureResponse()
    {
        $orderTransfer = $this->createOrderTransfer();
        $idPayment = $this->getPaymentEntity()->getIdPaymentPayolution();
        $adapterMock = new PreAuthorizationAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $preAuthorizationResponse = $facade->preAuthorizePayment($orderTransfer, $idPayment);

        $adapterMock = new CaptureAdapterMock();
        $adapterMock->expectFailure();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->capturePayment($orderTransfer, $idPayment);

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

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLog $requestLog */
        $requestLog = $this->getRequestLogCollectionForPayment()->getLast();
        $this->assertEquals(2, $this->getRequestLogCollectionForPayment()->count());
        $this->assertEquals(ApiConstants::PAYMENT_CODE_CAPTURE, $requestLog->getPaymentCode());
        $this->assertEquals($orderTransfer->getTotals()->getGrandTotal() / 100, $requestLog->getPresentationAmount());
        $this->assertEquals($preAuthorizationResponse->getIdentificationUniqueid(), $requestLog->getReferenceId());

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog $statusLog */
        $statusLog = $this->getStatusLogCollectionForPayment()->getLast();
        $this->assertEquals(2, $this->getStatusLogCollectionForPayment()->count());
        $this->matchStatusLogWithResponse($statusLog, $expectedResponse);
    }
}
