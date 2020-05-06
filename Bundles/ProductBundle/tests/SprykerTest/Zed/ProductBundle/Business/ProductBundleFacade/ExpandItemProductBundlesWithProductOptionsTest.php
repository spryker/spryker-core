<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\ProductBundleFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group ProductBundleFacade
 * @group ExpandItemProductBundlesWithProductOptionsTest
 * Add your own group annotations below this line
 */
class ExpandItemProductBundlesWithProductOptionsTest extends Unit
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
    public function testExpandItemProductBundlesWithProductOptionsCopiesUniqueProductOptionsFromItemsToBundleItems(): void
    {
        // Arrange
        $itemTransfers = $this->createBundleItemsWithOptions();

        // Act
        $itemTransfers = $this->tester->getFacade()->expandItemProductBundlesWithProductOptions($itemTransfers);

        // Assert
        $this->assertCount(2, $itemTransfers[0]->getProductBundle()->getProductOptions());
        $this->assertCount(2, $itemTransfers[2]->getProductBundle()->getProductOptions());
    }

    /**
     * @return void
     */
    public function testExpandItemProductBundlesWithProductOptionsConsiderProductOptionOrdering(): void
    {
        // Arrange
        $itemTransfers = $this->createBundleItemsWithOptions();

        // Act
        $itemTransfers = $this->tester->getFacade()->expandItemProductBundlesWithProductOptions($itemTransfers);

        // Assert
        $this->assertSame(
            static::FAKE_PRODUCT_OPTION_SKU_1,
            $itemTransfers[2]->getProductBundle()->getProductOptions()->offsetGet(0)->getSku()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function createBundleItemsWithOptions(): array
    {
        $bundleItem1 = (new ItemTransfer())
            ->setBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_1);
        $bundleItem2 = (new ItemTransfer())
            ->setBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_2);

        return [
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_1)
                ->setProductOptions(new ArrayObject([
                    (new ProductOptionTransfer())->setSku(static::FAKE_PRODUCT_OPTION_SKU_1),
                    (new ProductOptionTransfer())->setSku(static::FAKE_PRODUCT_OPTION_SKU_2),
                ]))
                ->setProductBundle($bundleItem1),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_1)
                ->setProductBundle($bundleItem1),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_2)
                ->setProductOptions(new ArrayObject([
                    (new ProductOptionTransfer())->setSku(static::FAKE_PRODUCT_OPTION_SKU_2),
                ]))
                ->setProductBundle($bundleItem2),
            (new ItemTransfer())
                ->setRelatedBundleItemIdentifier(static::FAKE_BUNDLE_ITEM_IDENTIFIER_2)
                ->setProductOptions(new ArrayObject([
                    (new ProductOptionTransfer())->setSku(static::FAKE_PRODUCT_OPTION_SKU_1),
                ]))
                ->setProductBundle($bundleItem2),
        ];
    }
}
