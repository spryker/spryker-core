<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Zed\Refund\Business;

use Spryker\Zed\Refund\Business\Model\RefundCalculatorInterface;
use Spryker\Zed\Refund\Business\Model\RefundSaverInterface;
use Spryker\Zed\Refund\Business\RefundBusinessFactory;

/**
 * @group Spryker
 * @group Zed
 * @group Refund
 * @group Business
 * @group RefundBusinessFactory
 */
class RefundBusinessFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCreateRefundCalculatorShouldReturnRefundCalculatorInterface()
    {
        $refundCalculationFactory = new RefundBusinessFactory();

        $this->assertInstanceOf(RefundCalculatorInterface::class, $refundCalculationFactory->createRefundCalculator());
    }

    /**
     * @return void
     */
    public function testCreateRefundSaverShouldReturnRefundSaverInterface()
    {
        $refundCalculationFactory = new RefundBusinessFactory();

        $this->assertInstanceOf(RefundSaverInterface::class, $refundCalculationFactory->createRefundSaver());
    }

}
