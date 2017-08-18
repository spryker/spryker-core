<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Order\MethodMapper;

use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Installment;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Order
 * @group MethodMapper
 * @group InstallmentTest
 * Add your own group annotations below this line
 */
class InstallmentTest extends BaseMethodMapperTest
{

    /**
     * @return void
     */
    public function setUp()
    {
        $this->paymentMethod = 'INSTALLMENT';

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testMapMethodDataToPayment()
    {
        $methodMapper = new Installment();
        $methodMapper->mapMethodDataToPayment(
            $this->quoteTransfer,
            $this->payment
        );

        $this->testAbstractMapMethodDataToPayment();

        $this->assertEquals('bic', $this->payment->getBankAccountBic());
        $this->assertEquals('acchold', $this->payment->getBankAccountHolder());
        $this->assertEquals('iban', $this->payment->getBankAccountIban());
        $this->assertEquals('DIRECT-DEBIT', $this->payment->getDebitPayType());
        $this->assertEquals(15, $this->payment->getInstallmentTotalAmount());
        $this->assertEquals(16, $this->payment->getInstallmentInterestAmount());
        $this->assertEquals(17, $this->payment->getInstallmentInterestRate());
        $this->assertEquals(18, $this->payment->getInstallmentLastRate());
        $this->assertEquals(19, $this->payment->getInstallmentRate());
        $this->assertEquals(28, $this->payment->getInstallmentPaymentFirstDay());
        $this->assertEquals(3, $this->payment->getInstallmentMonth());
        $this->assertEquals(12, $this->payment->getInstallmentNumberRates());
        $this->assertEquals('cs', $this->payment->getInstallmentCalculationStart());
        $this->assertEquals(20, $this->payment->getInstallmentServiceCharge());
        $this->assertEquals(21, $this->payment->getInstallmentAnnualPercentageRate());
        $this->assertEquals(1, $this->payment->getInstallmentMonthAllowed());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mockQuoteTransfer()
    {
        $quoteTransfer = parent::mockQuoteTransfer();

        $paymentTransfer = new RatepayPaymentInstallmentTransfer();
        $paymentTransfer = $this->mockPaymentTransfer($paymentTransfer);

        $quoteTransfer->getPayment()
            ->setRatepayInstallment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected function mockPaymentTransfer($paymentTransfer)
    {
        $paymentTransfer = parent::mockPaymentTransfer($paymentTransfer);

        $paymentTransfer->setBankAccountBic('bic');
        $paymentTransfer->setBankAccountHolder('acchold');
        $paymentTransfer->setBankAccountIban('iban');
        $paymentTransfer->setDebitPayType('DIRECT-DEBIT');
        $paymentTransfer->setInstallmentGrandTotalAmount(15);
        $paymentTransfer->setInstallmentInterestAmount(16);
        $paymentTransfer->setInstallmentInterestRate(17);
        $paymentTransfer->setInstallmentLastRate(18);
        $paymentTransfer->setInstallmentRate(19);
        $paymentTransfer->setInstallmentPaymentFirstDay(28);
        $paymentTransfer->setInstallmentMonth(3);
        $paymentTransfer->setInstallmentNumberRates(12);
        $paymentTransfer->setInstallmentCalculationStart('cs');
        $paymentTransfer->setInstallmentServiceCharge(20);
        $paymentTransfer->setInstallmentAnnualPercentageRate(21);
        $paymentTransfer->setInstallmentMonthAllowed(1);

        return $paymentTransfer;
    }

}
