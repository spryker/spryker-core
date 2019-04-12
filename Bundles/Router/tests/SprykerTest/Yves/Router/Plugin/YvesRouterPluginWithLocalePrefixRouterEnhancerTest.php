<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Router\Plugin;

use Codeception\Test\Unit;
use Spryker\Yves\Router\Plugin\Router\YvesRouterPlugin;
use Spryker\Yves\Router\Plugin\RouterEnhancer\LocalePrefixRouterEnhancerPlugin;
use Spryker\Yves\Router\UrlMatcher\RedirectableUrlMatcher;
use SprykerTest\Yves\Router\Plugin\Fixtures\RouteProviderPlugin;
use Symfony\Component\Routing\RequestContext;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Router
 * @group Plugin
 * @group YvesRouterPluginWithLocalePrefixRouterEnhancerTest
 * Add your own group annotations below this line
 */
class YvesRouterPluginWithLocalePrefixRouterEnhancerTest extends Unit
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

        $this->tester->mockConfigMethod(
            'getRouterConfiguration',
            [
                'cache_dir' => null,
                'generator_cache_class' => 'YvesUrlGenerator',
                'matcher_cache_class' => 'YvesUrlMatcher',
                'matcher_base_class' => RedirectableUrlMatcher::class,
            ]
        );

        $this->tester->mockFactoryMethod('getRouteProviderPlugins', [
            new RouteProviderPlugin(),
        ]);

        $this->tester->mockFactoryMethod('getRouterEnhancerPlugins', [
            new LocalePrefixRouterEnhancerPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testMatchReturnsParameterWithLocale(): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $router = $routerPlugin->getRouter();

        $parameters = $router->match('/de/foo');

        $this->assertSame('foo', $parameters['_route']);
        $this->assertSame('de', $parameters['locale']);
    }

    /**
     * @return void
     */
    public function testGenerateReturnsUrlWithLocaleWhenLocaleIsInContextParameter(): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $requestContext = new RequestContext();
        $requestContext->setParameter('locale', 'de');

        $router = $routerPlugin->getRouter();
        $router->setContext($requestContext);

        $url = $router->generate('foo');

        $this->assertSame('/de/foo', $url);
    }

    /**
     * @return void
     */
    public function testGenerateReturnsUrlWithoutLocaleWhenLocaleIsNotInContextParameter(): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $router = $routerPlugin->getRouter();

        $url = $router->generate('foo');

        $this->assertSame('/foo', $url);
    }
}
