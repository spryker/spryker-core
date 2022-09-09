<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationShoppingList\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
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
 * @group ExpandShoppingListItemsWithProductConfigurationTest
 * Add your own group annotations below this line
 */
class ExpandShoppingListItemsWithProductConfigurationTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListBusinessTester
     */
    protected ProductConfigurationShoppingListBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandShoppingListItemsWithProductConfigurationCopiesDataToDecodedInstanceFormat(): void
    {
        // Arrange
        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->addItem((new ShoppingListItemTransfer())
                ->setProductConfigurationInstanceData('{"isComplete": true, "quantity": 5}'));

        // Act
        $shoppingListItemCollectionTransfer = $this->tester
            ->getFacade()
            ->expandShoppingListItemsWithProductConfiguration($shoppingListItemCollectionTransfer);

        /** @var \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null $productConfigurationInstanceTransfer */
        $productConfigurationInstanceTransfer = $shoppingListItemCollectionTransfer->getItems()
            ->getIterator()
            ->current()
            ->getProductConfigurationInstance();

        // Assert
        $this->assertNotNull($productConfigurationInstanceTransfer);
        $this->assertTrue($productConfigurationInstanceTransfer->getIsComplete());
        $this->assertSame(5, $productConfigurationInstanceTransfer->getQuantity());
    }

    /**
     * @return void
     */
    public function testExpandShoppingListItemsWithProductConfigurationDoesNothingForItemsWithoutData(): void
    {
        // Arrange
        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->addItem((new ShoppingListItemTransfer())
                ->setProductConfigurationInstanceData(null));

        // Act
        $shoppingListItemCollectionTransfer = $this->tester
            ->getFacade()
            ->expandShoppingListItemsWithProductConfiguration($shoppingListItemCollectionTransfer);

        /** @var \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null $productConfigurationInstanceTransfer */
        $productConfigurationInstanceTransfer = $shoppingListItemCollectionTransfer->getItems()
            ->getIterator()
            ->current()
            ->getProductConfigurationInstance();

        // Assert
        $this->assertNull($productConfigurationInstanceTransfer);
    }
}
