<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductBundle\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductBundle\Plugin\Cart\ProductBundleItemCountQuantityPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductBundle
 * @group Plugin
 * @group Cart
 * @group ProductBundleItemCountQuantityPluginTest
 * Add your own group annotations below this line
 */
class ProductBundleItemCountQuantityPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testGetItemCountReturnsZeroWhenNoItemAndNoBundleProductInCart(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $productBundleItemCountQuantityPlugin = new ProductBundleItemCountQuantityPlugin();

        // Assert
        $this->assertSame(0, $productBundleItemCountQuantityPlugin->getItemCount($quoteTransfer));
    }

    /**
     * @return void
     */
    public function testGetItemCountReturnsOneWhenOneItemWithQuantityOneInCart(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem((new ItemTransfer())->setQuantity(1));
        $productBundleItemCountQuantityPlugin = new ProductBundleItemCountQuantityPlugin();

        // Assert
        $this->assertSame(1, $productBundleItemCountQuantityPlugin->getItemCount($quoteTransfer));
    }

    /**
     * @return void
     */
    public function testGetItemCountReturnsOneWhenOneBundleProductWithQuantityOneInCart(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addBundleItem((new ItemTransfer())->setQuantity(1));
        $productBundleItemCountQuantityPlugin = new ProductBundleItemCountQuantityPlugin();

        // Assert
        $this->assertSame(1, $productBundleItemCountQuantityPlugin->getItemCount($quoteTransfer));
    }

    /**
     * @return void
     */
    public function testGetItemCountReturnsTwoWhenOneBundleProductWithQuantityOneAndOneItemWithQuantityOneInCart(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addBundleItem((new ItemTransfer())->setQuantity(1));
        $quoteTransfer->addItem((new ItemTransfer())->setQuantity(1));
        $productBundleItemCountQuantityPlugin = new ProductBundleItemCountQuantityPlugin();

        // Assert
        $this->assertSame(2, $productBundleItemCountQuantityPlugin->getItemCount($quoteTransfer));
    }
}
