<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductConfigurationsRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\RestItemsAttributesBuilder;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\ProductConfigurationsRestApi\Plugin\CartsRestApi\ProductConfigurationRestCartItemsAttributesMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductConfigurationsRestApi
 * @group Plugin
 * @group ProductConfigurationRestCartItemsAttributesMapperPluginTest
 * Add your own group annotations below this line
 */
class ProductConfigurationRestCartItemsAttributesMapperPluginTest extends Unit
{
    protected const TEST_SKU = 'test-sku';
    protected const TEST_LOCALE = 'en_US';

    /**
     * @return void
     */
    public function testExpandWillExpandCartItemRequestTransferWithProductConfigurationData(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder([
            CartItemRequestTransfer::SKU => static::TEST_SKU,
            CartItemRequestTransfer::GROUP_KEY => static::TEST_SKU,
        ]))->build();
        $restItemsAttributesTransfer = (new RestItemsAttributesBuilder([
            RestCartItemsAttributesTransfer::SKU => static::TEST_SKU,
            RestCartItemsAttributesTransfer::GROUP_KEY => static::TEST_SKU,
        ]))->withProductConfigurationInstance()
            ->build();

        $productConfigurationCartItemExpanderPlugin = new ProductConfigurationRestCartItemsAttributesMapperPlugin();

        // Act
        $itemTransfer = $productConfigurationCartItemExpanderPlugin->mapItemTransferToRestItemsAttributesTransfer(
            $itemTransfer,
            $restItemsAttributesTransfer,
            static::TEST_LOCALE
        );

        // Assert
        $this->assertNotNull($restItemsAttributesTransfer->getProductConfigurationInstance());
        $this->assertSame(
            $itemTransfer->getProductConfigurationInstance()->getConfiguration(),
            $restItemsAttributesTransfer->getProductConfigurationInstance()->getConfiguration()
        );
        $this->assertSame(
            $itemTransfer->getProductConfigurationInstance()->getConfiguratorKey(),
            $restItemsAttributesTransfer->getProductConfigurationInstance()->getConfiguratorKey()
        );
        $this->assertSame(
            $itemTransfer->getProductConfigurationInstance()->getDisplayData(),
            $restItemsAttributesTransfer->getProductConfigurationInstance()->getDisplayData()
        );
    }
}
