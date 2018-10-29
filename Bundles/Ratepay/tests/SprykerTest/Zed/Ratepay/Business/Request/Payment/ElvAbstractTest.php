<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Request\Payment;

use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;
use SprykerTest\Zed\Ratepay\Business\Request\AbstractFacadeTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Request
 * @group Payment
 * @group ElvAbstractTest
 * Add your own group annotations below this line
 */
abstract class ElvAbstractTest extends AbstractFacadeTest
{
    /**
     * @const Payment method code.
     */
    public const PAYMENT_METHOD = RatepayConstants::ELV;

    /**
     * @return \Generated\Shared\Transfer\RatepayPaymentElvTransfer
     */
    protected function getRatepayPaymentMethodTransfer()
    {
        return (new RatepayPaymentElvTransfer())
            ->setBankAccountBic('XXXXXXXXXXX')
            ->setBankAccountIban('XXXX XXXX XXXX XXXX XXXX XX')
            ->setBankAccountHolder('TestHolder');
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayPaymentElvTransfer
     */
    protected function getPaymentTransferFromQuote()
    {
        return $this->quoteTransfer->getPayment()->getRatepayElv();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $payment
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $paymentTransfer
     *
     * @return void
     */
    protected function setRatepayPaymentDataToPaymentTransfer($payment, $paymentTransfer)
    {
        $payment->setRatepayElv($paymentTransfer);
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
            ->setCurrencyIso3('EUR')
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
}
