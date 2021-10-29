<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationWishlist;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationClientInterface;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface;
use Spryker\Client\ProductConfigurationWishlist\Resolver\ProductConfiguratorRedirectResolver;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationWishlist
 * @group ProductConfiguratorRedirectResolverTest
 * Add your own group annotations below this line
 */
class ProductConfiguratorRedirectResolverTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_ID_WISHLIST_ITEM = 123456;

    /**
     * @see \Spryker\Client\ProductConfigurationWishlist\Resolver\ProductConfiguratorRedirectResolver::GLOSSARY_KEY_WISHLIST_PRODUCT_CONFIGURATION_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_WISHLIST_PRODUCT_CONFIGURATION_NOT_FOUND = 'product_configuration_wishlist.error.configuration_not_found';

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
    public function testResolveProductConfiguratorAccessTokenRedirectWithoutWishlistItemId(): void
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
    public function testResolveProductConfiguratorAccessTokenRedirectWithoutWishlistItemConfiguration(): void
    {
        // Arrange
        $productConfiguratorRequestTransfer = (new ProductConfiguratorRequestTransfer())
            ->setProductConfiguratorRequestData(
                (new ProductConfiguratorRequestDataTransfer())
                    ->setIdWishlistItem(static::FAKE_ID_WISHLIST_ITEM),
            );

        // Act
        $productConfiguratorRedirectTransfer = $this
            ->createProductConfiguratorRedirectResolverMock(new WishlistItemTransfer())
            ->resolveProductConfiguratorAccessTokenRedirect($productConfiguratorRequestTransfer);

        // Assert
        $this->assertFalse($productConfiguratorRedirectTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_WISHLIST_PRODUCT_CONFIGURATION_NOT_FOUND,
            $productConfiguratorRedirectTransfer->getMessages()->offsetGet(0)->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testResolveProductConfiguratorAccessTokenRedirectWithWishlistItemConfiguration(): void
    {
        // Arrange
        $productConfiguratorRequestTransfer = (new ProductConfiguratorRequestTransfer())
            ->setProductConfiguratorRequestData(
                (new ProductConfiguratorRequestDataTransfer())
                    ->setIdWishlistItem(static::FAKE_ID_WISHLIST_ITEM),
            );

        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setProductConfigurationInstance((new ProductConfigurationInstanceTransfer()));

        // Act
        $productConfiguratorRedirectTransfer = $this
            ->createProductConfiguratorRedirectResolverMock($wishlistItemTransfer)
            ->resolveProductConfiguratorAccessTokenRedirect($productConfiguratorRequestTransfer);

        // Assert
        $this->assertTrue($productConfiguratorRedirectTransfer->getIsSuccessful());
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer|null $wishlistItemTransfer
     *
     * @return \Spryker\Client\ProductConfigurationWishlist\Resolver\ProductConfiguratorRedirectResolver|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfiguratorRedirectResolverMock(
        ?WishlistItemTransfer $wishlistItemTransfer = null
    ): ProductConfiguratorRedirectResolver {
        return $this->getMockBuilder(ProductConfiguratorRedirectResolver::class)
            ->setConstructorArgs([
                $this->createProductConfigurationWishlistToWishlistClientInterfaceMock($wishlistItemTransfer),
                $this->createProductConfigurationWishlistToProductConfigurationClientInterfaceMock(),
            ])
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer|null $wishlistItemTransfer
     *
     * @return \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfigurationWishlistToWishlistClientInterfaceMock(
        ?WishlistItemTransfer $wishlistItemTransfer = null
    ): ProductConfigurationWishlistToWishlistClientInterface {
        $productConfigurationWishlistToWishlistClientInterfaceMock = $this
            ->getMockBuilder(ProductConfigurationWishlistToWishlistClientInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getWishlistItem', 'updateWishlistItem'])
            ->getMock();

        $wishlistItemResponseTransfer = (new WishlistItemResponseTransfer())
            ->setIsSuccess((bool)$wishlistItemTransfer)
            ->setWishlistItem($wishlistItemTransfer);

        $productConfigurationWishlistToWishlistClientInterfaceMock->expects($this->any())
            ->method('getWishlistItem')
            ->willReturn($wishlistItemResponseTransfer);

        return $productConfigurationWishlistToWishlistClientInterfaceMock;
    }

    /**
     * @return \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfigurationWishlistToProductConfigurationClientInterfaceMock(): ProductConfigurationWishlistToProductConfigurationClientInterface
    {
        $productConfigurationWishlistToProductConfigurationClientInterfaceMock = $this
            ->getMockBuilder(ProductConfigurationWishlistToProductConfigurationClientInterface::class)
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
