<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Communication\Plugin\CartReorder;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductBundle\Communication\Plugin\CartReorder\OriginalOrderBundleItemCartPreReorderPlugin;
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
 * @group OriginalOrderBundleItemCartPreReorderPluginTest
 * Add your own group annotations below this line
 */
class OriginalOrderBundleItemCartPreReorderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleCommunicationTester
     */
    protected ProductBundleCommunicationTester $tester;

    /**
     * @return void
     */
    public function testPreReorderShouldAddOriginalSalesOrderItemsForBundleItems(): void
    {
        // Arrange
        $bundleItemTransfer = (new ItemTransfer())->setSku('bundle-sku-1')->setQuantity(2);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrder((new OrderTransfer())->setBundleItems(new ArrayObject([$bundleItemTransfer])))
            ->setQuote(new QuoteTransfer());

        // Act
        $resultCartReorderTransfer = (new OriginalOrderBundleItemCartPreReorderPlugin())
            ->preReorder(new CartReorderRequestTransfer(), $cartReorderTransfer);

        // Assert
        $originalSalesOrderItemTransfers = $resultCartReorderTransfer->getQuoteOrFail()->getOriginalSalesOrderItems();
        $this->assertCount(1, $originalSalesOrderItemTransfers);
        $this->assertSame('bundle-sku-1', $originalSalesOrderItemTransfers[0]->getSku());
        $this->assertSame('bundle-sku-1', $originalSalesOrderItemTransfers[0]->getGroupKey());
        $this->assertSame(2, $originalSalesOrderItemTransfers[0]->getQuantity());
    }

    /**
     * @return void
     */
    public function testPreReorderShouldDoNothingWhenNoBundleItemsExist(): void
    {
        // Arrange
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrder((new OrderTransfer())->setBundleItems(new ArrayObject()))
            ->setQuote(new QuoteTransfer());

        // Act
        $resultCartReorderTransfer = (new OriginalOrderBundleItemCartPreReorderPlugin())
            ->preReorder(new CartReorderRequestTransfer(), $cartReorderTransfer);

        // Assert
        $this->assertCount(0, $resultCartReorderTransfer->getQuoteOrFail()->getOriginalSalesOrderItems());
    }
}
