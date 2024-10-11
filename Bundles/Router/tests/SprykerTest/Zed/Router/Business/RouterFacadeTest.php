<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Router\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Router\Business\Route\Route;
use Spryker\Zed\Router\Business\Route\RouteCollection;
use Spryker\Zed\Router\Business\Router\ChainRouter;
use Spryker\Zed\Router\Business\RouterFacade;
use SprykerTest\Zed\Router\RouterBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Router
 * @group Business
 * @group Facade
 * @group RouterFacadeTest
 * Add your own group annotations below this line
 */
class RouterFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Router\RouterBusinessTester
     */
    protected RouterBusinessTester $tester;

    /**
     * @var \Spryker\Zed\Router\Business\RouterFacade
     */
    protected RouterFacade $routerFacade;

    /**
     * @return void
     */
    public function testGetBundlesReturnsCorrectResult(): void
    {
        // Arrange
        $this->mockFactory();

        // Act
        $bundles = $this->tester->getFacade()->getRouterBundleCollection()->getBundles();

        //Assert
        $this->assertEqualsCanonicalizing(['bundle-one', 'bundle-two'], $bundles);
    }

    /**
     * @dataProvider getControllersReturnsCorrectResultDataProvider
     *
     * @param string|null $bundle
     * @param array<string> $expectedResult
     *
     * @return void
     */
    public function testGetControllersReturnsCorrectResult(?string $bundle, array $expectedResult): void
    {
        // Arrange
        $this->mockFactory();

        // Act
        $controllers = $this->tester->getFacade()->getRouterControllerCollection($bundle)->getControllers();

        //Assert
        $this->assertEqualsCanonicalizing($expectedResult, $controllers);
    }

    /**
     * @dataProvider getActionsReturnsCorrectResultDataProvider
     *
     * @param string|null $bundle
     * @param string|null $controller
     * @param array<string> $expectedResult
     *
     * @return void
     */
    public function testGetActionsReturnsCorrectResult(?string $bundle, ?string $controller, array $expectedResult): void
    {
        // Arrange
        $this->mockFactory();

        // Act
        $actions = $this->tester->getFacade()->getRouterActionCollection($bundle, $controller)->getActions();

        //Assert
        $this->assertEqualsCanonicalizing($expectedResult, $actions);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getControllersReturnsCorrectResultDataProvider(): array
    {
        return [
            'check when bundle is bundle-one' => [
                'bundle' => 'bundle-one',
                'expectedResult' => [
                    'controller-one',
                    'controller-two',
                    'controller-three',
                    ],
            ],
            'check when bundle does not exist' => [
                'bundle' => 'not-existing',
                'expectedResult' => [],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getActionsReturnsCorrectResultDataProvider(): array
    {
        return [
            'check when bundle is not null and controller is not null' => [
                'bundle' => 'bundle-one',
                'controller' => 'controller-one',
                'expectedResult' => ['action-one', 'action-two'],
            ],
        ];
    }

    /**
     * @return void
     */
    protected function mockFactory(): void
    {
        $routerMock = $this->getMockBuilder(ChainRouter::class)
            ->onlyMethods(['getRouteCollection'])
            ->disableOriginalConstructor()
            ->getMock();
        $routeCollectionMock = $this->getMockBuilder(RouteCollection::class)->getMock();
        $routeCollectionMock->method('all')
            ->willReturn([
                'bundle-one' => new Route('/bundle-one'),
                'bundle-one:controller-one:action-one' => new Route('/bundle-one/controller-one'),
                'bundle-one:controller-one:action-two' => new Route('/bundle-one/controller-one'),
                'bundle-one:controller-three:action-two' => new Route('/bundle-one/controller-one'),
                'bundle-one:controller-two:action-one' => new Route('/bundle-one/controller-two'),
                'bundle-two:controller-one:action-one' => new Route('/bundle-two/controller-one'),
            ]);
        $routerMock->method('getRouteCollection')
            ->willReturn($routeCollectionMock);

        $this->tester->mockFactoryMethod('createBackofficeChainRouter', $routerMock);
    }
}
