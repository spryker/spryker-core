<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductConfigurationsRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartItemRequestBuilder;
use Generated\Shared\DataBuilder\RestCartItemsAttributesBuilder;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\ProductConfigurationsRestApi\Plugin\CartsRestApi\ProductConfigurationCartItemExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductConfigurationsRestApi
 * @group Plugin
 * @group ProductConfigurationCartItemExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductConfigurationCartItemExpanderPluginTest extends Unit
{
    protected const TEST_SKU = 'test-sku';

    /**
     * @return void
     */
    public function testExpandWillExpandCartItemRequestTransferWithProductConfigurationData(): void
    {
        // Arrange
        $restCartItemsAttributesTransfer = (new RestCartItemsAttributesBuilder([
            RestCartItemsAttributesTransfer::SKU => static::TEST_SKU,
            RestCartItemsAttributesTransfer::GROUP_KEY => static::TEST_SKU,
        ]))->withProductConfigurationInstance()
            ->build();
        $cartItemRequestTransfer = (new CartItemRequestBuilder([
            CartItemRequestTransfer::SKU => static::TEST_SKU,
            CartItemRequestTransfer::GROUP_KEY => static::TEST_SKU,
        ]))->build();

        $productConfigurationCartItemExpanderPlugin = new ProductConfigurationCartItemExpanderPlugin();

        // Act
        $cartItemRequestTransfer = $productConfigurationCartItemExpanderPlugin->expand(
            $cartItemRequestTransfer,
            $restCartItemsAttributesTransfer
        );

        // Assert
        $this->assertNotNull($cartItemRequestTransfer->getProductConfigurationInstance());
        $this->assertSame(
            $restCartItemsAttributesTransfer->getProductConfigurationInstance()->getConfiguration(),
            $cartItemRequestTransfer->getProductConfigurationInstance()->getConfiguration()
        );
        $this->assertSame(
            $restCartItemsAttributesTransfer->getProductConfigurationInstance()->getConfiguratorKey(),
            $cartItemRequestTransfer->getProductConfigurationInstance()->getConfiguratorKey()
        );
        $this->assertSame(
            $restCartItemsAttributesTransfer->getProductConfigurationInstance()->getDisplayData(),
            $cartItemRequestTransfer->getProductConfigurationInstance()->getDisplayData()
        );
    }
}
