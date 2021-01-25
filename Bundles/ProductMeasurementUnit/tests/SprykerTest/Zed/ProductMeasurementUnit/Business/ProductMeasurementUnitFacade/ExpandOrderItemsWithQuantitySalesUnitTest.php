<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMeasurementUnit\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductMeasurementUnit\Communication\Plugin\SalesExtension\QuantitySalesUnitOrderItemExpanderPreSavePlugin;
use Spryker\Zed\Sales\SalesDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMeasurementUnit
 * @group Business
 * @group ExpandOrderItemsWithQuantitySalesUnitTest
 * Add your own group annotations below this line
 */
class ExpandOrderItemsWithQuantitySalesUnitTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const FAKE_SALES_ORDER_ITEM_ID = 666;

    /**
     * @var \SprykerTest\Zed\ProductMeasurementUnit\ProductMeasurementUnitBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(SalesDependencyProvider::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS, [
            new QuantitySalesUnitOrderItemExpanderPreSavePlugin(),
        ]);

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithQuantitySalesUnitExpandOrderItemsWithQuantitySalesUnit(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderWithProductMeasurementUnits(static::DEFAULT_OMS_PROCESS_NAME);

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setQuantitySalesUnit(null);
        }

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithQuantitySalesUnit($orderTransfer->getItems()->getArrayCopy());

        // Assert
        $this->assertNotNull($itemTransfers[0]->getQuantitySalesUnit());
        $this->assertNotNull($itemTransfers[1]->getQuantitySalesUnit());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithQuantitySalesUnitWithoutSalesOrderItemId(): void
    {
        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithQuantitySalesUnit([new ItemTransfer()]);

        // Assert
        $this->assertEmpty($itemTransfers[0]->getQuantitySalesUnit());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithQuantitySalesUnitWithFakeSalesOrderItemId(): void
    {
        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithQuantitySalesUnit([(new ItemTransfer())->setIdSalesOrderItem(static::FAKE_SALES_ORDER_ITEM_ID)]);

        // Assert
        $this->assertEmpty($itemTransfers[0]->getQuantitySalesUnit());
    }
}
