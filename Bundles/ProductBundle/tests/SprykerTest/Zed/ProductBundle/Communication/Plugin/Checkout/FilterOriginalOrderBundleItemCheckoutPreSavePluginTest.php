<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Communication\Plugin\Checkout;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductBundle\Communication\Plugin\Checkout\FilterOriginalOrderBundleItemCheckoutPreSavePlugin;
use SprykerTest\Zed\ProductBundle\ProductBundleCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group FilterOriginalOrderBundleItemCheckoutPreSavePluginTest
 * Add your own group annotations below this line
 */
class FilterOriginalOrderBundleItemCheckoutPreSavePluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleCommunicationTester
     */
    protected ProductBundleCommunicationTester $tester;

    /**
     * @return void
     */
    public function testPreSaveShouldFilterOriginalSalesOrderItemsWhenTheyAreInBundleItems(): void
    {
        // Arrange
        $bundleItemTransfer = (new ItemTransfer())->setSku('bundle-item-sku');
        $originalSalesOrderItemTransfer1 = (new OriginalSalesOrderItemTransfer())->setSku('bundle-item-sku');
        $originalSalesOrderItemTransfer2 = (new OriginalSalesOrderItemTransfer())->setSku('another-item-sku');

        $quoteTransfer = (new QuoteTransfer())
            ->addBundleItem($bundleItemTransfer)
            ->setOriginalSalesOrderItems(new ArrayObject([
                $originalSalesOrderItemTransfer1,
                $originalSalesOrderItemTransfer2,
            ]));

        // Act
        $resultQuoteTransfer = (new FilterOriginalOrderBundleItemCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertCount(1, $resultQuoteTransfer->getOriginalSalesOrderItems());
        $this->assertSame('another-item-sku', $resultQuoteTransfer->getOriginalSalesOrderItems()[0]->getSku());
    }

    /**
     * @return void
     */
    public function testPreSaveShouldNotFilterOriginalSalesOrderItemsWhenTheyAreNotInBundleItems(): void
    {
        // Arrange
        $bundleItemTransfer = (new ItemTransfer())->setSku('bundle-item-sku');
        $originalSalesOrderItemTransfer1 = (new OriginalSalesOrderItemTransfer())->setSku('original-item-sku-1');
        $originalSalesOrderItemTransfer2 = (new OriginalSalesOrderItemTransfer())->setSku('original-item-sku-2');

        $quoteTransfer = (new QuoteTransfer())
            ->addBundleItem($bundleItemTransfer)
            ->setOriginalSalesOrderItems(new ArrayObject([
                $originalSalesOrderItemTransfer1,
                $originalSalesOrderItemTransfer2,
            ]));

        // Act
        $resultQuoteTransfer = (new FilterOriginalOrderBundleItemCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertCount(2, $resultQuoteTransfer->getOriginalSalesOrderItems());
    }

    /**
     * @return void
     */
    public function testPreSaveShouldDoNothingWhenBundleItemsAreEmpty(): void
    {
        // Arrange
        $originalSalesOrderItemTransfer1 = (new OriginalSalesOrderItemTransfer())->setSku('original-item-sku-1');
        $originalSalesOrderItemTransfer2 = (new OriginalSalesOrderItemTransfer())->setSku('original-item-sku-2');

        $quoteTransfer = (new QuoteTransfer())
            ->setOriginalSalesOrderItems(new ArrayObject([
                $originalSalesOrderItemTransfer1,
                $originalSalesOrderItemTransfer2,
            ]));

        // Act
        $resultQuoteTransfer = (new FilterOriginalOrderBundleItemCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertCount(2, $resultQuoteTransfer->getOriginalSalesOrderItems());
    }

    /**
     * @return void
     */
    public function testPreSaveShouldDoNothingWhenOriginalSalesOrderItemsAreEmpty(): void
    {
        // Arrange
        $bundleItemTransfer = (new ItemTransfer())->setSku('bundle-item-sku');

        $quoteTransfer = (new QuoteTransfer())
            ->addBundleItem($bundleItemTransfer)
            ->setOriginalSalesOrderItems(new ArrayObject());

        // Act
        $resultQuoteTransfer = (new FilterOriginalOrderBundleItemCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertCount(0, $resultQuoteTransfer->getOriginalSalesOrderItems());
    }
}
