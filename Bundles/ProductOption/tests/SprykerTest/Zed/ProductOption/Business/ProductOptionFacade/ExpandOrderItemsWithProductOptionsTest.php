<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business\ProductOptionFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group ProductOptionFacade
 * @group ExpandOrderItemsWithProductOptionsTest
 * Add your own group annotations below this line
 */
class ExpandOrderItemsWithProductOptionsTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';
    protected const FAKE_SALES_ORDER_ITEM_ID = 666;

    /**
     * @var \SprykerTest\Zed\ProductOption\ProductOptionBusinessTester
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
    public function testExpandOrderItemsWithProductOptionsExpandOrderItemsWithProductOptions(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderWithProductOptions(static::DEFAULT_OMS_PROCESS_NAME);
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $itemTransfer->setProductOptions(new ArrayObject())
            ->setUnitPriceToPayAggregation(null)
            ->setSumProductOptionPriceAggregation(null);

        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithProductOptions($orderTransfer->getItems()->getArrayCopy());

        // Assert
        $this->assertNotEmpty($itemTransfers[0]->getProductOptions());
        $this->assertNotNull($itemTransfers[0]->getUnitProductOptionPriceAggregation());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithProductOptionsWithoutSalesOrderItemId(): void
    {
        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithProductOptions([new ItemTransfer()]);

        // Assert
        $this->assertEmpty($itemTransfers[0]->getProductOptions());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemsWithProductOptionsWithFakeSalesOrderItemId(): void
    {
        // Act
        $itemTransfers = $this->tester
            ->getFacade()
            ->expandOrderItemsWithProductOptions([(new ItemTransfer())->setIdSalesOrderItem(static::FAKE_SALES_ORDER_ITEM_ID)]);

        // Assert
        $this->assertEmpty($itemTransfers[0]->getProductOptions());
    }
}
