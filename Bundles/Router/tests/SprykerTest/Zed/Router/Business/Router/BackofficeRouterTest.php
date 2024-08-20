<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Router\Business\Router;

use Codeception\Test\Unit;
use Spryker\Zed\Router\Business\Loader\LoaderInterface;
use Spryker\Zed\Router\Business\Route\Route;
use Spryker\Zed\Router\Business\Route\RouteCollection;
use Spryker\Zed\Router\Business\Router\Router;
use SprykerTest\Zed\Router\RouterBusinessTester;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Config\ConfigCacheInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Router
 * @group Business
 * @group Router
 * @group BackofficeRouterTest
 * Add your own group annotations below this line
 */
class BackofficeRouterTest extends Unit
{
    /**
     * @var int
     */
    protected const MODULE_ROOT_DIRECTORY_LEVEL = 6;

    /**
     * @var string
     */
    protected const OPTION_CACHE_DIR = 'cache_dir';

    /**
     * @var \SprykerTest\Zed\Router\RouterBusinessTester
     */
    protected RouterBusinessTester $tester;

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->tester->cleanCache();
    }

    /**
     * @return void
     */
    public function testGetBackofficeRouteCollectionReturnsRoutesFromLoaderWhenCacheDirIsNull(): void
    {
        // Arrange
        $options = [static::OPTION_CACHE_DIR => null];
        $routeCollection = new RouteCollection();
        $routeCollection->add('test1', new Route('test1'));
        $router = $this->mockDependencies($routeCollection, $options);

        // Act
        $routes = $router->getRouteCollection()->all();

        // Assert
        $this->tester->assertIsArray($routes);
        $this->tester->assertNotEmpty($routes);
        $this->tester->assertArrayHasKey('test1', $routes);
    }

    /**
     * @return void
     */
    public function testGetBackofficeRouteCollectionRetrievesRoutesFromCachePath(): void
    {
        // Arrange
        $options = [static::OPTION_CACHE_DIR => $this->tester->getCacheDir()];

        $routeCollection = new RouteCollection();
        $routeCollection->add('test1', new Route('test1'));
        $router = $this->mockDependencies($routeCollection, $options, true);

        // Act
        $generatedRoutes = $router->getRouteCollection()->all();

        // Assert
        $this->tester->assertIsArray($generatedRoutes);
        $this->tester->assertArrayHasKey('test1', $generatedRoutes);
    }

    /**
     * @return void
     */
    public function testGetBackofficeRouteCollectionHandlesEmptyRouteCollectionGracefully(): void
    {
        // Arrange
        $options = [static::OPTION_CACHE_DIR => $this->tester->getCacheDir()];

        $routeCollection = new RouteCollection();
        $router = $this->mockDependencies($routeCollection, $options, true);

        // Act
        $generatedRoutes = $router->getRouteCollection()->all();

        // Assert
        $this->assertIsArray($generatedRoutes);
        $this->assertEmpty($generatedRoutes);
    }

    /**
     * @param \Spryker\Zed\Router\Business\Route\RouteCollection $routeCollection
     * @param array $options
     * @param bool $withCache
     *
     * @return \Spryker\Zed\Router\Business\Router\Router
     */
    protected function mockDependencies(
        RouteCollection $routeCollection,
        array $options = [],
        bool $withCache = false
    ): Router {
        $loader = $this->getMockBuilder(LoaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $loader->method('load')
            ->willReturn($routeCollection);
        /** @var \Spryker\Zed\Router\RouterConfig $configMock */
        $configMock = $this->tester->getModuleConfig();
        $router = new Router($loader, 'resource', $configMock, [], $options);

        if ($withCache) {
            $configCacheFactory = $this->createMock(ConfigCacheFactory::class);
            $configCache = $this->createMock(ConfigCacheInterface::class);
            $cacheFile = $this->tester->getCacheFileName();

            $configCache->method('getPath')->willReturn($cacheFile);
            $configCacheFactory->method('cache')->willReturn($configCache);
            $router->setConfigCacheFactory($configCacheFactory);
        }

        return $router;
    }
}
