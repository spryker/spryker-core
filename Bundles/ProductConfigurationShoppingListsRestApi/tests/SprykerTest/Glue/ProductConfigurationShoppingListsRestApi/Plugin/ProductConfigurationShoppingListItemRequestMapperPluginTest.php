<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductConfigurationShoppingListsRestApi\Plugin;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RestCurrencyBuilder;
use Generated\Shared\DataBuilder\RestProductConfigurationPriceAttributesBuilder;
use Generated\Shared\DataBuilder\RestProductPriceVolumesAttributesBuilder;
use Generated\Shared\DataBuilder\RestShoppingListItemProductConfigurationInstanceAttributesBuilder;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer;
use Generated\Shared\Transfer\RestShoppingListItemProductConfigurationInstanceAttributesTransfer;
use Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Glue\ProductConfigurationShoppingListsRestApi\Plugin\ShoppingListsRestApi\ProductConfigurationShoppingListItemRequestMapperPlugin;
use Spryker\Glue\ProductConfigurationShoppingListsRestApi\ProductConfigurationShoppingListsRestApiDependencyProvider;
use Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Glue\ProductConfigurationShoppingListsRestApi\ProductConfigurationShoppingListsRestApiPluginTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductConfigurationShoppingListsRestApi
 * @group Plugin
 * @group ProductConfigurationShoppingListItemRequestMapperPluginTest
 * Add your own group annotations below this line
 */
class ProductConfigurationShoppingListItemRequestMapperPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\ProductConfigurationShoppingListsRestApi\ProductConfigurationShoppingListsRestApiPluginTester
     */
    protected ProductConfigurationShoppingListsRestApiPluginTester $tester;

    /**
     * @return void
     */
    public function testMapProductConfigurationToRequest(): void
    {
        // Arrange
        $restShoppingListItemsAttributesTransfer = $this->buildRestShoppingListItemsAttributesTransfer();

        $shoppingListItemRequestTransfer = (new ShoppingListItemRequestTransfer())
            ->setShoppingListItem(new ShoppingListItemTransfer());

        // Act
        $shoppingListItemRequestTransfer = (new ProductConfigurationShoppingListItemRequestMapperPlugin())->map(
            $restShoppingListItemsAttributesTransfer,
            $shoppingListItemRequestTransfer,
        );

        $shoppingListItemTransfer = $shoppingListItemRequestTransfer->getShoppingListItemOrFail();

        // Assert
        $this->assertInstanceOf(ProductConfigurationInstanceTransfer::class, $shoppingListItemTransfer->getProductConfigurationInstance());
        $this->assertInstanceOf(ArrayObject::class, $shoppingListItemTransfer->getProductConfigurationInstance()->getPrices());
        $this->assertNotEquals(0, $shoppingListItemTransfer->getProductConfigurationInstance()->getPrices()->count());
        $this->assertEquals(
            $restShoppingListItemsAttributesTransfer->getProductConfigurationInstance()->getConfiguratorKey(),
            $shoppingListItemTransfer->getProductConfigurationInstance()->getConfiguratorKey(),
        );
        $this->assertEquals(
            $restShoppingListItemsAttributesTransfer->getProductConfigurationInstance()->getIsComplete(),
            $shoppingListItemTransfer->getProductConfigurationInstance()->getIsComplete(),
        );
    }

    /**
     * @return void
     */
    public function testMapWithoutRequiredItem(): void
    {
        // Arrange
        $restShoppingListItemsAttributesTransfer = $this->buildRestShoppingListItemsAttributesTransfer();

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        (new ProductConfigurationShoppingListItemRequestMapperPlugin())
            ->map($restShoppingListItemsAttributesTransfer, new ShoppingListItemRequestTransfer());
    }

    /**
     * @return void
     */
    public function testMapExecuteProductConfigurationPriceMapperPluginStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            ProductConfigurationShoppingListsRestApiDependencyProvider::PLUGINS_PRODUCT_CONFIGURATION_PRICE_MAPPER,
            [$this->getProductConfigurationPriceMapperPluginMock()],
        );
        $restShoppingListItemsAttributesTransfer = $this->buildRestShoppingListItemsAttributesTransfer();

        $shoppingListItemRequestTransfer = (new ShoppingListItemRequestTransfer())
            ->setShoppingListItem(new ShoppingListItemTransfer());

        // Act
        (new ProductConfigurationShoppingListItemRequestMapperPlugin())->map(
            $restShoppingListItemsAttributesTransfer,
            $shoppingListItemRequestTransfer,
        );
    }

    /**
     * @return \Generated\Shared\Transfer\RestShoppingListItemsAttributesTransfer
     */
    protected function buildRestShoppingListItemsAttributesTransfer(): RestShoppingListItemsAttributesTransfer
    {
        $price = (new RestProductConfigurationPriceAttributesBuilder([
            RestProductConfigurationPriceAttributesTransfer::CURRENCY => (new RestCurrencyBuilder())->build(),
            RestProductConfigurationPriceAttributesTransfer::VOLUME_PRICES => [(new RestProductPriceVolumesAttributesBuilder())->build()],
        ]))
            ->build();

        $restProductConfigurationInstance = (new RestShoppingListItemProductConfigurationInstanceAttributesBuilder(
            [
                RestShoppingListItemProductConfigurationInstanceAttributesTransfer::PRICES => new ArrayObject([
                    $price,
                ]),
            ],
        ))->build();

        $restProductConfigurationInstance->setPrices(new ArrayObject([$price]));

        return (new RestShoppingListItemsAttributesTransfer())
            ->setProductConfigurationInstance($restProductConfigurationInstance);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface
     */
    protected function getProductConfigurationPriceMapperPluginMock(): ProductConfigurationPriceMapperPluginInterface
    {
        $productConfigurationPriceMapperPluginMock = $this
            ->getMockBuilder(ProductConfigurationPriceMapperPluginInterface::class)
            ->getMock();

        $productConfigurationPriceMapperPluginMock
            ->expects($this->once())
            ->method('map');

        return $productConfigurationPriceMapperPluginMock;
    }
}
