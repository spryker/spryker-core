<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Order\MethodMapper;

use Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Invoice;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Order
 * @group MethodMapper
 * @group InvoiceTest
 */
class InvoiceTest extends BaseMethodMapperTest
{

    /**
     * @return void
     */
    public function setUp()
    {
        $this->paymentMethod = 'INVOICE';

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testMapMethodDataToPayment()
    {
        $methodMapper = new Invoice();
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

        $paymentTransfer = new RatepayPaymentInvoiceTransfer();
        $paymentTransfer = $this->mockPaymentTransfer($paymentTransfer);

        $quoteTransfer->getPayment()
            ->setRatepayInvoice($paymentTransfer);

        return $quoteTransfer;
    }

}
