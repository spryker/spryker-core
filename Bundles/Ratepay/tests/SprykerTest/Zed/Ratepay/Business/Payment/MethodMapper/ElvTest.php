<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Spryker\Zed\Ratepay\Business\Request\Payment\Method\Elv;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Payment
 * @group MethodMapper
 * @group ElvTest
 * Add your own group annotations below this line
 */
class ElvTest extends AbstractMethodMapperTest
{

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Method\MethodInterface
     */
    public function getPaymentMethod()
    {
        return new Elv(
            $this->createApiRequestFactory(),
            $this->createMapperFactory(),
            $this->getQueryContainerMock()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function getPaymentTransfer()
    {
        $paymentTransfer = new RatepayPaymentElvTransfer();
        $this->setRatepayPaymentEntityData($paymentTransfer);

        $payment = new PaymentTransfer();
        $payment->setRatepayElv($paymentTransfer);

        return $payment;
    }

    /**
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay|\Generated\Shared\Transfer\RatepayPaymentElvTransfer $ratepayPaymentEntity
     *
     * @return void
     */
    protected function setRatepayPaymentEntityData($ratepayPaymentEntity)
    {
        $ratepayPaymentEntity
            ->setResultCode(503)
            ->setDateOfBirth('11.11.1991')
            ->setCurrencyIso3('iso3')
            ->setCustomerAllowCreditInquiry(true)
            ->setGender('M')
            ->setPhone('123456789')
            ->setIpAddress('127.0.0.1')
            ->setPaymentType('ELV')
            ->setTransactionId('58-201604122719694')
            ->setTransactionShortId('5QTZ.2VWD.OMWW.9D3E')
            ->setDeviceFingerprint('122356')

            ->setBankAccountBic('XXXXXXXXXXX')
            ->setBankAccountIban('XXXX XXXX XXXX XXXX XXXX XX')
            ->setBankAccountHolder('TestHolder');
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Payment\Request $request
     *
     * @return void
     */
    protected function testPaymentSpecificRequestData($request)
    {
        $this->assertEquals('invoice', $this->requestTransfer->getPayment()->getMethod());

        $this->assertNull($this->requestTransfer->getPayment()->getInstallmentDetails());
        $this->assertNull($this->requestTransfer->getPayment()->getDebitPayType());

        $this->assertEquals('fn ln', $this->requestTransfer->getBankAccount()->getOwner());
        $this->assertEquals('bic', $this->requestTransfer->getBankAccount()->getBicSwift());
        $this->assertEquals('iban', $this->requestTransfer->getBankAccount()->getIban());
    }

}
