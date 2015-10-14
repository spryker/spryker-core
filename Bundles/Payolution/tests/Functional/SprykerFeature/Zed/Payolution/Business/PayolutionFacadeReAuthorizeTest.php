<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Payolution\Business;

use Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http\PreAuthorizationAdapterMock;
use Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http\ReAuthorizationAdapterMock;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionRequestLog;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLog;

class PayolutionFacadeReAuthorizeTest extends AbstractFacadeTest
{

    public function testReAuthorizePaymentWithSuccessResponse()
    {
        $adapterMock = new PreAuthorizationAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $preAuthorizationResponse = $facade->preAuthorizePayment($this->getPaymentEntity()->getIdPaymentPayolution());

        $adapterMock = new ReAuthorizationAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->reAuthorizePayment($this->getPaymentEntity()->getIdPaymentPayolution());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getSuccessResponse();
        $expectedResponse = $this->getResponseConverter()->fromArray($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getPaymentCode(), $response->getPaymentCode());
        $this->assertEquals($expectedResponse->getProcessingResult(), $response->getProcessingResult());
        $this->assertEquals($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertEquals($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());
        $this->assertEquals(
            $preAuthorizationResponse->getIdentificationUniqueid(),
            $expectedResponse->getIdentificationReferenceid()
        );

        /** @var SpyPaymentPayolutionTransactionRequestLog $requestLog */
        $requestLog = $this->getRequestLogCollectionForPayment()->getLast();
        $this->assertEquals(2, $this->getRequestLogCollectionForPayment()->count());
        $this->assertEquals(Constants::PAYMENT_CODE_RE_AUTHORIZATION, $requestLog->getPaymentCode());
        $this->assertEquals($this->getOrderEntity()->getGrandTotal() / 100, $requestLog->getPresentationAmount());
        $this->assertEquals($preAuthorizationResponse->getIdentificationUniqueid(), $requestLog->getReferenceId());

        /** @var SpyPaymentPayolutionTransactionStatusLog $statusLog */
        $statusLog = $this->getStatusLogCollectionForPayment()->getLast();
        $this->assertEquals(2, $this->getStatusLogCollectionForPayment()->count());
        $this->matchStatusLogWithResponse($statusLog, $expectedResponse);
        $this->assertNotNull($statusLog->getProcessingConnectordetailConnectortxid1());
        $this->assertNotNull($statusLog->getProcessingConnectordetailPaymentreference());
    }

    public function testPreAuthorizationWithFailureResponse()
    {
        $adapterMock = new PreAuthorizationAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $preAuthorizationResponse = $facade->preAuthorizePayment($this->getPaymentEntity()->getIdPaymentPayolution());

        $adapterMock = new ReAuthorizationAdapterMock();
        $adapterMock->expectFailure();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->reAuthorizePayment($this->getPaymentEntity()->getIdPaymentPayolution());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getFailureResponse();
        $expectedResponse = $this->getResponseConverter()->fromArray($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getPaymentCode(), $response->getPaymentCode());
        $this->assertEquals($expectedResponse->getProcessingResult(), $response->getProcessingResult());
        $this->assertEquals($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertEquals($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());
        $this->assertEquals(
            $preAuthorizationResponse->getIdentificationUniqueid(),
            $expectedResponse->getIdentificationReferenceid()
        );

        /** @var SpyPaymentPayolutionTransactionRequestLog $requestLog */
        $requestLog = $this->getRequestLogCollectionForPayment()->getLast();
        $this->assertEquals(2, $this->getRequestLogCollectionForPayment()->count());
        $this->assertEquals(Constants::PAYMENT_CODE_RE_AUTHORIZATION, $requestLog->getPaymentCode());
        $this->assertEquals($this->getOrderEntity()->getGrandTotal() / 100, $requestLog->getPresentationAmount());
        $this->assertEquals($preAuthorizationResponse->getIdentificationUniqueid(), $requestLog->getReferenceId());

        /** @var SpyPaymentPayolutionTransactionStatusLog $statusLog */
        $statusLog = $this->getStatusLogCollectionForPayment()->getLast();
        $this->assertEquals(2, $this->getStatusLogCollectionForPayment()->count());
        $this->matchStatusLogWithResponse($statusLog, $expectedResponse);
    }

}
