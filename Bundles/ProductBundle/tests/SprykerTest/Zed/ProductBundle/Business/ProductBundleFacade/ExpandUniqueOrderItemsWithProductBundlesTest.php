<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\ProductBundleFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group ProductBundleFacade
 * @group ExpandUniqueOrderItemsWithProductBundlesTest
 * Add your own group annotations below this line
 */
class ExpandUniqueOrderItemsWithProductBundlesTest extends Unit
{
    protected const FAKE_BUNDLE_ITEM_IDENTIFIER_1 = 'FAKE_BUNDLE_ITEM_IDENTIFIER_1';
    protected const FAKE_BUNDLE_ITEM_IDENTIFIER_2 = 'FAKE_BUNDLE_ITEM_IDENTIFIER_2';

    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandUniqueOrderItemsWithProductBundlesCombinesItemsWithBundleItems(): void
    {
        // Arrange
        $orderTransfer = $this->createFakeOrder();

        // Act
        $itemTransfers = $this->tester->getFacade()->expandUniqueOrderItemsWithProductBundles(
            $orderTransfer->getItems()->getArrayCopy(),
            $orderTransfer
        );

        // Assert
        $this->assertCount(4, $itemTransfers);
    }

    /**
     * @return void
     */
    public function testExpandUniqueOrderItemsWithProductBundlesWithoutProductBundles(): void
    {
        // Arrange
        $orderTransfer = $this->createFakeOrder();
        $orderTransfer->setBundleItems(new ArrayObject());

        // Act
        $itemTransfers = $this->tester->getFacade()->expandUniqueOrderItemsWithProductBundles(
            $orderTransfer->getItems()->getArrayCopy(),
            $orderTransfer
        );

        // Assert
        $this->assertCount(2, $itemTransfers);
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createFakeOrder(): OrderTransfer
    {
        $bundleItems = [
            (new ItemTransfer())
                ->setBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_1)
                ->setQuantity(1)
                ->setSumPrice(100),
            (new ItemTransfer())
                ->setBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_2)
                ->setQuantity(1)
                ->setSumPrice(100),
        ];

        $itemTransfers = [
            (new ItemTransfer()),
            (new ItemTransfer()),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_1),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_1),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_1),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_2),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_2),
        ];

        return (new OrderTransfer())
            ->setItems(new ArrayObject($itemTransfers))
            ->setBundleItems(new ArrayObject($bundleItems));
    }
}
