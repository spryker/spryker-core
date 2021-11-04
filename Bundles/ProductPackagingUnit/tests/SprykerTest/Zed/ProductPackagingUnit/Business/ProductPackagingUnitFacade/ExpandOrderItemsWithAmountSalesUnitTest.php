<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductPackagingUnit\Communication\Plugin\SalesExtension\AmountSalesUnitOrderItemExpanderPreSavePlugin;
use Spryker\Zed\Sales\SalesDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group ExpandOrderItemsWithAmountSalesUnitTest
 * Add your own group annotations below this line
 */
class ExpandOrderItemsWithAmountSalesUnitTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var int
     */
    protected const FAKE_SALES_ORDER_ITEM_ID = 666;

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(SalesDependencyProvider::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS, [
            new AmountSalesUnitOrderItemExpanderPreSavePlugin(),
        ]);

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithAmountSalesUnitExpandOrderItemsWithAmountSalesUnit(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderWithProductPackagingUnits(static::DEFAULT_OMS_PROCESS_NAME);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setAmountSalesUnit(null);
        }

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithAmountSalesUnit($orderTransfer->getItems()->getArrayCopy());

        // Assert
        $this->assertNotNull($itemTransfers[0]->getAmountSalesUnit());
        $this->assertNotNull($itemTransfers[1]->getAmountSalesUnit());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithAmountSalesUnitWithoutSalesOrderItemId(): void
    {
        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithAmountSalesUnit([new ItemTransfer()]);

        // Assert
        $this->assertEmpty($itemTransfers[0]->getAmountSalesUnit());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithAmountSalesUnitWithFakeSalesOrderItemId(): void
    {
        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithAmountSalesUnit([(new ItemTransfer())->setIdSalesOrderItem(static::FAKE_SALES_ORDER_ITEM_ID)]);

        // Assert
        $this->assertEmpty($itemTransfers[0]->getAmountSalesUnit());
    }
}
