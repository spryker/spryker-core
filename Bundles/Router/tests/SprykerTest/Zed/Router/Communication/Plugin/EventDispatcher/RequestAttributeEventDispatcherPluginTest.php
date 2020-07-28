<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Router\Communication\Plugin\EventDispatcher;

use Codeception\Test\Unit;
use Spryker\Zed\Router\Communication\Plugin\EventDispatcher\RequestAttributesEventDispatcherPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Router
 * @group Communication
 * @group Plugin
 * @group EventDispatcher
 * @group RequestAttributeEventDispatcherPluginTest
 * Add your own group annotations below this line
 */
class RequestAttributeEventDispatcherPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Router\Communication\Plugin\EventDispatcher\RequestAttributesEventDispatcherPlugin::MODULE
     */
    protected const MODULE = 'module';

    /**
     * @uses \Spryker\Zed\Router\Communication\Plugin\EventDispatcher\RequestAttributesEventDispatcherPlugin::CONTROLLER
     */
    protected const CONTROLLER = 'controller';

    /**
     * @uses \Spryker\Zed\Router\Communication\Plugin\EventDispatcher\RequestAttributesEventDispatcherPlugin::ACTION
     */
    protected const ACTION = 'action';

    /**
     * @var \SprykerTest\Zed\Router\RouterCommunicationTester
     */
    protected $tester;

    /**
     * @dataProvider urlStack
     *
     * @param string $givenUrl
     * @param string $expectedModule
     * @param string $expectedController
     * @param string $expectedAction
     *
     * @return void
     */
    public function testBeforeMustParseRequestDataAndSetBundleControllerAndActionInRequest(
        string $givenUrl,
        string $expectedModule,
        string $expectedController,
        string $expectedAction
    ): void {
        // Arrange
        $this->tester->addRoute($givenUrl, $givenUrl, function () {
        });

        $this->tester->addEventDispatcherPlugin($this->getRequestAttributesEventDispatcherPluginMock());
        $request = Request::create($givenUrl);

        // Act
        $requestEvent = $this->tester->dispatchRequestEvent($request);
        $request = $requestEvent->getRequest();

        // Assert
        $this->assertSame($expectedModule, $request->attributes->get(static::MODULE));
        $this->assertSame($expectedController, $request->attributes->get(static::CONTROLLER));
        $this->assertSame($expectedAction, $request->attributes->get(static::ACTION));
    }

    /**
     * @param bool $isCliRequest
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Router\Communication\Plugin\EventDispatcher\RequestAttributesEventDispatcherPlugin
     */
    protected function getRequestAttributesEventDispatcherPluginMock(bool $isCliRequest = false): RequestAttributesEventDispatcherPlugin
    {
        $requestAttributesEventDispatcherPluginMock = $this->getMockBuilder(RequestAttributesEventDispatcherPlugin::class)
            ->onlyMethods(['isCli'])
            ->getMock();

        $requestAttributesEventDispatcherPluginMock->method('isCli')->willReturn($isCliRequest);

        return $requestAttributesEventDispatcherPluginMock;
    }

    /**
     * @return array[]
     */
    public function urlStack(): array
    {
        return [
            ['/foo', 'foo', 'index', 'index'],
            ['/foo/bar', 'foo', 'bar', 'index'],
            ['/foo/bar/baz', 'foo', 'bar', 'baz'],
        ];
    }
}
