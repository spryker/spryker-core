<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Order\MethodMapper;

use Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Prepayment;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Order
 * @group MethodMapper
 * @group PrepaymentTest
 * Add your own group annotations below this line
 */
class PrepaymentTest extends BaseMethodMapperTest
{

    /**
     * @return void
     */
    public function setUp()
    {
        $this->paymentMethod = 'PREPAYMENT';

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testMapMethodDataToPayment()
    {
        $methodMapper = new Prepayment();
        $methodMapper->mapMethodDataToPayment(
            $this->quoteTransfer,
            $this->payment
        );

        $this->testAbstractMapMethodDataToPayment();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mockQuoteTransfer()
    {
        $quoteTransfer = parent::mockQuoteTransfer();

        $paymentTransfer = new RatepayPaymentPrepaymentTransfer();
        $paymentTransfer = $this->mockPaymentTransfer($paymentTransfer);

        $quoteTransfer->getPayment()
            ->setRatepayPrepayment($paymentTransfer);

        return $quoteTransfer;
    }

}
