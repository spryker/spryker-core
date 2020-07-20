<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\EventDispatcher\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\KernelEvents;

abstract class AbstractEventDispatcherHelper extends Module
{
    use ContainerHelperTrait;

    protected const MODULE_NAME = 'EventDispatcher';
    protected const SERVICE_DISPATCHER = 'dispatcher';

    /**
     * @var \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface[]
     */
    protected $eventDispatcherPlugins = [];

    /**
     * @param \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface $eventDispatcherPlugin
     *
     * @return $this
     */
    public function addEventDispatcherPlugin(EventDispatcherPluginInterface $eventDispatcherPlugin)
    {
        $this->eventDispatcherPlugins[] = $eventDispatcherPlugin;

        return $this;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        $this->getApplicationHelper()->addApplicationPlugin(
            $this->getEventDispatcherApplicationPluginStub()
        );
    }

    /**
     * @return \SprykerTest\Zed\Application\Helper\ApplicationHelper|\SprykerTest\Yves\Application\Helper\ApplicationHelper
     */
    abstract protected function getApplicationHelper();

    /**
     * @return \Spryker\Zed\EventDispatcher\Communication\Plugin\Application\EventDispatcherApplicationPlugin|\Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin|\Spryker\Glue\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin
     */
    abstract protected function getEventDispatcherApplicationPluginStub();

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->eventDispatcherPlugins = [];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $requestType
     *
     * @return \Symfony\Component\HttpKernel\Event\RequestEvent
     */
    public function dispatchRequestEvent(Request $request, int $requestType = HttpKernel::MASTER_REQUEST): RequestEvent
    {
        $requestEvent = new RequestEvent($this->getApplicationHelper()->getKernel(), $request, $requestType);

        /** @var \Symfony\Component\HttpKernel\Event\RequestEvent $requestEvent */
        $requestEvent = $this->dispatch($requestEvent, KernelEvents::REQUEST);

        return $requestEvent;
    }

    /**
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    protected function getEventDispatcher(): EventDispatcherInterface
    {
        $container = $this->getContainerHelper()->getContainer();

        return $container->get(static::SERVICE_DISPATCHER);
    }

    /**
     * @param \Symfony\Contracts\EventDispatcher\Event|\Symfony\Component\EventDispatcher\Event $event
     * @param string $eventName
     *
     * @return \Symfony\Contracts\EventDispatcher\Event|\Symfony\Component\EventDispatcher\Event
     */
    protected function dispatch($event, string $eventName)
    {
        /** @var \Symfony\Contracts\EventDispatcher\Event $event */
        $event = $this->getEventDispatcher()->dispatch($event, $eventName);

        return $event;
    }
}
