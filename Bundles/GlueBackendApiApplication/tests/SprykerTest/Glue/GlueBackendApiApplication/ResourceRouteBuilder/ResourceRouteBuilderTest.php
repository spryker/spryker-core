<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\ResourceRouteBuilder;

use Codeception\Test\Unit;
use Spryker\Glue\GlueBackendApiApplication\ResourceRouteBuilder\ResourceRouteBuilder;
use SprykerTest\Glue\GlueBackendApiApplication\Stub\TestResourceEmptyMethodsRouteProviderPlugin;
use SprykerTest\Glue\GlueBackendApiApplication\Stub\TestResourceRouteProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueBackendApiApplication
 * @group ResourceRouteBuilder
 * @group ResourceRouteBuilderTest
 * Add your own group annotations below this line
 */
class ResourceRouteBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const RESOURCE_METHOD_GET = 'get';

    /**
     * @var string
     */
    protected const RESOURCE_METHOD_GET_COLLECTION = 'getCollection';

    /**
     * @var string
     */
    protected const RESOURCE_METHOD_POST = 'post';

    /**
     * @var string
     */
    protected const RESOURCE_METHOD_PATCH = 'patch';

    /**
     * @var string
     */
    protected const RESOURCE_METHOD_DELETE = 'delete';

    /**
     * @return void
     */
    public function testBuilderReturnsNonEmptyRoutes(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);

        //Assert
        $this->assertNotEmpty($routes);
    }

    /**
     * @return void
     */
    public function testBuilderReturnsEmptyRoutes(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceEmptyMethodsRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);

        //Assert
        $this->assertEmpty($routes);
    }

    /**
     * @return void
     */
    public function testGetMethodRouteIsGenerated(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);
        $methodKey = $this->getGeneratedMethodKey($resourcePlugin->getType(), static::RESOURCE_METHOD_GET);

        //Arrange
        $this->assertNotNull($routes[$methodKey]);
    }

    /**
     * @return void
     */
    public function testGetCoolectionMethodRouteIsGenerated(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);
        $methodKey = $this->getGeneratedMethodKey($resourcePlugin->getType(), static::RESOURCE_METHOD_GET_COLLECTION);

        //Arrange
        $this->assertNotNull($routes[$methodKey]);
    }

    /**
     * @return void
     */
    public function testPostMethodRouteIsGenerated(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);
        $methodKey = $this->getGeneratedMethodKey($resourcePlugin->getType(), static::RESOURCE_METHOD_POST);

        //Arrange
        $this->assertNotNull($routes[$methodKey]);
    }

    /**
     * @return void
     */
    public function testPatchMethodRouteIsGenerated(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);
        $methodKey = $this->getGeneratedMethodKey($resourcePlugin->getType(), static::RESOURCE_METHOD_PATCH);

        //Arrange
        $this->assertNotNull($routes[$methodKey]);
    }

    /**
     * @return void
     */
    public function testDeleteMethodRouteIsGenerated(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);
        $methodKey = $this->getGeneratedMethodKey($resourcePlugin->getType(), static::RESOURCE_METHOD_DELETE);

        //Arrange
        $this->assertNotNull($routes[$methodKey]);
    }

    /**
     * @param string $resourceType
     * @param string $method
     *
     * @return string
     */
    protected function getGeneratedMethodKey(string $resourceType, string $method): string
    {
        return sprintf(
            '%s%s%s',
            $resourceType,
            'Resource',
            ucfirst($method),
        );
    }

    /**
     * @return \SprykerTest\Glue\GlueStorefrontApiApplication\Stub\TestResourceRouteProviderPlugin
     */
    protected function createResourceRouterProviderPlugin(): TestResourceRouteProviderPlugin
    {
        return new TestResourceRouteProviderPlugin();
    }

    /**
     * @return \SprykerTest\Glue\GlueStorefrontApiApplication\Stub\TestResourceEmptyMethodsRouteProviderPlugin
     */
    protected function createResourceEmptyMethodsRouterProviderPlugin(): TestResourceEmptyMethodsRouteProviderPlugin
    {
        return new TestResourceEmptyMethodsRouteProviderPlugin();
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\ResourceRouteBuilder\ResourceRouteBuilder
     */
    protected function createResourceRouteBuilder(): ResourceRouteBuilder
    {
        return new ResourceRouteBuilder();
    }
}
