<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationShoppingList\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use SprykerTest\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationShoppingList
 * @group Business
 * @group Facade
 * @group CopyProductConfigurationFromQuoteItemToShoppingListItemTest
 * Add your own group annotations below this line
 */
class CopyProductConfigurationFromQuoteItemToShoppingListItemTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListBusinessTester
     */
    protected ProductConfigurationShoppingListBusinessTester $tester;

    /**
     * @return void
     */
    public function testCopyProductConfigurationFromQuoteItemToShoppingListItemCopiesItemConfigurationToShoppingListItem(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())
            ->setDisplayData('{}')
            ->setIsComplete(false);

        $itemTransfer = (new ItemTransfer())
            ->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $shoppingListItemTransfer = $this->tester
            ->getFacade()
            ->copyProductConfigurationFromQuoteItemToShoppingListItem($itemTransfer, new ShoppingListItemTransfer());

        // Assert
        $this->assertSame($productConfigurationInstanceTransfer, $shoppingListItemTransfer->getProductConfigurationInstance());
    }

    /**
     * @return void
     */
    public function testCopyProductConfigurationFromQuoteItemToShoppingListItemDoesNothingForItemWithoutConfiguration(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())
            ->setProductConfigurationInstance(null);

        // Act
        $shoppingListItemTransfer = $this->tester
            ->getFacade()
            ->copyProductConfigurationFromQuoteItemToShoppingListItem($itemTransfer, new ShoppingListItemTransfer());

        // Assert
        $this->assertNull($shoppingListItemTransfer->getProductConfigurationInstance());
    }
}
