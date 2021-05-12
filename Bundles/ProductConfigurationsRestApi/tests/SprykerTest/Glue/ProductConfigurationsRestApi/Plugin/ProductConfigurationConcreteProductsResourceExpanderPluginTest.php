<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductConfigurationsRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ConcreteProductsRestAttributesBuilder;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestProductConfigurationInstanceAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Plugin\ProductsRestApi\ProductConfigurationConcreteProductsResourceExpanderPlugin;
use Spryker\Glue\ProductConfigurationsRestApi\ProductConfigurationsRestApiDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductConfigurationsRestApi
 * @group Plugin
 * @group ProductConfigurationConcreteProductsResourceExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductConfigurationConcreteProductsResourceExpanderPluginTest extends Unit
{
    protected const TEST_ID_PRODUCT_CONCRETE = 111;

    /**
     * @var \SprykerTest\Glue\ProductConfigurationsRestApi\ProductConfigurationsRestApiPluginTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandWillExpandConcreteProductsResourceWithProductConfiguration(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build();
        $restProductConfigurationInstanceAttributesTransfer = (new RestProductConfigurationInstanceAttributesTransfer())->fromArray(
            $productConfigurationInstanceTransfer->toArray(),
            true
        );
        $concreteProductsRestAttributesTransfer = (new ConcreteProductsRestAttributesBuilder())->build();
        $this->tester->setDependency(
            ProductConfigurationsRestApiDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_STORAGE,
            $this->createProductConfigurationStorageClientMock($productConfigurationInstanceTransfer)
        );

        $productConfigurationConcreteProductsResourceExpanderPlugin = new ProductConfigurationConcreteProductsResourceExpanderPlugin();

        // Act
        $concreteProductsRestAttributesTransfer = $productConfigurationConcreteProductsResourceExpanderPlugin->expand(
            $concreteProductsRestAttributesTransfer,
            static::TEST_ID_PRODUCT_CONCRETE,
            $this->createRestRequestMock()
        );

        // Assert
        $this->assertNotNull($concreteProductsRestAttributesTransfer->getProductConfigurationInstance());
        $this->assertEquals(
            $concreteProductsRestAttributesTransfer->getProductConfigurationInstance()->toArray(),
            $restProductConfigurationInstanceAttributesTransfer->toArray()
        );
    }

    /**
     * @return void
     */
    public function testExpandWillNotExpandConcreteProductsResourceWithoutProductConfiguration(): void
    {
        // Arrange
        $concreteProductsRestAttributesTransfer = (new ConcreteProductsRestAttributesBuilder())->build();
        $this->tester->setDependency(
            ProductConfigurationsRestApiDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_STORAGE,
            $this->createProductConfigurationStorageClientMock()
        );

        $productConfigurationConcreteProductsResourceExpanderPlugin = new ProductConfigurationConcreteProductsResourceExpanderPlugin();

        // Act
        $concreteProductsRestAttributesTransfer = $productConfigurationConcreteProductsResourceExpanderPlugin->expand(
            $concreteProductsRestAttributesTransfer,
            static::TEST_ID_PRODUCT_CONCRETE,
            $this->createRestRequestMock()
        );

        // Assert
        $this->assertNull($concreteProductsRestAttributesTransfer->getProductConfigurationInstance());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null $productConfigurationInstanceTransfer
     *
     * @return \Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfigurationStorageClientMock(
        ?ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer = null
    ): ProductConfigurationsRestApiToProductConfigurationStorageClientInterface {
        $mock = $this->getMockBuilder(ProductConfigurationsRestApiToProductConfigurationStorageClientInterface::class)->getMock();
        $mock->method('findProductConfigurationInstanceBySku')->willReturn($productConfigurationInstanceTransfer);

        return $mock;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createRestRequestMock(): RestRequestInterface
    {
        return $this->getMockBuilder(RestRequestInterface::class)->getMock();
    }
}
