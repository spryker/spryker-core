<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOms\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOms
 * @group Business
 * @group Facade
 * @group SalesOmsFacadeTest
 * Add your own group annotations below this line
 */
class SalesOmsFacadeTest extends Unit
{
    protected const ORDER_ITEM_REFERENCE = 'Test01';
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesOms\SalesOmsBusinessTester
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
    public function testExpandOrderItemWithReference(): void
    {
        // Arrange
        $salesOrderItemEntityTransfer = (new SpySalesOrderItemEntityTransfer())
            ->setGroupKey('test')
            ->setName('test');
        $itemTransfer = new ItemTransfer();

        // Act
        $salesOrderItemEntityTransfer = $this->tester->getFacade()
            ->expandOrderItemWithReference($salesOrderItemEntityTransfer, $itemTransfer);

        // Assert
        $this->assertNotEmpty($salesOrderItemEntityTransfer->getOrderItemReference());
    }

    /**
     * @return void
     */
    public function testFindSalesOrderItemByOrderItemReference(): void
    {
        // Arrange
        $this->tester->createSalesOrderItemForOrder(
            $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME)->getIdSalesOrder(),
            (new SalesOrderItemTransfer())->setOrderItemReference(static::ORDER_ITEM_REFERENCE)->toArray()
        );

        // Act
        $salesOrderItemTransfer = $this->tester->getFacade()
            ->findSalesOrderItemByOrderItemReference(static::ORDER_ITEM_REFERENCE);

        // Assert
        $this->assertInstanceOf(SalesOrderItemTransfer::class, $salesOrderItemTransfer);
    }
}
