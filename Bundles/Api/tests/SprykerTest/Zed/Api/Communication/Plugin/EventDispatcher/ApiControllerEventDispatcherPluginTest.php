<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Communication\Plugin\EventDispatcher;

use Codeception\Test\Unit;
use Spryker\Service\Container\Container;
use Spryker\Shared\EventDispatcher\EventDispatcher;
use Spryker\Zed\Api\Communication\Plugin\EventDispatcher\ApiControllerEventDispatcherPlugin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Api
 * @group Communication
 * @group Plugin
 * @group EventDispatcher
 * @group ApiControllerEventDispatcherPluginTest
 * Add your own group annotations below this line
 */
class ApiControllerEventDispatcherPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Api\ApiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsNotExecutedWhenNotAnApiRequest(): void
    {
        $eventDispatcher = new EventDispatcher();
        $apiControllerEventDispatcherPlugin = new ApiControllerEventDispatcherPlugin();
        $eventDispatcher = $apiControllerEventDispatcherPlugin->extend($eventDispatcher, new Container());

        $event = $this->tester->getControllerEvent(
            $this->tester->getNonApiRequest()
        );
        $originalController = $event->getController();

        $dispatchedEvent = $eventDispatcher->dispatch($event, KernelEvents::CONTROLLER);

        $this->assertSame($originalController, $dispatchedEvent->getController());
    }

    /**
     * @return void
     */
    public function testIsExecutedWhenAnApiRequest(): void
    {
        $eventDispatcher = new EventDispatcher();
        $apiControllerEventDispatcherPlugin = new ApiControllerEventDispatcherPlugin();
        $eventDispatcher = $apiControllerEventDispatcherPlugin->extend($eventDispatcher, new Container());

        $event = $this->tester->getControllerEvent(
            $this->tester->getApiRequest()
        );
        $originalController = $event->getController();

        $dispatchedEvent = $eventDispatcher->dispatch($event, KernelEvents::CONTROLLER);

        $this->assertNotSame($originalController, $dispatchedEvent->getController());
    }

    /**
     * @return void
     */
    public function testApiControllerReturnsResponse(): void
    {
        $eventDispatcher = new EventDispatcher();
        $apiControllerEventDispatcherPlugin = new ApiControllerEventDispatcherPlugin();
        $eventDispatcher = $apiControllerEventDispatcherPlugin->extend($eventDispatcher, new Container());

        $event = $this->tester->getControllerEvent(
            $this->tester->getApiRequest()
        );

        $eventDispatcher->dispatch($event, KernelEvents::CONTROLLER);

        $controller = $event->getController();
        $response = $controller();

        $this->assertInstanceOf(Response::class, $response);
    }
}
