<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Payolution\Business;

use Functional\SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http\PreCheckAdapterMock;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Shared\Payolution\PayolutionApiConstants;

class PayolutionFacadePreCheckTest extends AbstractFacadeTest
{

    public function testPreCheckPaymentWithSuccessResponse()
    {
        $adapterMock = new PreCheckAdapterMock();
        $facade = $this->getFacadeMock($adapterMock);
        $response = $facade->preCheckPayment($this->getCheckoutRequestTransfer());

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
        $response = $facade->preCheckPayment($this->getCheckoutRequestTransfer());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);

        $expectedResponseData = $adapterMock->getFailureResponse();
        $expectedResponse = $this->getResponseConverter()->fromArray($expectedResponseData);

        $this->assertEquals($expectedResponse, $response);
        $this->assertSame($expectedResponse->getProcessingReasonCode(), $response->getProcessingReasonCode());
        $this->assertSame($expectedResponse->getProcessingStatusCode(), $response->getProcessingStatusCode());
    }

    /**
     * @return CheckoutRequestTransfer
     */
    private function getCheckoutRequestTransfer()
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer
            ->setSku('1234567890')
            ->setQuantity(1)
            ->setPriceToPay(10000)
            ->setGrossPrice(10000 * 1.19)
            ->setName('Socken')
            ->setTaxSet(new TaxSetTransfer());

        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer
            ->setGrandTotal(10000)
            ->setGrandTotalWithDiscounts(10000)
            ->setSubtotal(10000);

        $cartTransfer = new CartTransfer();
        $cartTransfer
            ->addItem($itemTransfer)
            ->setTotals($totalsTransfer);

        $billingAddressTransfer = new AddressTransfer();
        $billingAddressTransfer
            ->setIso2Code('de')
            ->setEmail('john@doe.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135')
            ->setZipCode('10623')
            ->setCity('Berlin');

        $shippingAddressTransfer = new AddressTransfer();
        $shippingAddressTransfer
            ->setIso2Code('de')
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
            ->setIso2Code('de')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135')
            ->setZipCode('10623');

        $paymentTransfer = (new PayolutionPaymentTransfer())
            ->setGender('Male')
            ->setDateOfBirth('1970-01-01')
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(PayolutionApiConstants::BRAND_INVOICE)
            ->setAddress($paymentAddressTransfer);

        $checkoutRequestTransfer = new CheckoutRequestTransfer();
        $checkoutRequestTransfer
            ->setIdUser(null)
            ->setShippingAddress($shippingAddressTransfer)
            ->setBillingAddress($billingAddressTransfer)
            ->setPaymentMethod('invoice')
            ->setCart($cartTransfer)
            ->setPayolutionPayment($paymentTransfer);

        return $checkoutRequestTransfer;
    }

}
