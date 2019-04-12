<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Router\Plugin;

use Codeception\Test\Unit;
use Spryker\Shared\Router\RouterConstants;
use Spryker\Yves\Router\Plugin\Router\YvesRouterPlugin;
use Spryker\Yves\Router\Plugin\RouterEnhancer\QueryStringRouterEnhancerPlugin;
use SprykerTest\Yves\Router\Plugin\Fixtures\RouteProviderPlugin;
use Symfony\Component\Routing\RequestContext;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Router
 * @group Plugin
 * @group YvesRouterPluginWithQueryStringRouterEnhancerTest
 * Add your own group annotations below this line
 */
class YvesRouterPluginWithQueryStringRouterEnhancerTest extends Unit
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

        $this->tester->mockFactoryMethod('getRouterEnhancerPlugins', [
            new QueryStringRouterEnhancerPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testGenerateReturnsUrlWithQueryParameter(): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $requestContext = new RequestContext();
        $requestContext->setQueryString('?foo=bar&baz=bat');

        $router = $routerPlugin->getRouter();
        $router->setContext($requestContext);

        $url = $router->generate('foo');

        $this->assertSame('/foo?foo=bar&baz=bat', $url);
    }

    /**
     * @return void
     */
    public function testGenerateReturnsUrlWithoutQueryParameter(): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $router = $routerPlugin->getRouter();

        $url = $router->generate('foo');

        $this->assertSame('/foo', $url);
    }
}
