<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClient;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToShoppingListClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Resolver\ProductConfiguratorRedirectResolver;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationShoppingList
 * @group ProductConfigurationShoppingListClient
 * @group ProductConfiguratorRedirectResolverTest
 * Add your own group annotations below this line
 */
class ProductConfiguratorRedirectResolverTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SHOPPING_LIST_ITEM_UUID = 'FAKE_SHOPPING_LIST_ITEM_UUID';

    /**
     * @see \Spryker\Client\ProductConfigurationShoppingList\Resolver\ProductConfiguratorRedirectResolver::GLOSSARY_KEY_SHOPPING_LIST_PRODUCT_CONFIGURATION_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_SHOPPING_LIST_PRODUCT_CONFIGURATION_NOT_FOUND = 'product_configuration_shopping_list.error.configuration_not_found';

    /**
     * @return void
     */
    public function testResolveProductConfiguratorAccessTokenRedirectWithoutProductConfiguratorRequestData(): void
    {
        // Arrange
        $productConfiguratorRequestTransfer = new ProductConfiguratorRequestTransfer();

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->createProductConfiguratorRedirectResolverMock()
            ->resolveProductConfiguratorAccessTokenRedirect($productConfiguratorRequestTransfer);
    }

    /**
     * @return void
     */
    public function testResolveProductConfiguratorAccessTokenRedirectWithoutShoppingListItemUuid(): void
    {
        // Arrange
        $productConfiguratorRequestTransfer = (new ProductConfiguratorRequestTransfer())
            ->setProductConfiguratorRequestData(new ProductConfiguratorRequestDataTransfer());

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->createProductConfiguratorRedirectResolverMock()
            ->resolveProductConfiguratorAccessTokenRedirect($productConfiguratorRequestTransfer);
    }

    /**
     * @return void
     */
    public function testResolveProductConfiguratorAccessTokenRedirectWithoutShoppingListItemConfiguration(): void
    {
        // Arrange
        $productConfiguratorRequestTransfer = (new ProductConfiguratorRequestTransfer())
            ->setProductConfiguratorRequestData(
                (new ProductConfiguratorRequestDataTransfer())
                    ->setShoppingListItemUuid(static::FAKE_SHOPPING_LIST_ITEM_UUID),
            );

        // Act
        $productConfiguratorRedirectTransfer = $this
            ->createProductConfiguratorRedirectResolverMock(new ShoppingListItemTransfer())
            ->resolveProductConfiguratorAccessTokenRedirect($productConfiguratorRequestTransfer);

        // Assert
        $this->assertFalse($productConfiguratorRedirectTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_SHOPPING_LIST_PRODUCT_CONFIGURATION_NOT_FOUND,
            $productConfiguratorRedirectTransfer->getMessages()->offsetGet(0)->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testResolveProductConfiguratorAccessTokenRedirectWithShoppingListItemConfiguration(): void
    {
        // Arrange
        $productConfiguratorRequestTransfer = (new ProductConfiguratorRequestTransfer())
            ->setProductConfiguratorRequestData(
                (new ProductConfiguratorRequestDataTransfer())
                    ->setShoppingListItemUuid(static::FAKE_SHOPPING_LIST_ITEM_UUID),
            );

        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setQuantity(1)
            ->setProductConfigurationInstance((new ProductConfigurationInstanceTransfer()));

        // Act
        $productConfiguratorRedirectTransfer = $this
            ->createProductConfiguratorRedirectResolverMock($shoppingListItemTransfer)
            ->resolveProductConfiguratorAccessTokenRedirect($productConfiguratorRequestTransfer);

        // Assert
        $this->assertTrue($productConfiguratorRedirectTransfer->getIsSuccessful());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer|null $shoppingListItemTransfer
     *
     * @return \Spryker\Client\ProductConfigurationShoppingList\Resolver\ProductConfiguratorRedirectResolver|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfiguratorRedirectResolverMock(
        ?ShoppingListItemTransfer $shoppingListItemTransfer = null
    ): ProductConfiguratorRedirectResolver {
        return $this->getMockBuilder(ProductConfiguratorRedirectResolver::class)
            ->setConstructorArgs([
                $this->createProductConfigurationShoppingListToShoppingListClientInterfaceMock($shoppingListItemTransfer),
                $this->createProductConfigurationShoppingListToProductConfigurationClientInterfaceMock(),
            ])
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer|null $shoppingListItemTransfer
     *
     * @return \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToShoppingListClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfigurationShoppingListToShoppingListClientInterfaceMock(
        ?ShoppingListItemTransfer $shoppingListItemTransfer = null
    ): ProductConfigurationShoppingListToShoppingListClientInterface {
        $productConfigurationWishlistToShoppingListClientInterfaceMock = $this
            ->getMockBuilder(ProductConfigurationShoppingListToShoppingListClientInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getShoppingListItemCollectionByUuid', 'updateShoppingListItemByUuid'])
            ->getMock();

        $shoppingListItemCollectionTransfer = new ShoppingListItemCollectionTransfer();

        if ($shoppingListItemTransfer) {
            $shoppingListItemCollectionTransfer->addItem($shoppingListItemTransfer);
        }

        $productConfigurationWishlistToShoppingListClientInterfaceMock->expects($this->any())
            ->method('getShoppingListItemCollectionByUuid')
            ->willReturn($shoppingListItemCollectionTransfer);

        return $productConfigurationWishlistToShoppingListClientInterfaceMock;
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfigurationShoppingListToProductConfigurationClientInterfaceMock(): ProductConfigurationShoppingListToProductConfigurationClientInterface
    {
        $productConfigurationWishlistToProductConfigurationClientInterfaceMock = $this
            ->getMockBuilder(ProductConfigurationShoppingListToProductConfigurationClientInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'validateProductConfiguratorCheckSumResponse',
                'mapProductConfiguratorCheckSumResponse',
                'sendProductConfiguratorAccessTokenRequest',
            ])
            ->getMock();

        $productConfigurationWishlistToProductConfigurationClientInterfaceMock->expects($this->any())
            ->method('sendProductConfiguratorAccessTokenRequest')
            ->willReturn((new ProductConfiguratorRedirectTransfer())->setIsSuccessful(true));

        return $productConfigurationWishlistToProductConfigurationClientInterfaceMock;
    }
}
