<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductConfigurationWishlistsRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\WishlistItemBuilder;
use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Spryker\Glue\ProductConfigurationWishlistsRestApi\Plugin\WishlistsRestApi\ProductConfigurationRestWishlistItemsAttributesMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductConfigurationWishlistsRestApi
 * @group Plugin
 * @group ProductConfigurationRestWishlistItemsAttributesMapperPluginTest
 * Add your own group annotations below this line
 */
class ProductConfigurationRestWishlistItemsAttributesMapperPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testMap(): void
    {
        // Arrange
        $productConfigurationRestWishlistItemsAttributesMapperPlugin = new ProductConfigurationRestWishlistItemsAttributesMapperPlugin();
        $wishlistItemTransfer = (new WishlistItemBuilder())
            ->withProductConfigurationInstance()
            ->build();
        // Act
        $restWishlistItemsAttributesTransfer = $productConfigurationRestWishlistItemsAttributesMapperPlugin->map(
            $wishlistItemTransfer,
            new RestWishlistItemsAttributesTransfer(),
        );

        $this->assertInstanceOf(RestWishlistItemsAttributesTransfer::class, $restWishlistItemsAttributesTransfer);
        $this->assertSame(
            $wishlistItemTransfer->getProductConfigurationInstance()->getConfiguratorKey(),
            $restWishlistItemsAttributesTransfer->getProductConfigurationInstance()->getConfiguratorKey(),
        );
    }
}
