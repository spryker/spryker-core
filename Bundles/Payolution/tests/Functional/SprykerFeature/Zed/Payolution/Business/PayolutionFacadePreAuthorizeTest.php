<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Payolution\Business;

use Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http\PreAuthorizationAdapterMock;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionRequestLog;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLog;

class PayolutionFacadePreAuthorizeTest extends AbstractFacadeTest
{

    public function testPreAuthorizePaymentWithSuccessResponse()
    {
        $adapterMock = new PreAuthorizationAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->preAuthorizePayment($this->getPaymentEntity()->getIdPaymentPayolution());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getSuccessResponse();
        $expectedResponse = $this->getResponseConverter()->fromArray($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals(Constants::PAYMENT_CODE_PRE_AUTHORIZATION, $expectedResponse->getPaymentCode());
        $this->assertEquals('ACK', $expectedResponse->getProcessingResult());
        $this->assertEquals('00', $expectedResponse->getProcessingReasonCode());
        $this->assertEquals('90', $expectedResponse->getProcessingStatusCode());

        /** @var SpyPaymentPayolutionTransactionRequestLog $requestLog */
        $requestLog = $this->getRequestLogCollectionForPayment()->getLast();
        $this->assertEquals(1, $this->getRequestLogCollectionForPayment()->count());
        $this->assertEquals(Constants::PAYMENT_CODE_PRE_AUTHORIZATION, $requestLog->getPaymentCode());
        $this->assertEquals($this->getOrderEntity()->getGrandTotal() / 100, $requestLog->getPresentationAmount());
        $this->assertNull($requestLog->getReferenceId());

        /** @var SpyPaymentPayolutionTransactionStatusLog $statusLog */
        $statusLog = $this->getStatusLogCollectionForPayment()->getLast();
        $this->assertEquals(1, $this->getStatusLogCollectionForPayment()->count());
        $this->matchStatusLogWithResponse($statusLog, $expectedResponse);
        $this->assertNotNull($statusLog->getProcessingConnectordetailConnectortxid1());
        $this->assertNotNull($statusLog->getProcessingConnectordetailPaymentreference());
    }

    public function testPreAuthorizationWithFailureResponse()
    {
        $adapterMock = (new PreAuthorizationAdapterMock())->expectFailure();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->preAuthorizePayment($this->getPaymentEntity()->getIdPaymentPayolution());

        $expectedResponseData = $adapterMock->getFailureResponse();
        $expectedResponse = $this->getResponseConverter()->fromArray($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals(Constants::PAYMENT_CODE_PRE_AUTHORIZATION, $expectedResponse->getPaymentCode());
        $this->assertEquals('NOK', $expectedResponse->getProcessingResult());
        $this->assertEquals('95', $expectedResponse->getProcessingReasonCode());
        $this->assertEquals('60', $expectedResponse->getProcessingStatusCode());

        /** @var SpyPaymentPayolutionTransactionStatusLog $statusLog */
        $statusLog = $this->getStatusLogCollectionForPayment()->getLast();
        $this->assertEquals(1, $this->getStatusLogCollectionForPayment()->count());
        $this->matchStatusLogWithResponse($statusLog, $expectedResponse);
    }

}
