<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Router\Business\Router\RouterResource;

use Codeception\Test\Unit;
use Spryker\Zed\Router\Business\Router\RouterResource\MerchantPortalRouterResource;
use Spryker\Zed\Router\Business\RouterResource\ResourceInterface;
use Spryker\Zed\Router\RouterConfig;
use SprykerTest\Zed\Router\RouterBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Router
 * @group Business
 * @group Router
 * @group RouterResource
 * @group MerchantPortalRouterResourceTest
 * Add your own group annotations below this line
 */
class MerchantPortalRouterResourceTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Router\Business\Router\RouterResource\MerchantPortalRouterResource::APPLICATION_MODULE_NAME
     *
     * @var string
     */
    protected const APPLICATION_MODULE_NAME = 'merchant-portal-application';

    /**
     * @var \SprykerTest\Zed\Router\RouterBusinessTester
     */
    protected RouterBusinessTester $tester;

    /**
     * @dataProvider getControllerPathDataProvider
     *
     * @param string $module
     * @param string $controller
     * @param string $action
     * @param list<string> $expectedPathCandidates
     *
     * @return void
     */
    public function testGetPathCandidates(string $module, string $controller, string $action, array $expectedPathCandidates): void
    {
        // Arrange
        /** @var \Spryker\Zed\Router\RouterConfig $routerConfig */
        $routerConfig = $this->tester->getModuleConfig();

        // Act
        $pathCandidates = $this->createMerchantPortalRouterResource($routerConfig)
            ->getPathCandidates($module, $controller, $action);

        // Assert
        $this->assertSame($expectedPathCandidates, $pathCandidates);
    }

    /**
     * @return array<array<string|list<string>>>
     */
    protected function getControllerPathDataProvider(): array
    {
        return [
            [
                'MockModule', 'Edit', 'edit', ['/mock-module/edit/edit'],
            ],
            [
                'MockModule', 'Edit', 'index', ['/mock-module/edit/index', '/mock-module/edit'],
            ],
            [
                'MockModule', 'Index', 'index', ['/mock-module/index/index', '/mock-module/index', '/mock-module'],
            ],
            [
                static::APPLICATION_MODULE_NAME,
                'Index',
                'index',
                [
                    sprintf('/%s/index/index', static::APPLICATION_MODULE_NAME),
                    sprintf('/%s/index', static::APPLICATION_MODULE_NAME),
                    sprintf('/%s', static::APPLICATION_MODULE_NAME),
                    '/',
                ],
            ],
        ];
    }

    /**
     * @param \Spryker\Zed\Router\RouterConfig $config
     *
     * @return \Spryker\Zed\Router\Business\RouterResource\ResourceInterface
     */
    protected function createMerchantPortalRouterResource(RouterConfig $config): ResourceInterface
    {
        return new class ($config) extends MerchantPortalRouterResource
        {
            /**
             * @param string $module
             * @param string $controller
             * @param string $action
             *
             * @return list<string>
             */
            public function getPathCandidates(string $module, string $controller, string $action): array
            {
                return parent::getPathCandidates($module, $controller, $action);
            }
        };
    }
}
