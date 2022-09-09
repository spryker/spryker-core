<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingList\Business\ShoppingList;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\ShoppingList\ShoppingListDependencyProvider;
use Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBulkPostSavePluginInterface;
use SprykerTest\Zed\ShoppingList\ShoppingListBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShoppingList
 * @group Business
 * @group ShoppingList
 * @group UpdateShoppingListItemByUuidTest
 * Add your own group annotations below this line
 */
class UpdateShoppingListItemByUuidTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ShoppingList\ShoppingListBusinessTester
     */
    protected ShoppingListBusinessTester $tester;

    /**
     * @return void
     */
    public function testUpdateShoppingListItemByUuidUpdatesItemByUuid(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->tester->createShoppingListItem();

        $shoppingListItemToBeUpdated = (new ShoppingListItemTransfer())
            ->setUuid($shoppingListItemTransfer->getUuid())
            ->setFkShoppingList($shoppingListItemTransfer->getFkShoppingList())
            ->setIdCompanyUser($shoppingListItemTransfer->getIdCompanyUser())
            ->setQuantity(5);

        // Act
        $shoppingListItemResponseTransfer = $this->tester
            ->getFacade()
            ->updateShoppingListItemByUuid($shoppingListItemToBeUpdated);

        // Assert
        $this->assertTrue($shoppingListItemResponseTransfer->getIsSuccess());
        $this->assertSame(5, $shoppingListItemResponseTransfer->getShoppingListItem()->getQuantity());
    }

    /**
     * @return void
     */
    public function testUpdateShoppingListItemByUuidUpdatesItemByUuidWithoutCompanyPermissions(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->tester->createShoppingListItem();

        $shoppingListItemToBeUpdated = (new ShoppingListItemTransfer())
            ->setUuid($shoppingListItemTransfer->getUuid())
            ->setFkShoppingList($shoppingListItemTransfer->getFkShoppingList())
            ->setIdCompanyUser(null)
            ->setQuantity(5);

        // Act
        $shoppingListItemResponseTransfer = $this->tester
            ->getFacade()
            ->updateShoppingListItemByUuid($shoppingListItemToBeUpdated);

        // Assert
        $this->assertFalse($shoppingListItemResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateShoppingListItemByUuidUpdatesItemByUuidWithoutUuidProperty(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->tester->createShoppingListItem();

        $shoppingListItemToBeUpdated = (new ShoppingListItemTransfer())
            ->setUuid(null)
            ->setFkShoppingList($shoppingListItemTransfer->getFkShoppingList())
            ->setIdCompanyUser($shoppingListItemTransfer->getIdCompanyUser())
            ->setQuantity(5);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateShoppingListItemByUuid($shoppingListItemToBeUpdated);
    }

    /**
     * @return void
     */
    public function testUpdateShoppingListItemByUuidUpdatesItemByUuidWithoutFkShoppingListProperty(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->tester->createShoppingListItem();

        $shoppingListItemToBeUpdated = (new ShoppingListItemTransfer())
            ->setUuid($shoppingListItemTransfer->getUuid())
            ->setFkShoppingList(null)
            ->setIdCompanyUser($shoppingListItemTransfer->getIdCompanyUser())
            ->setQuantity(5);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateShoppingListItemByUuid($shoppingListItemToBeUpdated);
    }

    /**
     * @return void
     */
    public function testUpdateShoppingListItemByUuidUpdatesItemByUuidWithoutQuantityProperty(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->tester->createShoppingListItem();

        $shoppingListItemToBeUpdated = (new ShoppingListItemTransfer())
            ->setUuid($shoppingListItemTransfer->getUuid())
            ->setFkShoppingList($shoppingListItemTransfer->getFkShoppingList())
            ->setIdCompanyUser($shoppingListItemTransfer->getIdCompanyUser())
            ->setQuantity(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateShoppingListItemByUuid($shoppingListItemToBeUpdated);
    }

    /**
     * @return void
     */
    public function testUpdateShoppingListItemByUuidEnsureThatPluginStackExecuted(): void
    {
        // Arrange
        $shoppingListItemTransfer = $this->tester->createShoppingListItem();

        $shoppingListItemToBeUpdated = (new ShoppingListItemTransfer())
            ->setUuid($shoppingListItemTransfer->getUuid())
            ->setFkShoppingList($shoppingListItemTransfer->getFkShoppingList())
            ->setIdCompanyUser($shoppingListItemTransfer->getIdCompanyUser())
            ->setQuantity(5);

        $this->tester->setDependency(
            ShoppingListDependencyProvider::PLUGINS_SHOPPING_LIST_ITEM_BULK_POST_SAVE,
            [$this->getShoppingListItemBulkPostSavePluginMock()],
        );

        // Act
        $this->tester->getFacade()->updateShoppingListItemByUuid($shoppingListItemToBeUpdated);
    }

    /**
     * @return \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBulkPostSavePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getShoppingListItemBulkPostSavePluginMock(): ShoppingListItemBulkPostSavePluginInterface
    {
        $shoppingListItemBulkPostSavePluginMock = $this
            ->getMockBuilder(ShoppingListItemBulkPostSavePluginInterface::class)
            ->getMock();

        $shoppingListItemBulkPostSavePluginMock
            ->expects($this->once())
            ->method('execute')
            ->willReturnCallback(function (ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer) {
                return $shoppingListItemCollectionTransfer;
            });

        return $shoppingListItemBulkPostSavePluginMock;
    }
}
