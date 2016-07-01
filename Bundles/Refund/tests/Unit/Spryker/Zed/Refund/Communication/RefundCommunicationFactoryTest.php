<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Zed\Refund\Communication;

use Spryker\Zed\Refund\Communication\RefundCommunicationFactory;
use Spryker\Zed\Refund\Communication\Table\RefundTable;

/**
 * @group Spryker
 * @group Zed
 * @group Refund
 * @group Communication
 * @group RefundCommunicationFactory
 */
class RefundCommunicationFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateRefundTableShouldReturnRefundTable()
    {
        $refundCommunicationFactor = new RefundCommunicationFactory();

        $this->assertInstanceOf(RefundTable::class, $refundCommunicationFactor->createRefundTable());
    }

}
