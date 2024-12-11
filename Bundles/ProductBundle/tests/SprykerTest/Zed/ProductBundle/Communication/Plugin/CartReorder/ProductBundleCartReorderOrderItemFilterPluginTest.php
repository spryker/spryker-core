<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Communication\Plugin\CartReorder;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\ProductBundle\Communication\Plugin\CartReorder\ProductBundleCartReorderOrderItemFilterPlugin;
use SprykerTest\Zed\ProductBundle\ProductBundleCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group ProductBundleCartReorderOrderItemFilterPluginTest
 * Add your own group annotations below this line
 */
class ProductBundleCartReorderOrderItemFilterPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleCommunicationTester
     */
    protected ProductBundleCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldReplaceFilterItemsToBundleItems(): void
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
            ->setOrder($orderTransfer)
            ->setSalesOrderItemIds([])
            ->setBundleItemIdentifiers(['1']);

        $filteredItems = new ArrayObject([
            (new ItemTransfer())->setIdSalesOrderItem(1),
            (new ItemTransfer())->setIdSalesOrderItem(2)->setRelatedBundleItemIdentifier('1'),
            (new ItemTransfer())->setIdSalesOrderItem(3)->setRelatedBundleItemIdentifier('1'),
            (new ItemTransfer())->setIdSalesOrderItem(4),
            (new ItemTransfer())->setIdSalesOrderItem(5)->setRelatedBundleItemIdentifier('2'),
            (new ItemTransfer())->setIdSalesOrderItem(6)->setRelatedBundleItemIdentifier('2'),
        ]);

        // Arrange
        $filteredItems = (new ProductBundleCartReorderOrderItemFilterPlugin())->filter(
            $filteredItems,
            $cartReorderRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $filteredItems);
    }

    /**
     * @return void
     */
    public function testShouldAppendFilterItemsWithBundleItems(): void
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
            ->setOrder($orderTransfer)
            ->setSalesOrderItemIds([1, 4])
            ->setBundleItemIdentifiers(['1']);

        $filteredItems = new ArrayObject([
            (new ItemTransfer())->setIdSalesOrderItem(1),
            (new ItemTransfer())->setIdSalesOrderItem(4),
        ]);

        // Arrange
        $filteredItems = (new ProductBundleCartReorderOrderItemFilterPlugin())->filter(
            $filteredItems,
            $cartReorderRequestTransfer,
        );

        // Assert
        $this->assertCount(3, $filteredItems);
    }
}
