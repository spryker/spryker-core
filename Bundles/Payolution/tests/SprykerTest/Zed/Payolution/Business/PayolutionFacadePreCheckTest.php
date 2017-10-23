<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payolution\Business;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Payolution\PayolutionConstants;
use SprykerTest\Zed\Payolution\Business\Api\Adapter\Http\PreCheckAdapterMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Payolution
 * @group Business
 * @group Facade
 * @group PayolutionFacadePreCheckTest
 * Add your own group annotations below this line
 */
class PayolutionFacadePreCheckTest extends AbstractFacadeTest
{
    /**
     * @return void
     */
    public function testPreCheckPaymentWithSuccessResponse()
    {
        $adapterMock = new PreCheckAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->preCheckPayment($this->getCheckoutRequestTransfer());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionTransactionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getSuccessResponse();
        $expectedResponse = $this->getResponseConverter()->toTransactionResponseTransfer($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertSame($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertSame($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());
    }

    /**
     * @return void
     */
    public function testPreCheckPaymentWithFailureResponse()
    {
        $adapterMock = (new PreCheckAdapterMock())->expectFailure();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->preCheckPayment($this->getCheckoutRequestTransfer());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionTransactionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getFailureResponse();
        $expectedResponse = $this->getResponseConverter()->toTransactionResponseTransfer($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertSame($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertSame($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function getCheckoutRequestTransfer()
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer
            ->setSku('1234567890')
            ->setQuantity(1)
            ->setUnitSubtotalAggregation(10000)
            ->setName('Socken');

        $billingAddressTransfer = new AddressTransfer();
        $billingAddressTransfer
            ->setIso2Code('DE')
            ->setEmail('john@doe.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135')
            ->setZipCode('10623')
            ->setCity('Berlin');

        $shippingAddressTransfer = new AddressTransfer();
        $shippingAddressTransfer
            ->setIso2Code('DE')
            ->setEmail('john@doe.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setAddress1('Fraunhoferstraße')
            ->setAddress2('120')
            ->setZipCode('80469')
            ->setCity('München');

        $paymentAddressTransfer = (new AddressTransfer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setEmail('john@doe.com')
            ->setCity('Berlin')
            ->setIso2Code('DE')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135')
            ->setZipCode('10623');

        $payolutionPaymentTransfer = (new PayolutionPaymentTransfer())
            ->setGender('Male')
            ->setDateOfBirth('1970-01-01')
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(PayolutionConstants::BRAND_INVOICE)
            ->setAddress($paymentAddressTransfer);

        $quoteTransfer = new QuoteTransfer();

        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer
            ->setGrandTotal(10000)
            ->setSubtotal(10000);

        $quoteTransfer->setTotals($totalsTransfer);

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentSelection('no_payment');
        $paymentTransfer->setPayolution($payolutionPaymentTransfer);
        $quoteTransfer->setPayment($paymentTransfer);

        $quoteTransfer
            ->setShippingAddress($shippingAddressTransfer)
            ->setBillingAddress($billingAddressTransfer);

        return $quoteTransfer;
    }
}
