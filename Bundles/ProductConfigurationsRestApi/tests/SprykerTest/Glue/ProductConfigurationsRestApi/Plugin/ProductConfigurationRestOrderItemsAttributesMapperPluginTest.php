<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductConfigurationsRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\RestOrderItemsAttributesBuilder;
use Spryker\Glue\ProductConfigurationsRestApi\Plugin\OrdersRestApi\ProductConfigurationRestOrderItemsAttributesMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductConfigurationsRestApi
 * @group Plugin
 * @group ProductConfigurationRestOrderItemsAttributesMapperPluginTest
 * Add your own group annotations below this line
 */
class ProductConfigurationRestOrderItemsAttributesMapperPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testMapItemTransferToRestOrderItemsAttributesTransferWillMapProductConfiguration(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder())
            ->withSalesOrderItemConfiguration()
            ->build();

        $restOrderItemsAttributesTransfer = (new RestOrderItemsAttributesBuilder())->build();

        $productConfigurationRestOrderItemsAttributesMapperPlugin = new ProductConfigurationRestOrderItemsAttributesMapperPlugin();

        // Act
        $restOrderItemsAttributesTransfer = $productConfigurationRestOrderItemsAttributesMapperPlugin->mapItemTransferToRestOrderItemsAttributesTransfer(
            $itemTransfer,
            $restOrderItemsAttributesTransfer
        );

        // Assert
        $this->assertNotNull($restOrderItemsAttributesTransfer->getSalesOrderItemConfiguration());

        $productConfigurationInstanceTransfer = $itemTransfer->getSalesOrderItemConfiguration();
        $restSalesOrderItemProductConfigurationInstanceAttributesTransfer = $restOrderItemsAttributesTransfer->getSalesOrderItemConfiguration();
        $this->assertSame($restSalesOrderItemProductConfigurationInstanceAttributesTransfer->getConfiguration(), $productConfigurationInstanceTransfer->getConfiguration());
        $this->assertSame($restSalesOrderItemProductConfigurationInstanceAttributesTransfer->getConfiguratorKey(), $productConfigurationInstanceTransfer->getConfiguratorKey());
        $this->assertSame($restSalesOrderItemProductConfigurationInstanceAttributesTransfer->getDisplayData(), $productConfigurationInstanceTransfer->getDisplayData());
    }

    /**
     * @return void
     */
    public function testMapItemTransferToRestOrderItemsAttributesTransferWillNotMapProductConfigurationWhenItIsAbsentInItem(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder())->build();
        $itemTransfer->setSalesOrderItemConfiguration(null);
        $restOrderItemsAttributesTransfer = (new RestOrderItemsAttributesBuilder())->build();

        $productConfigurationRestOrderItemsAttributesMapperPlugin = new ProductConfigurationRestOrderItemsAttributesMapperPlugin();

        // Act
        $restOrderItemsAttributesTransfer = $productConfigurationRestOrderItemsAttributesMapperPlugin->mapItemTransferToRestOrderItemsAttributesTransfer(
            $itemTransfer,
            $restOrderItemsAttributesTransfer
        );

        // Assert
        $this->assertNull($restOrderItemsAttributesTransfer->getSalesOrderItemConfiguration());
    }
}
