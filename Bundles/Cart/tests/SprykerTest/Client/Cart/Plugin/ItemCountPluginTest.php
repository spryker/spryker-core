<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Cart\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\Plugin\ItemCountPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Cart
 * @group Plugin
 * @group ItemCountPluginTest
 * Add your own group annotations below this line
 */
class ItemCountPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testGetItemCountReturnsOWhenNoItemInQuote()
    {
        $quoteTransfer = new QuoteTransfer();

        $itemCountPlugin = new ItemCountPlugin();
        $this->assertSame(0, $itemCountPlugin->getItemCount($quoteTransfer));
    }

    /**
     * @return void
     */
    public function testGetItemCountReturnsNumberOfItemsInCart()
    {
        $quoteTransfer = new QuoteTransfer();
        $itemTransfer = new ItemTransfer();
        $quoteTransfer->addItem($itemTransfer);

        $itemCountPlugin = new ItemCountPlugin();
        $this->assertSame(1, $itemCountPlugin->getItemCount($quoteTransfer));
    }
}
