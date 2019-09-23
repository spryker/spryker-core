<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Refund\Communication;

use Codeception\Test\Unit;
use Spryker\Zed\Refund\Communication\RefundCommunicationFactory;
use Spryker\Zed\Refund\Communication\Table\RefundTable;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Refund
 * @group Communication
 * @group RefundCommunicationFactoryTest
 * Add your own group annotations below this line
 */
class RefundCommunicationFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateRefundTableShouldReturnRefundTable()
    {
        $refundCommunicationFactor = new RefundCommunicationFactory();

        $this->assertInstanceOf(RefundTable::class, $refundCommunicationFactor->createRefundTable());
    }
}
