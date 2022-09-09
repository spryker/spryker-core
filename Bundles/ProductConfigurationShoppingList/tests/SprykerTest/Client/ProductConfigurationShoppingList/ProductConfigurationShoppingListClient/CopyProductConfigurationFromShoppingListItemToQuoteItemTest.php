<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClient;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use SprykerTest\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationShoppingList
 * @group ProductConfigurationShoppingListClient
 * @group CopyProductConfigurationFromShoppingListItemToQuoteItemTest
 * Add your own group annotations below this line
 */
class CopyProductConfigurationFromShoppingListItemToQuoteItemTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClientTester
     */
    protected ProductConfigurationShoppingListClientTester $tester;

    /**
     * @return void
     */
    public function testCopyProductConfigurationFromShoppingListItemToQuoteItemCopiesConfigurationToItem(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())
            ->setIsComplete(true);

        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $itemTransfer = $this->tester
            ->getClient()
            ->copyProductConfigurationFromShoppingListItemToQuoteItem($shoppingListItemTransfer, new ItemTransfer());

        // Assert
        $this->assertSame($productConfigurationInstanceTransfer, $itemTransfer->getProductConfigurationInstance());
    }

    /**
     * @return void
     */
    public function testCopyProductConfigurationFromShoppingListItemToQuoteItemCopiesNothingWhenShoppingListItemWithoutConfiguration(): void
    {
        // Arrange
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setProductConfigurationInstance(null);

        // Act
        $itemTransfer = $this->tester
            ->getClient()
            ->copyProductConfigurationFromShoppingListItemToQuoteItem($shoppingListItemTransfer, new ItemTransfer());

        // Assert
        $this->assertNull($itemTransfer->getProductConfigurationInstance());
    }
}
