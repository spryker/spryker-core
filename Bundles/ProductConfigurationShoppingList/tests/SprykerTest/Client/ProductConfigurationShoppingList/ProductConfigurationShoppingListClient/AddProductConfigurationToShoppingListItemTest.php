<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClient;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationStorageClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListFactory;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationShoppingList
 * @group ProductConfigurationShoppingListClient
 * @group AddProductConfigurationToShoppingListItemTest
 * Add your own group annotations below this line
 */
class AddProductConfigurationToShoppingListItemTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SKU = 'FAKE_SKU';

    /**
     * @var \SprykerTest\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClientTester
     */
    protected ProductConfigurationShoppingListClientTester $tester;

    /**
     * @return void
     */
    public function testAddProductConfigurationToShoppingListItemAddsProductConfigurationToItem(): void
    {
        // Arrange
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setSku(static::FAKE_SKU);

        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())
            ->setQuantity(5)
            ->setIsComplete(true);

        $productConfigurationInstanceCollectionTransfer = (new ProductConfigurationInstanceCollectionTransfer())->setProductConfigurationInstances(
            new ArrayObject([
                static::FAKE_SKU => $productConfigurationInstanceTransfer,
            ]),
        );

        $productConfigurationShoppingListFactory = $this->createProductConfigurationShoppingListFactoryMock($productConfigurationInstanceCollectionTransfer);
        $productConfigurationShoppingListClient = $this->tester->getClient()->setFactory($productConfigurationShoppingListFactory);

        // Act
        $shoppingListItemTransfer = $productConfigurationShoppingListClient->addProductConfigurationToShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertEquals($productConfigurationInstanceTransfer, $shoppingListItemTransfer->getProductConfigurationInstance());
    }

    /**
     * @return void
     */
    public function testAddProductConfigurationToShoppingListItemAddsItemWithoutProductConfiguration(): void
    {
        // Arrange
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setSku(static::FAKE_SKU);

        $productConfigurationShoppingListFactory = $this->createProductConfigurationShoppingListFactoryMock(new ProductConfigurationInstanceCollectionTransfer());
        $productConfigurationShoppingListClient = $this->tester->getClient()->setFactory($productConfigurationShoppingListFactory);

        // Act
        $shoppingListItemTransfer = $productConfigurationShoppingListClient->addProductConfigurationToShoppingListItem($shoppingListItemTransfer);

        // Assert
        $this->assertNull($shoppingListItemTransfer->getProductConfigurationInstance());
    }

    /**
     * @return void
     */
    public function testAddProductConfigurationToShoppingListItemThrowsExceptionWhenItemWithoutSku(): void
    {
        // Arrange
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setSku(null);

        $productConfigurationShoppingListFactory = $this->createProductConfigurationShoppingListFactoryMock(new ProductConfigurationInstanceCollectionTransfer());
        $productConfigurationShoppingListClient = $this->tester->getClient()->setFactory($productConfigurationShoppingListFactory);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $productConfigurationShoppingListClient->addProductConfigurationToShoppingListItem($shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceCollectionTransfer $productConfigurationInstanceCollectionTransfer
     *
     * @return \Spryker\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfigurationShoppingListFactoryMock(
        ProductConfigurationInstanceCollectionTransfer $productConfigurationInstanceCollectionTransfer
    ): ProductConfigurationShoppingListFactory {
        $productConfigurationShoppingListMock = $this->getMockBuilder(ProductConfigurationShoppingListFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getProductConfigurationStorageClient'])
            ->getMock();

        $productConfigurationStorageClient = $this->getMockBuilder(ProductConfigurationShoppingListToProductConfigurationStorageClientInterface::class)
            ->onlyMethods(['getProductConfigurationInstanceCollection'])
            ->getMockForAbstractClass();

        $productConfigurationStorageClient
            ->method('getProductConfigurationInstanceCollection')
            ->willReturn($productConfigurationInstanceCollectionTransfer);

        $productConfigurationShoppingListMock
            ->method('getProductConfigurationStorageClient')
            ->willReturn($productConfigurationStorageClient);

        return $productConfigurationShoppingListMock;
    }
}
