<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingList\Business\ShoppingList;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\ShoppingList\ShoppingListDependencyProvider;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemCollectionExpanderPluginInterface;
use SprykerTest\Zed\ShoppingList\ShoppingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShoppingList
 * @group Business
 * @group ShoppingList
 * @group GetShoppingListItemCollectionByUuidTest
 * Add your own group annotations below this line
 */
class GetShoppingListItemCollectionByUuidTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShoppingList\ShoppingListBusinessTester
     */
    protected ShoppingListBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetShoppingListItemCollectionByUuidRetrievesItemsByUuid(): void
    {
        // Arrange
        $firstShoppingListItem = $this->tester->createShoppingListItem();
        $secondShoppingListItem = $this->tester->createShoppingListItem();

        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->addItem((new ShoppingListItemTransfer())->setUuid($firstShoppingListItem->getUuid()))
            ->addItem((new ShoppingListItemTransfer())->setUuid($secondShoppingListItem->getUuid()));

        // Act
        $shoppingListItemCollectionTransfer = $this->tester
            ->getFacade()
            ->getShoppingListItemCollectionByUuid($shoppingListItemCollectionTransfer);

        // Assert
        $this->assertCount(2, $shoppingListItemCollectionTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testGetShoppingListItemCollectionByUuidRetrievesItemByUuid(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->tester->createShoppingListItem();

        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->addItem((new ShoppingListItemTransfer())->setUuid($shoppingListItemTransfer->getUuid()));

        // Act
        $shoppingListItemCollectionTransfer = $this->tester
            ->getFacade()
            ->getShoppingListItemCollectionByUuid($shoppingListItemCollectionTransfer);

        // Assert
        $this->assertSame(
            $shoppingListItemTransfer->getIdShoppingListItem(),
            $shoppingListItemCollectionTransfer->getItems()->offsetGet(0)->getIdShoppingListItem(),
        );
    }

    /**
     * @return void
     */
    public function testGetShoppingListItemCollectionByUuidEnsureThatPluginStackExecuted(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->tester->createShoppingListItem();

        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->addItem((new ShoppingListItemTransfer())->setUuid($shoppingListItemTransfer->getUuid()));

        $this->tester->setDependency(
            ShoppingListDependencyProvider::PLUGINS_ITEM_COLLECTION_EXPANDER,
            [$this->getShoppingListItemCollectionExpanderPluginMock()],
        );

        // Act
        $this->tester->getFacade()->getShoppingListItemCollectionByUuid($shoppingListItemCollectionTransfer);
    }

    /**
     * @return \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemCollectionExpanderPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getShoppingListItemCollectionExpanderPluginMock(): ShoppingListItemCollectionExpanderPluginInterface
    {
        $shoppingListItemCollectionExpanderPluginMock = $this
            ->getMockBuilder(ShoppingListItemCollectionExpanderPluginInterface::class)
            ->getMock();

        $shoppingListItemCollectionExpanderPluginMock
            ->expects($this->once())
            ->method('expandItemCollection')
            ->willReturnCallback(function (ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer) {
                return $shoppingListItemCollectionTransfer;
            });

        return $shoppingListItemCollectionExpanderPluginMock;
    }
}
