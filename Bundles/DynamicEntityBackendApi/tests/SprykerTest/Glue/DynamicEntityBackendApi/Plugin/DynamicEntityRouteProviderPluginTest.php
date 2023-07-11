<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DynamicEntityBackendApi\Plugin;

use Codeception\Test\Unit;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration;
use Spryker\Glue\DynamicEntityBackendApi\Plugin\GlueApplication\DynamicEntityRouteProviderPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DynamicEntityBackendApi
 * @group Plugin
 * @group DynamicEntityRouteProviderPluginTest
 * Add your own group annotations below this line
 */
class DynamicEntityRouteProviderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const COLLECTION_PATH = '/dynamic-entity/foo';

    /**
     * @var string
     */
    protected const BY_ID_PATH = '/dynamic-entity/foo/{id}';

    /**
     * @var string
     */
    protected const ROUTE_NAME = 'foo%s';

    /**
     * @var string
     */
    protected const ROUTE_NAME_COLLECTION = 'fooCollection%s';

    /**
     * @var string
     */
    protected const GET_COLLECTION_ACTION = 'getCollectionAction';

    /**
     * @var string
     */
    protected const GET_ACTION = 'getAction';

    /**
     * @var string
     */
    protected const POST_ACTION = 'postAction';

    /**
     * @var string
     */
    protected const PATCH_ACTION = 'patchAction';

    /**
     * @var string
     */
    protected const PUT_ACTION = 'putAction';

    /**
     * @var string
     */
    protected const CONTROLLER = '_controller';

    /**
     * @var string
     */
    protected const METHOD = '_method';

    /**
     * @dataProvider routeDataProvider
     *
     * @param string $routeName
     * @param string $path
     * @param string $method
     * @param string $action
     *
     * @return void
     */
    public function testDynamicEntityRouteProviderPluginAddsRoutes(string $routeName, string $path, string $method, string $action): void
    {
        //Arrange
        $this->createFooEntity();
        $dynamicEntityRouteProviderPlugin = new DynamicEntityRouteProviderPlugin();
        $routeCollection = new RouteCollection();

        //Act
        $routeCollection = $dynamicEntityRouteProviderPlugin->addRoutes($routeCollection);

        //Assert
        $route = $routeCollection->get($routeName);
        $this->assertNotNull($route);
        $this->assertEquals($path, $route->getPath());
        $this->assertEquals($method, $route->getDefaults()[static::METHOD]);
        $this->assertEquals($action, $route->getDefaults()[static::CONTROLLER][1]);
    }

    /**
     * @return array<mixed>
     */
    protected function routeDataProvider(): array
    {
        return [
            [
                $this->buildRouteName(static::ROUTE_NAME_COLLECTION, Request::METHOD_GET),
                static::COLLECTION_PATH,
                Request::METHOD_GET,
                static::GET_COLLECTION_ACTION,
            ], [
                $this->buildRouteName(static::ROUTE_NAME, Request::METHOD_GET),
                static::BY_ID_PATH,
                Request::METHOD_GET,
                static::GET_ACTION,
            ], [
                $this->buildRouteName(static::ROUTE_NAME, Request::METHOD_POST),
                static::COLLECTION_PATH,
                Request::METHOD_POST,
                static::POST_ACTION,
            ], [
                $this->buildRouteName(static::ROUTE_NAME_COLLECTION, Request::METHOD_PATCH),
                static::COLLECTION_PATH,
                Request::METHOD_PATCH,
                static::PATCH_ACTION,
            ], [
                $this->buildRouteName(static::ROUTE_NAME, Request::METHOD_PATCH),
                static::BY_ID_PATH,
                Request::METHOD_PATCH,
                static::PATCH_ACTION,
            ], [
                $this->buildRouteName(static::ROUTE_NAME_COLLECTION, Request::METHOD_PUT),
                static::COLLECTION_PATH,
                Request::METHOD_PUT,
                static::PUT_ACTION,
            ], [
                $this->buildRouteName(static::ROUTE_NAME, Request::METHOD_PUT),
                static::BY_ID_PATH,
                Request::METHOD_PUT,
                static::PUT_ACTION,
            ],
        ];
    }

    /**
     * @return void
     */
    protected function createFooEntity(): void
    {
        (new SpyDynamicEntityConfiguration())
            ->setIsActive(true)
            ->setTableAlias($this->tester::FOO_TABLE_ALIAS)
            ->setTableName($this->tester::TABLE_NAME)
            ->setDefinition($this->tester->buildDefinitionWithNonAutoIncrementedId())
            ->save();
    }

    /**
     * @param string $routeNamePlaceholder
     * @param string $method
     *
     * @return string
     */
    protected function buildRouteName(string $routeNamePlaceholder, string $method): string
    {
        return sprintf($routeNamePlaceholder, $method);
    }
}
