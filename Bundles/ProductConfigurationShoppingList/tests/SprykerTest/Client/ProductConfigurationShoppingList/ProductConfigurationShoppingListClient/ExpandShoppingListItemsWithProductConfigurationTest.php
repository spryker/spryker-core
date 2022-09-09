<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClient;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationStorageClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface;
use Spryker\Client\ProductConfigurationShoppingList\Expander\ProductConfigurationExpander;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationShoppingList
 * @group ProductConfigurationShoppingListClient
 * @group ExpandShoppingListItemsWithProductConfigurationTest
 * Add your own group annotations below this line
 */
class ExpandShoppingListItemsWithProductConfigurationTest extends Unit
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
    public function testExpandShoppingListItemsWithProductConfigurationExpandsItemWithCinfiguration(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())
            ->setQuantity(5)
            ->setIsComplete(true);

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->addItem((new ShoppingListItemTransfer())->setSku(static::FAKE_SKU));

        $productConfigurationExpanderMock = $this->createProductConfigurationExpanderMock($productConfigurationInstanceTransfer);

        // Act
        $shoppingListTransfer = $productConfigurationExpanderMock->expandShoppingListItemsWithProductConfiguration($shoppingListTransfer);

        // Assert
        $this->assertSame(
            $productConfigurationInstanceTransfer,
            $shoppingListTransfer->getItems()->offsetGet(0)->getProductConfigurationInstance(),
        );
        $this->assertSame(
            json_encode($productConfigurationInstanceTransfer->toArray()),
            $shoppingListTransfer->getItems()->offsetGet(0)->getProductConfigurationInstanceData(),
        );
    }

    /**
     * @return void
     */
    public function testExpandShoppingListItemsWithProductConfigurationExpandsItemWithoutCinfiguration(): void
    {
        // Arrange
        $shoppingListTransfer = (new ShoppingListTransfer())
            ->addItem((new ShoppingListItemTransfer())->setSku(static::FAKE_SKU));

        $productConfigurationExpanderMock = $this->createProductConfigurationExpanderMock();

        // Act
        $shoppingListTransfer = $productConfigurationExpanderMock->expandShoppingListItemsWithProductConfiguration($shoppingListTransfer);

        // Assert
        $this->assertNull($shoppingListTransfer->getItems()->offsetGet(0)->getProductConfigurationInstance());
        $this->assertNull($shoppingListTransfer->getItems()->offsetGet(0)->getProductConfigurationInstanceData());
    }

    /**
     * @return void
     */
    public function testExpandShoppingListItemsWithProductConfigurationExpandsItemWithoutItems(): void
    {
        // Arrange
        $shoppingListTransfer = new ShoppingListTransfer();
        $productConfigurationExpanderMock = $this->createProductConfigurationExpanderMock();

        // Act
        $shoppingListTransfer = $productConfigurationExpanderMock->expandShoppingListItemsWithProductConfiguration($shoppingListTransfer);

        // Assert
        $this->assertCount(0, $shoppingListTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testExpandShoppingListItemsWithProductConfigurationExpandsItemWithoutMandatorySkuField(): void
    {
        // Arrange
        $shoppingListTransfer = (new ShoppingListTransfer())
            ->addItem(new ShoppingListItemTransfer());

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->createProductConfigurationExpanderMock()->expandShoppingListItemsWithProductConfiguration($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null $productConfigurationInstanceTransfer
     *
     * @return \Spryker\Client\ProductConfigurationShoppingList\Expander\ProductConfigurationExpander|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfigurationExpanderMock(
        ?ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer = null
    ): ProductConfigurationExpander {
        return $this->getMockBuilder(ProductConfigurationExpander::class)
            ->setConstructorArgs([
                $this->createProductConfigurationStorageClientMock($productConfigurationInstanceTransfer),
                $this->createProductConfigurationShoppingListToUtilEncodingServiceInterfaceMock(),
            ])
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null $productConfigurationInstanceTransfer
     *
     * @return \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationStorageClientInterface
     */
    protected function createProductConfigurationStorageClientMock(
        ?ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer = null
    ): ProductConfigurationShoppingListToProductConfigurationStorageClientInterface {
        $productConfigurationStorageClientMock = $this
            ->getMockBuilder(ProductConfigurationShoppingListToProductConfigurationStorageClientInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'findProductConfigurationInstanceBySku',
            ])
            ->getMock();

        $productConfigurationStorageClientMock
            ->method('findProductConfigurationInstanceBySku')
            ->willReturn($productConfigurationInstanceTransfer);

        return $productConfigurationStorageClientMock;
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface
     */
    protected function createProductConfigurationShoppingListToUtilEncodingServiceInterfaceMock(): ProductConfigurationShoppingListToUtilEncodingServiceInterface
    {
        $productConfigurationShoppingListToUtilEncodingServiceInterfaceMock = $this
            ->getMockBuilder(ProductConfigurationShoppingListToUtilEncodingServiceInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['encodeJson'])
            ->getMock();

        $productConfigurationShoppingListToUtilEncodingServiceInterfaceMock
            ->method('encodeJson')
            ->willReturnCallback(function (array $value, ?int $options = null, ?int $depth = null) {
                return json_encode($value);
            });

        return $productConfigurationShoppingListToUtilEncodingServiceInterfaceMock;
    }
}
