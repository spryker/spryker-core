<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ClientUnit\Spryker\Client\Cart\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Client\Cart\Plugin\ItemCountPlugin;

class ItemCountPluginTest extends PHPUnit_Framework_TestCase
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
