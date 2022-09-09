<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationShoppingList\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationShoppingList
 * @group Business
 * @group Facade
 * @group UpdateProductConfigurationsTest
 * Add your own group annotations below this line
 */
class UpdateProductConfigurationsTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SHOPPING_LIST_ITEM_UUID = 'FAKE_SHOPPING_LIST_ITEM_UUID';

    /**
     * @var \SprykerTest\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListBusinessTester
     */
    protected ProductConfigurationShoppingListBusinessTester $tester;

    /**
     * @return void
     */
    public function testUpdateProductConfigurationsPersistsProductConfigurationData(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->tester->createShoppingListItem();
        $shoppingListItemTransfer->setProductConfigurationInstance(
            (new ProductConfigurationInstanceTransfer())
                ->setIsComplete(true)
                ->setQuantity(5),
        );

        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->addItem($shoppingListItemTransfer);

        // Act
        $this->tester
            ->getFacade()
            ->updateProductConfigurations($shoppingListItemCollectionTransfer);

        // Assert
        $productConfigurationData = $this->tester->findProductConfigurationData($shoppingListItemTransfer);
        $decodedProductConfigurationData = json_decode($productConfigurationData, true);

        $this->assertSame(5, $decodedProductConfigurationData['quantity']);
        $this->assertTrue($decodedProductConfigurationData['is_complete']);
    }

    /**
     * @return void
     */
    public function testUpdateProductConfigurationsEnsuresEncodedDataToBeSet(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->tester->createShoppingListItem();
        $shoppingListItemTransfer->setProductConfigurationInstance(
            (new ProductConfigurationInstanceTransfer())
                ->setIsComplete(true)
                ->setQuantity(5),
        );

        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->addItem($shoppingListItemTransfer);

        // Act
        $shoppingListItemCollectionTransfer = $this->tester
            ->getFacade()
            ->updateProductConfigurations($shoppingListItemCollectionTransfer);

        /** @var \Generated\Shared\Transfer\ShoppingListItemTransfer $expandedShoppingListItemTransfer */
        $expandedShoppingListItemTransfer = $shoppingListItemCollectionTransfer->getItems()
            ->getIterator()
            ->current();

        // Assert
        $this->assertNotNull($expandedShoppingListItemTransfer->getProductConfigurationInstanceData());
    }

    /**
     * @return void
     */
    public function testUpdateProductConfigurationsRemovesDataFromItemsWithoutProductConfigurationInstace(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->tester->createShoppingListItem();
        $shoppingListItemTransfer->setProductConfigurationInstanceData('{"isComplete": true, "quantity": 5}');

        $this->tester->updateProductConfigurationData($shoppingListItemTransfer);

        $shoppingListItemTransfer
            ->setProductConfigurationInstance(null)
            ->setProductConfigurationInstanceData(null);

        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->addItem($shoppingListItemTransfer);

        // Act
        $this->tester->getFacade()->updateProductConfigurations($shoppingListItemCollectionTransfer);

        // Assert
        $this->assertNull($this->tester->findProductConfigurationData($shoppingListItemTransfer));
    }

    /**
     * @return void
     */
    public function testUpdateProductConfigurationsIgnoresUndefindedShoppingListItems(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->tester->createShoppingListItem();
        $shoppingListItemTransfer->setProductConfigurationInstance(
            (new ProductConfigurationInstanceTransfer())
                ->setIsComplete(true)
                ->setQuantity(5),
        );

        $fakeShoppingListItemTransfer = (new ShoppingListItemTransfer())->setUuid(static::FAKE_SHOPPING_LIST_ITEM_UUID);

        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->addItem($shoppingListItemTransfer)
            ->addItem($fakeShoppingListItemTransfer);

        // Act
        $this->tester->getFacade()->updateProductConfigurations($shoppingListItemCollectionTransfer);

        // Assert
        $this->assertNull($this->tester->findProductConfigurationData($fakeShoppingListItemTransfer));
        $this->assertNotNull($this->tester->findProductConfigurationData($shoppingListItemTransfer));
    }

    /**
     * @return void
     */
    public function testUpdateProductConfigurationsThrowsExceptionForItemsWithoutUUID(): void
    {
        // Arrange
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())->setUuid(null);

        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->addItem($shoppingListItemTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->updateProductConfigurations($shoppingListItemCollectionTransfer);
    }
}
