<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Router\Plugin;

use Codeception\Test\Unit;
use Spryker\Shared\Router\RouterConstants;
use Spryker\Yves\Router\Plugin\Router\YvesRouterPlugin;
use SprykerTest\Yves\Router\Plugin\Fixtures\RouteProviderPlugin;

/**
 * Auto-generated group annotations
 *
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

        $this->tester->mockEnvironmentConfig(RouterConstants::YVES_IS_CACHE_ENABLED, false);

        $this->tester->mockFactoryMethod('getRouteProviderPlugins', [
            new RouteProviderPlugin(),
        ]);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param string $url
     * @param string $routeName
     *
     * @return void
     */
    public function testMatchReturnsParameterForPathInfo(string $url, string $routeName): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $router = $routerPlugin->getRouter();

        $parameters = $router->match($url);

        $this->assertSame($routeName, $parameters['_route']);
    }

    /**
     * @dataProvider dataProvider
     *
     * @param string $url
     * @param string $routeName
     *
     * @return void
     */
    public function testGenerateReturnsUrlForRouteName(string $url, string $routeName): void
    {
        $routerPlugin = new YvesRouterPlugin();
        $routerPlugin->setFactory($this->tester->getFactory());

        $router = $routerPlugin->getRouter();

        $url = $router->generate($routeName);

        $this->assertSame($url, $url);
    }

    /**
     * @return string[][]
     */
    public function dataProvider(): array
    {
        return [
            ['/', 'home'],
            ['/foo', 'foo'],
        ];
    }
}
