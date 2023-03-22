<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductBundle\ProductBundleClient;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use SprykerTest\Client\ProductBundle\ProductBundleClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductBundle
 * @group ProductBundleClient
 * @group GetGroupedBundleItemsTest
 * Add your own group annotations below this line
 */
class GetGroupedBundleItemsTest extends Unit
{
    /**
     * @uses \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_PRODUCT
     *
     * @var string
     */
    protected const BUNDLE_PRODUCT = 'bundleProduct';

    /**
     * @uses \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_ITEMS
     *
     * @var string
     */
    protected const BUNDLE_ITEMS = 'bundleItems';

    /**
     * @var string
     */
    protected const BUNDLE_ITEM_IDENTIFIER = 'BUNDLE_ITEM_IDENTIFIER';

    /**
     * @var string
     */
    protected const BUNDLE_ITEM_IDENTIFIER_2 = 'BUNDLE_ITEM_IDENTIFIER_2';

    /**
     * @var string
     */
    protected const BUNDLE_PRODUCT_SKU = 'BUNDLE_PRODUCT_SKU';

    /**
     * @var string
     */
    protected const BUNDLE_PRODUCT_SKU_2 = 'BUNDLE_PRODUCT_SKU_2';

    /**
     * @var string
     */
    protected const PRODUCT_SKU = 'PRODUCT_SKU';

    /**
     * @var string
     */
    protected const PRODUCT_SKU_2 = 'PRODUCT_SKU_2';

    /**
     * @var string
     */
    protected const PRODUCT_OPTION_SKU = 'PRODUCT_OPTION_SKU';

    /**
     * @var string
     */
    protected const PRODUCT_OPTION_SKU_2 = 'PRODUCT_OPTION_SKU_2';

    /**
     * @var \SprykerTest\Client\ProductBundle\ProductBundleClientTester
     */
    protected ProductBundleClientTester $tester;

    /**
     * @return void
     */
    public function testBundleShouldBeGroupedByBundleSkuWhenItemsDoNotHaveOptions(): void
    {
        // Arrange
        $bundleItemTransfers = new ArrayObject([
            (new ItemTransfer())
            ->setBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER)
            ->setSku(static::BUNDLE_PRODUCT_SKU),
        ]);

        $itemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setSku(static::PRODUCT_SKU)
                ->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
            (new ItemTransfer())
                ->setSku(static::PRODUCT_SKU_2)
                ->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
        ]);

        // Act
        $groupedProductBundles = $this->tester->getClient()->getGroupedBundleItems($itemTransfers, $bundleItemTransfers);

        // Assert
        $this->assertCount(1, $groupedProductBundles);
        $this->assertArrayHasKey(static::BUNDLE_PRODUCT_SKU, $groupedProductBundles);
        $this->assertCount(2, $groupedProductBundles[static::BUNDLE_PRODUCT_SKU][static::BUNDLE_ITEMS]);
    }

    /**
     * @return void
     */
    public function testBundleShouldBeGroupedByGeneratedBundleGroupKeyWhenItemsHaveOptions(): void
    {
        // Arrange
        $bundleItemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER)
                ->setSku(static::BUNDLE_PRODUCT_SKU),
        ]);

        $itemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setProductOptions(new ArrayObject([
                    (new ProductOptionTransfer())->setSku(static::PRODUCT_OPTION_SKU),
                    (new ProductOptionTransfer())->setSku(static::PRODUCT_OPTION_SKU_2),
                ]))
                ->setSku(static::PRODUCT_SKU)
                ->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
            (new ItemTransfer())
                ->setSku(static::PRODUCT_SKU_2)
                ->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
        ]);

        // Act
        $groupedProductBundles = $this->tester->getClient()->getGroupedBundleItems($itemTransfers, $bundleItemTransfers);

        // Assert
        $this->assertCount(1, $groupedProductBundles);

        $bundleGroupKey = sprintf('%s_%s_%s', static::BUNDLE_PRODUCT_SKU, static::PRODUCT_OPTION_SKU, static::PRODUCT_OPTION_SKU_2);
        $this->assertArrayHasKey($bundleGroupKey, $groupedProductBundles);
        $this->assertCount(2, $groupedProductBundles[$bundleGroupKey][static::BUNDLE_ITEMS]);
    }

    /**
     * @return void
     */
    public function testBundleItemsWithSameGroupKeyShouldBeMerged(): void
    {
        // Arrange
        $bundleItemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setGroupKey(static::BUNDLE_PRODUCT_SKU)
                ->setBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER)
                ->setSku(static::BUNDLE_PRODUCT_SKU)
                ->setQuantity(1)
                ->setSumSubtotalAggregation(100)
                ->setUnitSubtotalAggregation(100),
            (new ItemTransfer())
                ->setGroupKey(static::BUNDLE_PRODUCT_SKU_2)
                ->setBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER_2)
                ->setSku(static::BUNDLE_PRODUCT_SKU_2)
                ->setQuantity(1)
                ->setSumSubtotalAggregation(100)
                ->setUnitSubtotalAggregation(100),
            (new ItemTransfer())
                ->setGroupKey(static::BUNDLE_PRODUCT_SKU)
                ->setBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER)
                ->setSku(static::BUNDLE_PRODUCT_SKU)
                ->setQuantity(2)
                ->setSumSubtotalAggregation(300)
                ->setUnitSubtotalAggregation(200),
        ]);

        $itemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setSku(static::PRODUCT_SKU)
                ->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
            (new ItemTransfer())
                ->setSku(static::PRODUCT_SKU)
                ->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER_2),
        ]);

        // Act
        $groupedProductBundles = $this->tester->getClient()->getGroupedBundleItems($itemTransfers, $bundleItemTransfers);

        // Assert
        $this->assertCount(2, $groupedProductBundles);

        /** @var \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer */
        $bundleItemTransfer = $groupedProductBundles[static::BUNDLE_PRODUCT_SKU][static::BUNDLE_PRODUCT];
        $this->assertSame(3, $bundleItemTransfer->getQuantity());
        $this->assertSame(400, $bundleItemTransfer->getSumSubtotalAggregation());
        $this->assertSame(300, $bundleItemTransfer->getUnitSubtotalAggregation());
        $this->assertCount(1, $groupedProductBundles[static::BUNDLE_PRODUCT_SKU][static::BUNDLE_ITEMS]);

        /** @var \Generated\Shared\Transfer\ItemTransfer $secondBundleItemTransfer */
        $secondBundleItemTransfer = $groupedProductBundles[static::BUNDLE_PRODUCT_SKU_2][static::BUNDLE_PRODUCT];
        $this->assertSame(1, $secondBundleItemTransfer->getQuantity());
        $this->assertSame(100, $secondBundleItemTransfer->getSumSubtotalAggregation());
        $this->assertSame(100, $secondBundleItemTransfer->getUnitSubtotalAggregation());
        $this->assertCount(1, $groupedProductBundles[static::BUNDLE_PRODUCT_SKU_2][static::BUNDLE_ITEMS]);
    }

    /**
     * @return void
     */
    public function testItemsWithSameSkuAndRelatedBundleIdentifierShouldBeMerged(): void
    {
        // Arrange
        $bundleItemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER)
                ->setSku(static::BUNDLE_PRODUCT_SKU),
        ]);

        $itemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setSku(static::PRODUCT_SKU)
                ->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER)
                ->setQuantity(1),
            (new ItemTransfer())
                ->setSku(static::PRODUCT_SKU)
                ->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER)
                ->setQuantity(2),
        ]);

        // Act
        $groupedProductBundles = $this->tester->getClient()->getGroupedBundleItems($itemTransfers, $bundleItemTransfers);

        // Assert
        $this->assertCount(1, $groupedProductBundles);
        $this->assertCount(1, $groupedProductBundles[static::BUNDLE_PRODUCT_SKU][static::BUNDLE_ITEMS]);

        $itemKey = sprintf('%s%s', static::PRODUCT_SKU, static::BUNDLE_ITEM_IDENTIFIER);
        $this->assertSame(3, $groupedProductBundles[static::BUNDLE_PRODUCT_SKU][static::BUNDLE_ITEMS][$itemKey]->getQuantity());
    }

    /**
     * @return void
     */
    public function testSingleItemShouldNotBeAddedToBundle(): void
    {
        // Arrange
        $bundleItemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER)
                ->setSku(static::BUNDLE_PRODUCT_SKU),
            (new ItemTransfer())
                ->setBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER_2)
                ->setSku(static::BUNDLE_PRODUCT_SKU_2),
        ]);

        $itemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setSku(static::PRODUCT_SKU)
                ->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER),
            (new ItemTransfer())
                ->setSku(static::PRODUCT_SKU)
                ->setRelatedBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER_2),
            (new ItemTransfer())->setSku(static::PRODUCT_SKU_2),
        ]);

        // Act
        $groupedProductBundles = $this->tester->getClient()->getGroupedBundleItems($itemTransfers, $bundleItemTransfers);

        // Assert
        $this->assertCount(3, $groupedProductBundles);
        $this->assertCount(1, $groupedProductBundles[static::BUNDLE_PRODUCT_SKU][static::BUNDLE_ITEMS]);
        $this->assertCount(1, $groupedProductBundles[static::BUNDLE_PRODUCT_SKU_2][static::BUNDLE_ITEMS]);
        $this->assertSame(static::PRODUCT_SKU_2, $groupedProductBundles[0]->getSku());
    }

    /**
     * @return void
     */
    public function testBundlesWithoutItemsShouldBeRemoved(): void
    {
        // Arrange
        $bundleItemTransfers = new ArrayObject([
            (new ItemTransfer())
                ->setBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER)
                ->setSku(static::BUNDLE_PRODUCT_SKU),
            (new ItemTransfer())
                ->setBundleItemIdentifier(static::BUNDLE_ITEM_IDENTIFIER_2)
                ->setSku(static::BUNDLE_PRODUCT_SKU_2),
        ]);

        // Act
        $groupedProductBundles = $this->tester->getClient()->getGroupedBundleItems(new ArrayObject(), $bundleItemTransfers);

        // Assert
        $this->assertCount(0, $groupedProductBundles);
    }
}
