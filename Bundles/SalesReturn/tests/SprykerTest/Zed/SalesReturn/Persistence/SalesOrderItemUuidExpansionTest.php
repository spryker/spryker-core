<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn\Persistence;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturn
 * @group Persistence
 * @group SalesOrderItemUuidExpansionTest
 * Add your own group annotations below this line
 */
class SalesOrderItemUuidExpansionTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesReturn\SalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testHaveOrderWithSalesOrderUuidProperty(): void
    {
        // Arrange

        // Act
        $orderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        // Assert
        $this->assertNotEmpty($orderTransfer->getOrderItems()->offsetGet(0)->getUuid());
    }
}
