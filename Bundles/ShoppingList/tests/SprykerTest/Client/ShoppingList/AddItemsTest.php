<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingList;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Client\ShoppingList\Creator\ShoppingListItemCreator;
use Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToZedRequestClientInterface;
use Spryker\Client\ShoppingList\PermissionUpdater\PermissionUpdaterInterface;
use Spryker\Client\ShoppingList\Remover\ShoppingListSessionRemoverInterface;
use Spryker\Client\ShoppingList\ShoppingList\ShoppingListAddItemExpanderInterface;
use Spryker\Client\ShoppingList\Zed\ShoppingListStubInterface;
use Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListExpanderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ShoppingList
 * @group AddItemsTest
 * Add your own group annotations below this line
 */
class AddItemsTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ShoppingList\ShoppingListClientTester
     */
    protected ShoppingListClientTester $tester;

    /**
     * @return void
     */
    public function testAddItemsMakeSureThatExpanderPluginStackWasExecuted(): void
    {
        // Arrange
        $shoppingListTransfer = (new ShoppingListTransfer())
            ->addItem(new ShoppingListItemTransfer());

        // Assert
        $shoppingListWriterMock = $this->createShoppingListItemCreatorMock([$this->getShoppingListExpanderPluginMock()]);

        // Act
        $shoppingListWriterMock->addItems($shoppingListTransfer);
    }

    /**
     * @param array<\Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListExpanderPluginInterface> $shoppingListExpanderPlugins
     *
     * @return \Spryker\Client\ShoppingList\Creator\ShoppingListItemCreator
     */
    protected function createShoppingListItemCreatorMock(array $shoppingListExpanderPlugins): ShoppingListItemCreator
    {
        return $this->getMockBuilder(ShoppingListItemCreator::class)
            ->setConstructorArgs([
                $this->createShoppingListStubInterfaceMock(),
                $this->createShoppingListToZedRequestClientInterfaceMock(),
                $this->createPermissionUpdaterInterfaceMock(),
                $this->createShoppingListSessionRemoverInterfaceMock(),
                $this->createShoppingListAddItemExpanderInterfaceMock(),
                $shoppingListExpanderPlugins,
            ])
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @return \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToQuoteClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createShoppingListStubInterfaceMock(): ShoppingListStubInterface
    {
        return $this->getMockBuilder(ShoppingListStubInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @return \Spryker\Client\ShoppingList\Remover\ShoppingListSessionRemoverInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createShoppingListSessionRemoverInterfaceMock(): ShoppingListSessionRemoverInterface
    {
        return $this->getMockBuilder(ShoppingListSessionRemoverInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @return \Spryker\Client\ShoppingList\ShoppingList\ShoppingListAddItemExpanderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createShoppingListAddItemExpanderInterfaceMock(): ShoppingListAddItemExpanderInterface
    {
        return $this->getMockBuilder(ShoppingListAddItemExpanderInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @return \Spryker\Client\ShoppingList\Dependency\Client\ShoppingListToZedRequestClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createShoppingListToZedRequestClientInterfaceMock(): ShoppingListToZedRequestClientInterface
    {
        return $this->getMockBuilder(ShoppingListToZedRequestClientInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @return \Spryker\Client\ShoppingList\PermissionUpdater\PermissionUpdaterInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createPermissionUpdaterInterfaceMock(): PermissionUpdaterInterface
    {
        return $this->getMockBuilder(PermissionUpdaterInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @return \Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListExpanderPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getShoppingListExpanderPluginMock(): ShoppingListExpanderPluginInterface
    {
        $shoppingListExpanderPluginMock = $this
            ->getMockBuilder(ShoppingListExpanderPluginInterface::class)
            ->onlyMethods(['expand'])
            ->getMock();

        $shoppingListExpanderPluginMock
            ->expects($this->once())
            ->method('expand')
            ->willReturnCallback(function (ShoppingListTransfer $shoppingListTransfer) {
                return $shoppingListTransfer;
            });

        return $shoppingListExpanderPluginMock;
    }
}
