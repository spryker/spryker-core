<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Router\Plugin;

use Codeception\Test\Unit;
use Spryker\Shared\Router\RouterConstants;
use Spryker\Yves\Router\Plugin\RouteManipulator\LanguageDefaultRouteManipulatorPlugin;
use Spryker\Yves\Router\Plugin\RouteManipulator\StoreDefaultRouteManipulatorPlugin;
use Spryker\Yves\Router\Plugin\Router\YvesRouterPlugin;
use Spryker\Yves\Router\Plugin\RouterEnhancer\LanguagePrefixRouterEnhancerPlugin;
use Spryker\Yves\Router\Plugin\RouterEnhancer\StorePrefixRouterEnhancerPlugin;
use SprykerTest\Yves\Router\Plugin\Fixtures\RouteProviderPlugin;
use Symfony\Component\Routing\RequestContext;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Router
 * @group Plugin
 * @group YvesRouterPluginWithStoreAndLanguagePrefixRouterEnhancerTest
 * Add your own group annotations below this line
 */
class YvesRouterPluginWithStoreAndLanguagePrefixRouterEnhancerTest extends Unit
{
    /**
     * @var \SprykerTest\Yves\Router\RouterYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->tester->mockEnvironmentConfig(RouterConstants::ROUTER_CACHE_ENABLED_YVES, false);

        $this->tester->mockFactoryMethod('getRouteProviderPlugins', [
            new RouteProviderPlugin(),
        ]);

        $this->tester->mockFactoryMethod('getRouteManipulatorPlugins', [
            new LanguageDefaultRouteManipulatorPlugin(),
            new StoreDefaultRouteManipulatorPlugin(),
        ]);

        $this->tester->mockFactoryMethod('getRouterEnhancerPlugins', [
            new StorePrefixRouterEnhancerPlugin(),
            new LanguagePrefixRouterEnhancerPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testMatchReturnsParameterWithStoreAndLanguage(): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $router = $routerPlugin->getRouter();

        $parameters = $router->match('/DE/de/foo');

        $this->assertSame('foo', $parameters['_route']);
        $this->assertSame('de', $parameters['language']);
        $this->assertSame('DE', $parameters['store']);
    }

    /**
     * @return void
     */
    public function testGenerateReturnsUrlWithStoreAndLanguageWhenStoreAndLanguageAreInContextParameter(): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $requestContext = new RequestContext();
        $requestContext->setParameter('language', 'de');
        $requestContext->setParameter('store', 'DE');

        $router = $routerPlugin->getRouter();
        $router->setContext($requestContext);

        $url = $router->generate('foo');

        $this->assertSame('/DE/de/foo', $url);
    }

    /**
     * @return void
     */
    public function testGenerateReturnsUrlWithoutStoreAndLanguageWhenStoreAndLanguageAreNotInContextParameter(): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $router = $routerPlugin->getRouter();

        $url = $router->generate('foo');

        $this->assertSame('/foo', $url);
    }
}
