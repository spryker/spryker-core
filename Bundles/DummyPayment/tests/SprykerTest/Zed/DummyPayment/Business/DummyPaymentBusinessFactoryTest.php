<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DummyPayment\Business;

use Codeception\Test\Unit;
use Spryker\Zed\DummyPayment\Business\DummyPaymentBusinessFactory;
use Spryker\Zed\DummyPayment\Business\Model\Payment\RefundInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DummyPayment
 * @group Business
 * @group DummyPaymentBusinessFactoryTest
 * Add your own group annotations below this line
 */
class DummyPaymentBusinessFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateRefundShouldReturnRefundInterface()
    {
        $dummyPaymentBusinessFactory = new DummyPaymentBusinessFactory();
        $refund = $dummyPaymentBusinessFactory->createRefund();

        $this->assertInstanceOf(RefundInterface::class, $refund);
    }
}
