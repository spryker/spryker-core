<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Router\Plugin;

use Codeception\Test\Unit;
use Spryker\Yves\Router\Plugin\Router\YvesRouterPlugin;
use Spryker\Yves\Router\UrlMatcher\RedirectableUrlMatcher;
use SprykerTest\Yves\Router\Plugin\Fixtures\RouteProviderPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Router
 * @group Plugin
 * @group YvesRouterPluginTest
 * Add your own group annotations below this line
 */
class YvesRouterPluginTest extends Unit
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
    }

    /**
     * @return void
     */
    public function testMatchReturnsParameterForPathInfo(): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $router = $routerPlugin->getRouter();

        $parameters = $router->match('/foo');

        $this->assertSame('foo', $parameters['_route']);
    }

    /**
     * @return void
     */
    public function testGenerateReturnsUrlForRouteName(): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $router = $routerPlugin->getRouter();

        $url = $router->generate('foo');

        $this->assertSame('/foo', $url);
    }
}
