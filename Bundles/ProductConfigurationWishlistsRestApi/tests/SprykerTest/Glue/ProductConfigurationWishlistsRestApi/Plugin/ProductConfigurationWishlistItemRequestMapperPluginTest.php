<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductConfigurationWishlistsRestApi\Plugin;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RestProductConfigurationPriceAttributesBuilder;
use Generated\Shared\DataBuilder\RestWishlistItemProductConfigurationInstanceAttributesBuilder;
use Generated\Shared\DataBuilder\RestWishlistItemsAttributesBuilder;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Spryker\Glue\ProductConfigurationWishlistsRestApi\Plugin\WishlistsRestApi\ProductConfigurationWishlistItemRequestMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductConfigurationWishlistsRestApi
 * @group Plugin
 * @group ProductConfigurationWishlistItemRequestMapperPluginTest
 * Add your own group annotations below this line
 */
class ProductConfigurationWishlistItemRequestMapperPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testMap(): void
    {
        // Arrange
        $productConfigurationWishlistItemRequestMapperPlugin = new ProductConfigurationWishlistItemRequestMapperPlugin();

        $restProductConfigurationPriceAttributesBuilder = (new RestProductConfigurationPriceAttributesBuilder())
            ->withCurrency()
            ->withVolumePrice();

        $restWishlistItemProductConfigurationInstanceAttributesTransfer = (new RestWishlistItemProductConfigurationInstanceAttributesBuilder())
            ->withPrice($restProductConfigurationPriceAttributesBuilder)
            ->build();

        $restWishlistItemsAttributesTransfer = (new RestWishlistItemsAttributesBuilder())
            ->build()
            ->setProductConfigurationInstance($restWishlistItemProductConfigurationInstanceAttributesTransfer);

        // Act
        $wishlistItemRequestTransfer = $productConfigurationWishlistItemRequestMapperPlugin->map(
            $restWishlistItemsAttributesTransfer,
            new WishlistItemRequestTransfer(),
        );

        // Assert
        $this->assertInstanceOf(ProductConfigurationInstanceTransfer::class, $wishlistItemRequestTransfer->getProductConfigurationInstance());
        $this->assertEquals($restWishlistItemProductConfigurationInstanceAttributesTransfer->getConfiguratorKey(), $wishlistItemRequestTransfer->getProductConfigurationInstance()->getConfiguratorKey());
        $this->assertEquals($restWishlistItemProductConfigurationInstanceAttributesTransfer->getIsComplete(), $wishlistItemRequestTransfer->getProductConfigurationInstance()->getIsComplete());
        $this->assertInstanceOf(ArrayObject::class, $wishlistItemRequestTransfer->getProductConfigurationInstance()->getPrices());
        $this->assertNotEquals(0, $wishlistItemRequestTransfer->getProductConfigurationInstance()->getPrices()->count());
    }
}
