<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Cart;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Cart
 * @group FilterReorderBundleItemsTest
 * Add your own group annotations below this line
 */
class FilterReorderBundleItemsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester
     */
    protected ProductBundleBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldFilterReorderBundleItemsByBundleItemIdentifier(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->setItems(new ArrayObject([
                (new ItemTransfer())->setIdSalesOrderItem(1),
                (new ItemTransfer())->setIdSalesOrderItem(2)->setRelatedBundleItemIdentifier('1'),
                (new ItemTransfer())->setIdSalesOrderItem(3)->setRelatedBundleItemIdentifier('1'),
                (new ItemTransfer())->setIdSalesOrderItem(4),
                (new ItemTransfer())->setIdSalesOrderItem(5)->setRelatedBundleItemIdentifier('2'),
                (new ItemTransfer())->setIdSalesOrderItem(6)->setRelatedBundleItemIdentifier('2'),
            ]))
            ->setBundleItems(new ArrayObject([
                (new ItemTransfer())->setBundleItemIdentifier('1'),
                (new ItemTransfer())->setBundleItemIdentifier('2'),
            ]));

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setBundleItemIdentifiers(['1']);

        // Act
        $itemTransfers = $this->tester->getFacade()->filterReorderBundleItems($cartReorderRequestTransfer, $orderTransfer);

        // Assert
        $this->assertCount(1, $itemTransfers);
        $this->assertSame('1', $itemTransfers[0]->getBundleItemIdentifier());
        $this->assertSame(2, $itemTransfers[0]->getIdSalesOrderItem());
    }

    /**
     * @return void
     */
    public function testShouldNotFilterReorderBundleItemsByWrongBundleItemIdentifier(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->setItems(new ArrayObject([
                (new ItemTransfer())->setIdSalesOrderItem(1),
                (new ItemTransfer())->setIdSalesOrderItem(2)->setRelatedBundleItemIdentifier('1'),
                (new ItemTransfer())->setIdSalesOrderItem(3)->setRelatedBundleItemIdentifier('1'),
            ]))
            ->setBundleItems(new ArrayObject([
                (new ItemTransfer())->setBundleItemIdentifier('1'),
            ]));

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setBundleItemIdentifiers(['3']);

        // Act
        $itemTransfers = $this->tester->getFacade()->filterReorderBundleItems($cartReorderRequestTransfer, $orderTransfer);

        // Assert
        $this->assertCount(0, $itemTransfers);
    }
}
