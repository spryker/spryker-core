<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Payolution\Business\Payment\MethodMapper;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Payolution\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Payolution\Business\Payment\Method\Invoice\Invoice;
use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTableMap;

class InvoiceTest extends Test
{

    /**
     * @return void
     */
    public function testMapToPreCheck()
    {
        $checkoutRequestTransfer = $this->getCheckoutRequestTransfer();
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $requestData = $methodMapper->buildPreCheckRequest($checkoutRequestTransfer);

        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
        $this->assertSame(ApiConstants::PAYMENT_CODE_PRE_CHECK, $requestData['PAYMENT.CODE']);
        $this->assertSame('Straße des 17. Juni 135', $requestData['ADDRESS.STREET']);
        $this->assertSame(ApiConstants::CRITERION_PRE_CHECK, 'CRITERION.PAYOLUTION_PRE_CHECK');
        $this->assertSame('TRUE', $requestData['CRITERION.PAYOLUTION_PRE_CHECK']);
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutRequestTransfer
     */
    private function getCheckoutRequestTransfer()
    {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer
            ->setGrandTotal(10000)
            ->setGrandTotalWithDiscounts(10000)
            ->setSubtotal(10000);

        $cartTransfer = new CartTransfer();
        $cartTransfer->setTotals($totalsTransfer);

        $addressTransfer = new AddressTransfer();
        $addressTransfer
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setCity('Berlin')
            ->setIso2Code('de')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135')
            ->setZipCode('10623');

        $paymentTransfer = new PayolutionPaymentTransfer();
        $paymentTransfer
            ->setGender('Male')
            ->setDateOfBirth('1970-01-01')
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(ApiConstants::BRAND_INVOICE)
            ->setAddress($addressTransfer);

        $checkoutRequestTransfer = new CheckoutRequestTransfer();
        $checkoutRequestTransfer
            ->setIdUser(null)
            ->setPaymentMethod('invoice')
            ->setCart($cartTransfer)
            ->setPayolutionPayment($paymentTransfer);

        return $checkoutRequestTransfer;
    }

    /**
     * @return void
     */
    public function testMapToPreAuthorization()
    {
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $requestData = $methodMapper->buildPreAuthorizationRequest($paymentEntityMock);

        $this->assertSame($paymentEntityMock->getEmail(), $requestData['CONTACT.EMAIL']);
        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
        $this->assertSame(ApiConstants::PAYMENT_CODE_PRE_AUTHORIZATION, $requestData['PAYMENT.CODE']);
    }

    /**
     * @return void
     */
    public function testMapToReAuthorization()
    {
        $uniqueId = uniqid('test_');
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $requestData = $methodMapper->buildReAuthorizationRequest($paymentEntityMock, $uniqueId);

        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
        $this->assertSame(ApiConstants::PAYMENT_CODE_RE_AUTHORIZATION, $requestData['PAYMENT.CODE']);
        $this->assertSame($uniqueId, $requestData['IDENTIFICATION.REFERENCEID']);
    }

    /**
     * @return void
     */
    public function testMapToReversal()
    {
        $uniqueId = uniqid('test_');
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $requestData = $methodMapper->buildRevertRequest($paymentEntityMock, $uniqueId);

        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
        $this->assertSame(ApiConstants::PAYMENT_CODE_REVERSAL, $requestData['PAYMENT.CODE']);
        $this->assertSame($uniqueId, $requestData['IDENTIFICATION.REFERENCEID']);
    }

    /**
     * @return void
     */
    public function testMapToCapture()
    {
        $uniqueId = uniqid('test_');
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $requestData = $methodMapper->buildCaptureRequest($paymentEntityMock, $uniqueId);

        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
        $this->assertSame(ApiConstants::PAYMENT_CODE_CAPTURE, $requestData['PAYMENT.CODE']);
        $this->assertSame($uniqueId, $requestData['IDENTIFICATION.REFERENCEID']);
    }

    /**
     * @return void
     */
    public function testMapToRefund()
    {
        $uniqueId = uniqid('test_');
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $requestData = $methodMapper->buildRefundRequest($paymentEntityMock, $uniqueId);

        $this->assertSame(ApiConstants::BRAND_INVOICE, $requestData['ACCOUNT.BRAND']);
        $this->assertSame(ApiConstants::PAYMENT_CODE_REFUND, $requestData['PAYMENT.CODE']);
        $this->assertSame($uniqueId, $requestData['IDENTIFICATION.REFERENCEID']);
    }

    /**
     * @return \Spryker\Zed\Payolution\PayolutionConfig
     */
    private function getBundleConfigMock()
    {
        return $this->getMock(
            'Spryker\Zed\Payolution\PayolutionConfig',
            [],
            [],
            '',
            false
        );
    }

    /**
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution
     */
    private function getPaymentEntityMock()
    {
        $orderEntityMock = $this->getMock(
            'Orm\Zed\Sales\Persistence\SpySalesOrder',
            []
        );

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution|\PHPUnit_Framework_MockObject_MockObject $paymentEntityMock */
        $paymentEntityMock = $this->getMock(
            'Orm\Zed\Payolution\Persistence\SpyPaymentPayolution',
            [
                'getSpySalesOrder',
            ]
        );
        $paymentEntityMock
            ->expects($this->any())
            ->method('getSpySalesOrder')
            ->will($this->returnValue($orderEntityMock));

        $paymentEntityMock
            ->setIdPaymentPayolution(1)
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(ApiConstants::BRAND_INVOICE)
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('john@doe.com')
            ->setSalutation('Mr')
            ->setDateOfBirth('1970-01-01')
            ->setCountryIso2Code('de')
            ->setCity('Berlin')
            ->setStreet('Straße des 17. Juni 135')
            ->setZipCode('10623')
            ->setGender(SpyPaymentPayolutionTableMap::COL_GENDER_FEMALE);

        return $paymentEntityMock;
    }

}
