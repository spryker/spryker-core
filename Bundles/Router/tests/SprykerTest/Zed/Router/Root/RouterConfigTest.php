<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Router\Root;

use Codeception\Stub;
use Codeception\Test\Unit;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Router\RouterConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Router
 * @group Root
 * @group RouterConfigTest
 * Add your own group annotations below this line
 */
class RouterConfigTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Router\RouterRootTester
     */
    protected $tester;

    /**
     * @return array
     */
    public function coreNamespaceProvider(): array
    {
        return [
            ['Spryker', 'root:///spryker/*/src/Spryker/Zed/*/Communication/Controller/'],
            ['SprykerShop', 'root:///spryker-shop/*/src/SprykerShop/Zed/*/Communication/Controller/'],
            ['AShop', 'root:///a-shop/*/src/AShop/Zed/*/Communication/Controller/'],
            ['ShopA', 'root:///shop-a/*/src/ShopA/Zed/*/Communication/Controller/'],
            ['Shop1', 'root:///shop-1/*/src/Shop1/Zed/*/Communication/Controller/'],
            ['1Shop', 'root:///1-shop/*/src/1Shop/Zed/*/Communication/Controller/'],
        ];
    }

    /**
     * @dataProvider coreNamespaceProvider
     *
     * @param string $camelCasedName
     * @param string $expectedDirectory
     *
     * @return void
     */
    public function testGetControllerDirectories(string $camelCasedName, string $expectedDirectory): void
    {
        $this->tester->setConfig(KernelConstants::PROJECT_NAMESPACES, []);
        $this->tester->setConfig(KernelConstants::CORE_NAMESPACES, [$camelCasedName]);

        /** @var \Spryker\Zed\Router\RouterConfig $routerConfigStub */
        $routerConfigStub = Stub::make(RouterConfig::class, [
            'getVendorDirectory' => 'root://',
            'filterDirectories' => function ($input) {
                return $input;
            },
        ]);

        $controllerDirectory = $routerConfigStub->getControllerDirectories()[0];

        $this->assertSame($expectedDirectory, $controllerDirectory);
    }
}
