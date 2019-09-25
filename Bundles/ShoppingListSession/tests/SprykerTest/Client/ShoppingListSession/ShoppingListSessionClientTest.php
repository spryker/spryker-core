<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingListSession;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Client\Session\SessionClient;
use Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToSessionClientBridge;
use Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToSessionClientInterface;
use Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToShoppingListClientBridgeInterface;
use Spryker\Client\ShoppingListSession\ShoppingListSessionClientInterface;
use Spryker\Client\ShoppingListSession\ShoppingListSessionDependencyProvider;
use SprykerTest\Client\ShoppingListSession\Fixtures\Plugin\CollectionOutdatedPluginReturnsFalse;
use SprykerTest\Client\ShoppingListSession\Fixtures\Plugin\CollectionOutdatedPluginReturnsTrue;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ShoppingListSession
 * @group ShoppingListSessionClientTest
 * Add your own group annotations below this line
 */
class ShoppingListSessionClientTest extends Unit
{
    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    protected $sessionClient;

    /**
     * @var \SprykerTest\Client\ShoppingListSession\ShoppingListClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->tester->setDependency(ShoppingListSessionDependencyProvider::SHOPPING_LIST_SESSION_SESSION_CLIENT, $this->getShoppingListSessionToSessionClientBridge());
        $this->tester->setDependency(
            ShoppingListSessionDependencyProvider::SHOPPING_LIST_SESSION_COLLECTION_OUTDATED_PLUGINS,
            function () {
                return [new CollectionOutdatedPluginReturnsFalse()];
            }
        );
    }

    /**
     * @return void
     */
    public function testGetCustomerShoppingListCollectionFirstTimeReturnsCollectionFromShoppingListClient(): void
    {
        //Assign
        $shoppingListCollectionTransfer = (new ShoppingListCollectionTransfer())
            ->addShoppingList(new ShoppingListTransfer())
            ->addShoppingList(new ShoppingListTransfer());

        //Act
        $shoppingListClientMock = $this->getShoppingListClientMock($shoppingListCollectionTransfer);
        $this->tester->setDependency(ShoppingListSessionDependencyProvider::SHOPPING_LIST_SESSION_SHOPPING_LIST_CLIENT, $shoppingListClientMock);
        $customerShoppingListCollection = $this->getShoppingListSessionClient()->getCustomerShoppingListCollection();

        //Assert
        $this->assertEquals($shoppingListCollectionTransfer, $customerShoppingListCollection);
    }

    /**
     * @return void
     */
    public function testGetCustomerShoppingListCollectionShouldGetCollectionFromSessionClientWhenNoOfCollectionOutdatedPluginsReturnsTrue(): void
    {
        //Assign
        $shoppingListCollectionTransferFirst = (new ShoppingListCollectionTransfer())
            ->addShoppingList(new ShoppingListTransfer())
            ->addShoppingList(new ShoppingListTransfer());

        $shoppingListCollectionTransferSecond = (new ShoppingListCollectionTransfer())
            ->addShoppingList(new ShoppingListTransfer());

        //Act
        $this->tester->setDependency(
            ShoppingListSessionDependencyProvider::SHOPPING_LIST_SESSION_SHOPPING_LIST_CLIENT,
            $this->getShoppingListClientMock($shoppingListCollectionTransferFirst)
        );
        $customerShoppingListCollectionFirstResult = $this->getShoppingListSessionClient()->getCustomerShoppingListCollection();

        $this->tester->setDependency(
            ShoppingListSessionDependencyProvider::SHOPPING_LIST_SESSION_SHOPPING_LIST_CLIENT,
            $this->getShoppingListClientMock($shoppingListCollectionTransferSecond)
        );
        $customerShoppingListCollectionSecondResult = $this->getShoppingListSessionClient()->getCustomerShoppingListCollection();

        //Assert
        $this->assertNotEquals($shoppingListCollectionTransferFirst, $shoppingListCollectionTransferSecond);
        $this->assertEquals($shoppingListCollectionTransferFirst, $customerShoppingListCollectionFirstResult);
        $this->assertEquals($shoppingListCollectionTransferFirst, $customerShoppingListCollectionSecondResult);
        $this->assertNotEquals($shoppingListCollectionTransferFirst, $shoppingListCollectionTransferSecond);
    }

    /**
     * @return void
     */
    public function testGetCustomerShoppingListCollectionShouldGetCollectionFromShoppingListClientWhenOneOfCollectionOutdatedPluginsReturnsTrue(): void
    {
        //Assign
        $shoppingListCollectionTransferFirst = (new ShoppingListCollectionTransfer())
            ->addShoppingList(new ShoppingListTransfer())
            ->addShoppingList(new ShoppingListTransfer());

        $shoppingListCollectionTransferSecond = (new ShoppingListCollectionTransfer())
            ->addShoppingList(new ShoppingListTransfer());

        //Act
        $this->tester->setDependency(
            ShoppingListSessionDependencyProvider::SHOPPING_LIST_SESSION_SHOPPING_LIST_CLIENT,
            $this->getShoppingListClientMock($shoppingListCollectionTransferFirst)
        );
        $customerShoppingListCollectionFirstResult = $this->getShoppingListSessionClient()->getCustomerShoppingListCollection();

        $this->tester->setDependency(
            ShoppingListSessionDependencyProvider::SHOPPING_LIST_SESSION_COLLECTION_OUTDATED_PLUGINS,
            function () {
                return [new CollectionOutdatedPluginReturnsTrue()];
            }
        );
        $shoppingListClientMock = $this->getShoppingListClientMock($shoppingListCollectionTransferSecond);
        $this->tester->setDependency(ShoppingListSessionDependencyProvider::SHOPPING_LIST_SESSION_SHOPPING_LIST_CLIENT, $shoppingListClientMock);
        $customerShoppingListCollectionSecondResult = $this->getShoppingListSessionClient()->getCustomerShoppingListCollection();

        //Assert
        $this->assertNotEquals($shoppingListCollectionTransferFirst, $shoppingListCollectionTransferSecond);
        $this->assertEquals($shoppingListCollectionTransferFirst, $customerShoppingListCollectionFirstResult);
        $this->assertEquals($shoppingListCollectionTransferSecond, $customerShoppingListCollectionSecondResult);
        $this->assertNotEquals($shoppingListCollectionTransferFirst, $shoppingListCollectionTransferSecond);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     *
     * @return \Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToShoppingListClientBridgeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getShoppingListClientMock(ShoppingListCollectionTransfer $shoppingListCollectionTransfer): ShoppingListSessionToShoppingListClientBridgeInterface
    {
        $shoppingListClientMock = $this->getMockBuilder(ShoppingListSessionToShoppingListClientBridgeInterface::class)
            ->setMethods(['getCustomerShoppingListCollection', 'updateCustomerPermission'])
            ->disableOriginalConstructor()
            ->getMock();

        $shoppingListClientMock->expects($this->any())
            ->method('getCustomerShoppingListCollection')
            ->will($this->returnValue($shoppingListCollectionTransfer));

        return $shoppingListClientMock;
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\Dependency\Client\ShoppingListSessionToSessionClientInterface
     */
    protected function getShoppingListSessionToSessionClientBridge(): ShoppingListSessionToSessionClientInterface
    {
        $this->sessionClient = new SessionClient();
        $this->sessionClient->setContainer(new Session(new MockArraySessionStorage()));

        return new ShoppingListSessionToSessionClientBridge($this->sessionClient);
    }

    /**
     * @return \Spryker\Client\ShoppingListSession\ShoppingListSessionClientInterface
     */
    protected function getShoppingListSessionClient(): ShoppingListSessionClientInterface
    {
        return $this->tester->getLocator()->shoppingListSession()->client();
    }
}
