<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductConfigurationShoppingListsRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ShoppingListItemBuilder;
use Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer;
use Spryker\Glue\ProductConfigurationShoppingListsRestApi\Plugin\ShoppingListsRestApi\ProductConfigurationRestShoppingListItemsAttributesMapperPlugin;
use Spryker\Glue\ProductConfigurationShoppingListsRestApi\ProductConfigurationShoppingListsRestApiDependencyProvider;
use Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface;
use SprykerTest\Glue\ProductConfigurationShoppingListsRestApi\ProductConfigurationShoppingListsRestApiPluginTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductConfigurationShoppingListsRestApi
 * @group Plugin
 * @group ProductConfigurationRestShoppingListItemsAttributesMapperPluginTest
 * Add your own group annotations below this line
 */
class ProductConfigurationRestShoppingListItemsAttributesMapperPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\ProductConfigurationShoppingListsRestApi\ProductConfigurationShoppingListsRestApiPluginTester
     */
    protected ProductConfigurationShoppingListsRestApiPluginTester $tester;

    /**
     * @return void
     */
    public function testMapConfigurationToAttributes(): void
    {
        // Arrange
        $shoppingListItemTransfer = (new ShoppingListItemBuilder())
            ->withProductConfigurationInstance()
            ->build();

        // Act
        $restShoppingListItemsAttributesTransfer = (new ProductConfigurationRestShoppingListItemsAttributesMapperPlugin())->map(
            $shoppingListItemTransfer,
            new RestShoppingListItemsAttributesTransfer(),
        );

        $this->assertInstanceOf(RestShoppingListItemsAttributesTransfer::class, $restShoppingListItemsAttributesTransfer);
        $this->assertSame(
            $shoppingListItemTransfer->getProductConfigurationInstance()->getConfiguratorKey(),
            $restShoppingListItemsAttributesTransfer->getProductConfigurationInstance()->getConfiguratorKey(),
        );
    }

    /**
     * @return void
     */
    public function testMapExecuteRestProductConfigurationPriceMapperPluginStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            ProductConfigurationShoppingListsRestApiDependencyProvider::PLUGINS_REST_PRODUCT_CONFIGURATION_PRICE_MAPPER,
            [$this->getRestProductConfigurationPriceMapperPluginMock()],
        );

        $shoppingListItemTransfer = (new ShoppingListItemBuilder())
            ->withProductConfigurationInstance()
            ->build();

        // Act
        (new ProductConfigurationRestShoppingListItemsAttributesMapperPlugin())->map(
            $shoppingListItemTransfer,
            new RestShoppingListItemsAttributesTransfer(),
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface
     */
    protected function getRestProductConfigurationPriceMapperPluginMock(): RestProductConfigurationPriceMapperPluginInterface
    {
        $restProductConfigurationPriceMapperPluginMock = $this
            ->getMockBuilder(RestProductConfigurationPriceMapperPluginInterface::class)
            ->getMock();

        $restProductConfigurationPriceMapperPluginMock
            ->expects($this->once())
            ->method('map');

        return $restProductConfigurationPriceMapperPluginMock;
    }
}
