<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Payolution\Business;

use Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http\PreCheckAdapterMock;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;

class PayolutionFacadePreCheckTest extends AbstractFacadeTest
{

    public function testPreCheckPaymentWithSuccessResponse()
    {
        $adapterMock = new PreCheckAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->preCheckPayment($this->getOrderTransfer());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getSuccessResponse();
        $expectedResponse = $this->getResponseConverter()->fromArray($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertSame($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertSame($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());
    }

    public function testPreCheckPaymentWithFailureResponse()
    {
        $adapterMock = (new PreCheckAdapterMock())->expectFailure();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->preCheckPayment($this->getOrderTransfer());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getFailureResponse();
        $expectedResponse = $this->getResponseConverter()->fromArray($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertSame($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertSame($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());
    }

    /**
     * @return OrderTransfer
     */
    private function getOrderTransfer()
    {
        $totalsTransfer = (new TotalsTransfer())->setGrandTotal(100000);

        $addressTransfer = (new AddressTransfer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setEmail('john@doe.com')
            ->setCity('Berlin')
            ->setIso2Code('de')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135')
            ->setZipCode('10623');

        $paymentTransfer = (new PayolutionPaymentTransfer())
            ->setGender('Male')
            ->setDateOfBirth('1970-01-01')
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE)
            ->setAddress($addressTransfer);

        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder(1)
            ->setPayolutionPayment($paymentTransfer)
            ->setTotals($totalsTransfer);

        return $orderTransfer;
    }

}
