<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\NavigationItemCollectionTransfer;
use Generated\Shared\Transfer\NavigationItemTransfer;
use Spryker\Zed\Router\Business\Route\RouteCollection;
use Spryker\Zed\Router\Business\Router\Router;
use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface;
use Spryker\Zed\ZedNavigation\Business\Model\Formatter\MenuFormatter;
use Spryker\Zed\ZedNavigation\Business\ZedNavigationBusinessFactory;
use Spryker\Zed\ZedNavigation\Business\ZedNavigationFacade;
use Spryker\Zed\ZedNavigation\Business\ZedNavigationFacadeInterface;
use Spryker\Zed\ZedNavigation\Dependency\Facade\ZedNavigationToRouterFacadeBridge;
use Spryker\Zed\ZedNavigation\Dependency\Facade\ZedNavigationToRouterFacadeInterface;
use Spryker\Zed\ZedNavigation\ZedNavigationConfig;
use Spryker\Zed\ZedNavigation\ZedNavigationDependencyProvider;
use Symfony\Component\Routing\Route;

class ZedNavigationBusinessTester extends Unit
{
    /**
     * @var \SprykerTest\Zed\ZedNavigation\ZedNavigationBusinessTester
     */
    protected $tester;

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\ZedNavigationFacadeInterface
     */
    protected function getFacade(): ZedNavigationFacadeInterface
    {
        return new ZedNavigationFacade();
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\ZedNavigationBusinessFactory
     */
    protected function getFactory(): ZedNavigationBusinessFactory
    {
        return new ZedNavigationBusinessFactory();
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Collector\ZedNavigationCollectorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getZedNavigationCollectorMock(): ZedNavigationCollectorInterface
    {
        return $this
            ->getMockBuilder(ZedNavigationCollectorInterface::class)
            ->setMethods(['getNavigation'])
            ->getMock();
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getZedNavigationCacheMock(): ZedNavigationCacheInterface
    {
        $cacheMock = $this
            ->getMockBuilder(ZedNavigationCacheInterface::class)
            ->setMethods(['setNavigation', 'getNavigation', 'hasContent', 'isEnabled', 'removeCache'])
            ->getMock();

        $cacheMock->expects($this->never())
            ->method('isEnabled');
        $cacheMock->expects($this->never())
            ->method('hasContent');

        return $cacheMock;
    }

    /**
     * @param array $navigationData
     * @param bool $hasContent
     *
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCacheInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getZedNavigationCacheMockWithReturn(
        array $navigationData,
        bool $hasContent = true
    ): ZedNavigationCacheInterface {
        $cacheMock = $this
            ->getMockBuilder(ZedNavigationCacheInterface::class)
            ->setMethods(['setNavigation', 'getNavigation', 'hasContent', 'isEnabled', 'removeCache'])
            ->getMock();

        $cacheMock->expects($this->never())
            ->method('isEnabled');
        $cacheMock->expects($this->once())
            ->method('hasContent')
            ->willReturn($hasContent);
        $cacheMock->expects($this->once())
            ->method('getNavigation')
            ->will($this->returnValue($navigationData));

        return $cacheMock;
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\ZedNavigationConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getZedNavigationConfigMock(): ZedNavigationConfig
    {
        return $this
            ->getMockBuilder(ZedNavigationConfig::class)
            ->setMethods(['isNavigationCacheEnabled'])
            ->getMock();
    }

    /**
     * @param array $navigationItems
     * @param \Generated\Shared\Transfer\NavigationItemCollectionTransfer $navigationItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    protected function mapNavigationItemsToNavigationItemCollectionTransfer(
        array $navigationItems,
        NavigationItemCollectionTransfer $navigationItemCollectionTransfer
    ): NavigationItemCollectionTransfer {
        foreach ($navigationItems as $navigationItem) {
            if ($this->hasNestedNavigationItems($navigationItem)) {
                $navigationItemCollectionTransfer = $this->mapNavigationItemsToNavigationItemCollectionTransfer(
                    $navigationItem[MenuFormatter::PAGES],
                    $navigationItemCollectionTransfer
                );

                continue;
            }

            $navigationItemTransfer = (new NavigationItemTransfer())
                ->fromArray($navigationItem, true)
                ->setModule($navigationItem[MenuFormatter::BUNDLE] ?? null);

            $navigationItemName = $this->getNavigationItemKey($navigationItem);

            if ($navigationItemName !== '') {
                $navigationItemCollectionTransfer->addNavigationItem(
                    $navigationItemName,
                    $navigationItemTransfer
                );
            }
        }

        return $navigationItemCollectionTransfer;
    }

    /**
     * @param array $navigationItem
     *
     * @return bool
     */
    protected function hasNestedNavigationItems(array $navigationItem): bool
    {
        return isset($navigationItem[MenuFormatter::PAGES]);
    }

    /**
     * @param array<string> $navigationItem
     *
     * @return string
     */
    protected function getNavigationItemKey(array $navigationItem): string
    {
        if (
            isset($navigationItem[MenuFormatter::BUNDLE])
            && isset($navigationItem[MenuFormatter::CONTROLLER])
            && isset($navigationItem[MenuFormatter::ACTION])
        ) {
            return sprintf(
                '%s:%s:%s',
                $navigationItem[MenuFormatter::BUNDLE],
                $navigationItem[MenuFormatter::CONTROLLER],
                $navigationItem[MenuFormatter::ACTION]
            );
        }

        return $navigationItem[MenuFormatter::URI] ?? '';
    }

    /**
     * @param array<\Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemCollectionFilterPluginInterface> $plugins
     *
     * @return void
     */
    protected function provideNavigationItemCollectionFilterPlugins(array $plugins = []): void
    {
        $this->tester->setDependency(ZedNavigationDependencyProvider::PLUGINS_NAVIGATION_ITEM_COLLECTION_FILTER, $plugins);
    }

    /**
     * @return void
     */
    protected function provideRouterFacade()
    {
        $this->tester->setDependency(ZedNavigationDependencyProvider::FACADE_ROUTER, $this->buildZedNavigationToRouterFacadeBridge());
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Dependency\Facade\ZedNavigationToRouterFacadeInterface
     */
    protected function buildZedNavigationToRouterFacadeBridge(): ZedNavigationToRouterFacadeInterface
    {
        $collection = new RouteCollection();
        $collection->add('test:index:index', $this->createMock(Route::class));

        $zedNavigationToRouterFacadeBridgeMock = $this->createPartialMock(ZedNavigationToRouterFacadeBridge::class, ['getBackofficeRouter']);
        $routerMock = $this->createPartialMock(Router::class, ['getRouteCollection']);

        $routerMock->method('getRouteCollection')->willReturn($collection);

        $zedNavigationToRouterFacadeBridgeMock->method('getBackofficeRouter')->willReturn($routerMock);

        return $zedNavigationToRouterFacadeBridgeMock;
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\ZedNavigationFacadeInterface
     */
    protected function getFacadeWithCustomNavigationFile(): ZedNavigationFacadeInterface
    {
        $zedNavigationConfigMock = $this->buildZedNavigationConfigMock();
        $zedNavigationBusinessFactoryMock = $this->buildZedNavigationBusinessFactoryMock($zedNavigationConfigMock);

        $zedNavigationFacade = new ZedNavigationFacade();

        return $zedNavigationFacade->setFactory($zedNavigationBusinessFactoryMock);
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\ZedNavigationConfig
     */
    protected function buildZedNavigationConfigMock(): ZedNavigationConfig
    {
        $zedNavigationConfig = $this->createPartialMock(ZedNavigationConfig::class, ['getNavigationSchemaPathPattern', 'getRootNavigationSchemaPaths']);
        $zedNavigationConfig->method('getNavigationSchemaPathPattern')->willReturn([codecept_data_dir()]);
        $zedNavigationConfig->method('getRootNavigationSchemaPaths')->willReturn([]);

        return $zedNavigationConfig;
    }

    /**
     * @param \Spryker\Zed\ZedNavigation\ZedNavigationConfig $zedNavigationConfig
     *
     * @return \Spryker\Zed\ZedNavigation\Business\ZedNavigationBusinessFactory
     */
    protected function buildZedNavigationBusinessFactoryMock(ZedNavigationConfig $zedNavigationConfig): ZedNavigationBusinessFactory
    {
        $factory = new ZedNavigationBusinessFactory();

        return $factory->setConfig($zedNavigationConfig);
    }
}
