<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payolution\Business;

use Spryker\Zed\Payolution\Business\Payment\Method\ApiConstants;
use SprykerTest\Zed\Payolution\Business\Api\Adapter\Http\PreAuthorizationAdapterMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Payolution
 * @group Business
 * @group Facade
 * @group PayolutionFacadePreAuthorizeTest
 * Add your own group annotations below this line
 */
class PayolutionFacadePreAuthorizeTest extends AbstractFacadeTest
{
    /**
     * @return void
     */
    public function testPreAuthorizePaymentWithSuccessResponse()
    {
        $orderTransfer = $this->createOrderTransfer();

        $adapterMock = new PreAuthorizationAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->preAuthorizePayment($orderTransfer, $this->getPaymentEntity()->getIdPaymentPayolution());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionTransactionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getSuccessResponse();
        $expectedResponse = $this->getResponseConverter()->toTransactionResponseTransfer($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getPaymentCode(), $response->getPaymentCode());
        $this->assertEquals($expectedResponse->getProcessingResult(), $response->getProcessingResult());
        $this->assertEquals($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertEquals($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLog $requestLog */
        $requestLog = $this->getRequestLogCollectionForPayment()->getLast();
        $this->assertEquals(1, $this->getRequestLogCollectionForPayment()->count());
        $this->assertEquals(ApiConstants::PAYMENT_CODE_PRE_AUTHORIZATION, $requestLog->getPaymentCode());
        $this->assertEquals($orderTransfer->getTotals()->getGrandTotal() / 100, $requestLog->getPresentationAmount());
        $this->assertNull($requestLog->getReferenceId());

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog $statusLog */
        $statusLog = $this->getStatusLogCollectionForPayment()->getLast();
        $this->assertEquals(1, $this->getStatusLogCollectionForPayment()->count());
        $this->matchStatusLogWithResponse($statusLog, $expectedResponse);
        $this->assertNotNull($statusLog->getProcessingConnectordetailConnectortxid1());
        $this->assertNotNull($statusLog->getProcessingConnectordetailPaymentreference());
    }

    /**
     * @return void
     */
    public function testPreAuthorizationWithFailureResponse()
    {
        $orderTransfer = $this->createOrderTransfer();
        $adapterMock = (new PreAuthorizationAdapterMock())->expectFailure();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->preAuthorizePayment($orderTransfer, $this->getPaymentEntity()->getIdPaymentPayolution());

        $expectedResponseData = $adapterMock->getFailureResponse();
        $expectedResponse = $this->getResponseConverter()->toTransactionResponseTransfer($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getPaymentCode(), $response->getPaymentCode());
        $this->assertEquals($expectedResponse->getProcessingResult(), $response->getProcessingResult());
        $this->assertEquals($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertEquals($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog $statusLog */
        $statusLog = $this->getStatusLogCollectionForPayment()->getLast();
        $this->assertEquals(1, $this->getStatusLogCollectionForPayment()->count());
        $this->matchStatusLogWithResponse($statusLog, $expectedResponse);
    }
}
