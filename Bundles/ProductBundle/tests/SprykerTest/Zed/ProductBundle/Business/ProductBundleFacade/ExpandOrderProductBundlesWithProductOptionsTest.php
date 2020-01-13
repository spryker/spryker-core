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
use Generated\Shared\Transfer\ProductOptionTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group ProductBundleFacade
 * @group ExpandOrderProductBundlesWithProductOptionsTest
 * Add your own group annotations below this line
 */
class ExpandOrderProductBundlesWithProductOptionsTest extends Unit
{
    protected const FAKE_BUNDLE_ITEM_IDENTIFIER_1 = 'FAKE_BUNDLE_ITEM_IDENTIFIER_1';
    protected const FAKE_BUNDLE_ITEM_IDENTIFIER_2 = 'FAKE_BUNDLE_ITEM_IDENTIFIER_2';

    protected const FAKE_PRODUCT_OPTION_SKU_1 = 'FAKE_PRODUCT_OPTION_SKU_1';
    protected const FAKE_PRODUCT_OPTION_SKU_2 = 'FAKE_PRODUCT_OPTION_SKU_2';

    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandOrderProductBundlesWithProductOptionsCopiesUniqueProductOptionsFromItemsToBundleItems(): void
    {
        // Arrange
        $orderTransfer = $this->createFakeOrder();

        // Act
        $orderTransfer = $this->tester->getFacade()->expandOrderProductBundlesWithProductOptions($orderTransfer);

        // Assert
        $this->assertCount(2, $orderTransfer->getBundleItems()->offsetGet(0)->getProductOptions());
        $this->assertCount(2, $orderTransfer->getBundleItems()->offsetGet(1)->getProductOptions());
    }

    /**
     * @return void
     */
    public function testExpandOrderProductBundlesWithProductOptionsWithoutProductBundles(): void
    {
        // Arrange
        $orderTransfer = $this->createFakeOrder();
        $orderTransfer->setBundleItems(new ArrayObject());

        // Act
        $orderTransfer = $this->tester->getFacade()->expandOrderProductBundlesWithProductOptions($orderTransfer);

        // Assert
        $this->assertEmpty($orderTransfer->getBundleItems());
    }

    /**
     * @return void
     */
    public function testExpandOrderProductBundlesWithProductOptionsConsiderProductOptionOrdering(): void
    {
        // Arrange
        $orderTransfer = $this->createFakeOrder();

        // Act
        $orderTransfer = $this->tester->getFacade()->expandOrderProductBundlesWithProductOptions($orderTransfer);

        // Assert
        $this->assertSame(
            static::FAKE_PRODUCT_OPTION_SKU_1,
            $orderTransfer->getBundleItems()->offsetGet(1)->getProductOptions()->offsetGet(0)->getSku()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createFakeOrder(): OrderTransfer
    {
        $bundleItems = [
            (new ItemTransfer())
                ->setBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_1),
            (new ItemTransfer())
                ->setBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_2),
        ];

        $itemTransfers = [
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_1)
                ->setProductOptions(new ArrayObject([
                    (new ProductOptionTransfer())->setSku(static::FAKE_PRODUCT_OPTION_SKU_1),
                    (new ProductOptionTransfer())->setSku(static::FAKE_PRODUCT_OPTION_SKU_2),
                ])),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_1),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_2)
                ->setProductOptions(new ArrayObject([
                    (new ProductOptionTransfer())->setSku(static::FAKE_PRODUCT_OPTION_SKU_2),
                ])),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_2)
                ->setProductOptions(new ArrayObject([
                    (new ProductOptionTransfer())->setSku(static::FAKE_PRODUCT_OPTION_SKU_1),
                ])),
        ];

        return (new OrderTransfer())
            ->setItems(new ArrayObject($itemTransfers))
            ->setBundleItems(new ArrayObject($bundleItems));
    }
}
