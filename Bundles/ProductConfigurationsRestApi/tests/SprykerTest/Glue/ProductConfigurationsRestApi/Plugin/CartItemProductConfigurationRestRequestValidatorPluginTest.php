<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductConfigurationsRestApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\DataBuilder\RestCartItemsAttributesBuilder;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Dependency\Client\ProductConfigurationsRestApiToProductConfigurationStorageClientInterface;
use Spryker\Glue\ProductConfigurationsRestApi\Plugin\GlueApplication\CartItemProductConfigurationRestRequestValidatorPlugin;
use Spryker\Glue\ProductConfigurationsRestApi\ProductConfigurationsRestApiConfig;
use Spryker\Glue\ProductConfigurationsRestApi\ProductConfigurationsRestApiDependencyProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductConfigurationsRestApi
 * @group Plugin
 * @group CartItemProductConfigurationRestRequestValidatorPluginTest
 * Add your own group annotations below this line
 */
class CartItemProductConfigurationRestRequestValidatorPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESOURCE_CART_ITEMS
     */
    protected const CART_ITEMS_RESOURCE = 'items';

    protected const PRODUCT_CONFIGURATION_KEY = 'TEST_PRODUCT_CONFIGURATION';
    protected const PRODUCT_CONCRETE_SKU = 'concrete-sku';

    /**
     * @var \SprykerTest\Glue\ProductConfigurationsRestApi\ProductConfigurationsRestApiPluginTester
     */
    protected $tester;

    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApi\Plugin\GlueApplication\CartItemProductConfigurationRestRequestValidatorPlugin
     */
    protected $cartItemProductConfigurationRestRequestValidatorPlugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cartItemProductConfigurationRestRequestValidatorPlugin = new CartItemProductConfigurationRestRequestValidatorPlugin();
    }

    /**
     * @return void
     */
    public function testValidateWillReturnNullWhenRequestedResourceIsNotInValidationList(): void
    {
        // Arrange
        $restAttribute = $this->createRestResourceMock('some-random-resource');
        $httpRequest = $this->createHttpRequestMock();
        $restRequest = $this->createRestRequestMock($restAttribute);

        // Act
        $restErrorCollectionTransfer = $this->cartItemProductConfigurationRestRequestValidatorPlugin->validate(
            $httpRequest,
            $restRequest
        );

        // Assert
        $this->assertNull($restErrorCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testValidateWillReturnNullWhenResourceAttributeDoesNotHaveProductConfiguration(): void
    {
        // Arrange
        $restCartItemsAttributesTransfer = (new RestCartItemsAttributesBuilder())->build();
        $restAttribute = $this->createRestResourceMock(static::CART_ITEMS_RESOURCE, $restCartItemsAttributesTransfer);
        $httpRequest = $this->createHttpRequestMock();
        $restRequest = $this->createRestRequestMock($restAttribute);

        // Act
        $restErrorCollectionTransfer = $this->cartItemProductConfigurationRestRequestValidatorPlugin->validate(
            $httpRequest,
            $restRequest
        );

        // Assert
        $this->assertNull($restErrorCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testValidateWillReturnNullWhenItemHaveDefaultProductConfigurationWithSameKey(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder([
            ProductConfigurationInstanceTransfer::CONFIGURATOR_KEY => static::PRODUCT_CONFIGURATION_KEY,
        ]))->build();
        $this->tester->setDependency(
            ProductConfigurationsRestApiDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_STORAGE,
            $this->createProductConfigurationStorageClientMock($productConfigurationInstanceTransfer)
        );

        $restCartItemsAttributesTransfer = (new RestCartItemsAttributesBuilder([RestCartItemsAttributesTransfer::SKU => static::PRODUCT_CONCRETE_SKU]))
            ->withProductConfigurationInstance([
                RestCartItemProductConfigurationInstanceAttributesTransfer::CONFIGURATOR_KEY => static::PRODUCT_CONFIGURATION_KEY,
            ])
            ->build();

        $restAttribute = $this->createRestResourceMock(static::CART_ITEMS_RESOURCE, $restCartItemsAttributesTransfer);
        $httpRequest = $this->createHttpRequestMock();
        $restRequest = $this->createRestRequestMock($restAttribute);

        // Act
        $restErrorCollectionTransfer = $this->cartItemProductConfigurationRestRequestValidatorPlugin->validate(
            $httpRequest,
            $restRequest
        );

        // Assert
        $this->assertNull($restErrorCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testValidateWillReturnErrorWhenItemDoesNotHaveDefaultProductConfiguration(): void
    {
        // Arrange
        $this->tester->setDependency(
            ProductConfigurationsRestApiDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_STORAGE,
            $this->createProductConfigurationStorageClientMock()
        );

        $restCartItemsAttributesTransfer = (new RestCartItemsAttributesBuilder([RestCartItemsAttributesTransfer::SKU => static::PRODUCT_CONCRETE_SKU]))
            ->withProductConfigurationInstance([
                RestCartItemProductConfigurationInstanceAttributesTransfer::CONFIGURATOR_KEY => static::PRODUCT_CONFIGURATION_KEY,
            ])
            ->build();

        $restAttribute = $this->createRestResourceMock(static::CART_ITEMS_RESOURCE, $restCartItemsAttributesTransfer);
        $httpRequest = $this->createHttpRequestMock();
        $restRequest = $this->createRestRequestMock($restAttribute);

        // Act
        $restErrorCollectionTransfer = $this->cartItemProductConfigurationRestRequestValidatorPlugin->validate(
            $httpRequest,
            $restRequest
        );

        // Assert
        $this->assertNotNull($restErrorCollectionTransfer);
        $this->assertCount(1, $restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            ProductConfigurationsRestApiConfig::RESPONSE_CODE_DEFAULT_PRODUCT_CONFIGURATION_INSTANCE_IS_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->offsetGet(0)->getCode()
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->offsetGet(0)->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testValidateWillReturnErrorWhenItemDoesNotHaveProductConfigurationWithGivenConfigurationKey(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder([
            ProductConfigurationInstanceTransfer::CONFIGURATOR_KEY => static::PRODUCT_CONFIGURATION_KEY,
        ]))->build();
        $this->tester->setDependency(
            ProductConfigurationsRestApiDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_STORAGE,
            $this->createProductConfigurationStorageClientMock($productConfigurationInstanceTransfer)
        );

        $restCartItemsAttributesTransfer = (new RestCartItemsAttributesBuilder([RestCartItemsAttributesTransfer::SKU => static::PRODUCT_CONCRETE_SKU]))
            ->withProductConfigurationInstance([
                RestCartItemProductConfigurationInstanceAttributesTransfer::CONFIGURATOR_KEY => 'SOME_KEY',
            ])
            ->build();

        $restAttribute = $this->createRestResourceMock(static::CART_ITEMS_RESOURCE, $restCartItemsAttributesTransfer);
        $httpRequest = $this->createHttpRequestMock();
        $restRequest = $this->createRestRequestMock($restAttribute);

        // Act
        $restErrorCollectionTransfer = $this->cartItemProductConfigurationRestRequestValidatorPlugin->validate(
            $httpRequest,
            $restRequest
        );

        // Assert
        $this->assertNotNull($restErrorCollectionTransfer);
        $this->assertCount(1, $restErrorCollectionTransfer->getRestErrors());
        $this->assertEquals(
            ProductConfigurationsRestApiConfig::RESPONSE_CODE_DEFAULT_PRODUCT_CONFIGURATION_INSTANCE_IS_MISSING,
            $restErrorCollectionTransfer->getRestErrors()->offsetGet(0)->getCode()
        );
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $restErrorCollectionTransfer->getRestErrors()->offsetGet(0)->getStatus()
        );
    }

    /**
     * @param string $resourceType
     * @param \Generated\Shared\Transfer\RestCartItemsAttributesTransfer|null $restCartItemsAttributesTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createRestResourceMock(
        string $resourceType,
        ?RestCartItemsAttributesTransfer $restCartItemsAttributesTransfer = null
    ): RestResourceInterface {
        $restResourceMock = $this->getMockBuilder(RestResourceInterface::class)->getMock();
        $restResourceMock->method('getType')->willReturn($resourceType);
        $restResourceMock->method('getAttributes')->willReturn($restCartItemsAttributesTransfer);

        return $restResourceMock;
    }

    /**
     * @return \PHPUnit\Framework\MokObject\MockObject|\Symfony\Component\HttpFoundation\Request
     */
    protected function createHttpRequestMock(): Request
    {
        $requestMock = $this->getMockBuilder(Request::class)->getMock();
        $requestMock->method('getMethod')->willReturn(Request::METHOD_POST);

        return $requestMock;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected function createRestRequestMock(RestResourceInterface $restResource): RestRequestInterface
    {
        $restRequestMock = $this->getMockBuilder(RestRequestInterface::class)->getMock();
        $restRequestMock->method('getResource')->willReturn($restResource);

        return $restRequestMock;
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
}
