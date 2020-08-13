<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\EventDispatcher\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Exception;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

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
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface|null $kernel
     * @param callable|null $controller
     * @param array|null $arguments
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param int|null $requestType
     *
     * @return \Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent
     */
    public function createControllerArgumentsEvent(
        ?HttpKernelInterface $kernel = null,
        ?callable $controller = null,
        ?array $arguments = [],
        ?Request $request = null,
        ?int $requestType = Kernel::MASTER_REQUEST
    ): ControllerArgumentsEvent {
        $kernel = $kernel ?? $this->getApplicationHelper()->getKernel();
        $request = $request ?? $this->getApplicationHelper()->getRequest();
        $controller = $controller ?? function () {
        };

        return new ControllerArgumentsEvent($kernel, $controller, $arguments, $request, $requestType);
    }

    /**
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface|null $kernel
     * @param callable|null $controller
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param int|null $requestType
     *
     * @return \Symfony\Component\HttpKernel\Event\ControllerEvent
     */
    public function createControllerEvent(
        ?HttpKernelInterface $kernel = null,
        ?callable $controller = null,
        ?Request $request = null,
        ?int $requestType = Kernel::MASTER_REQUEST
    ): ControllerEvent {
        $kernel = $kernel ?? $this->getApplicationHelper()->getKernel();
        $request = $request ?? $this->getApplicationHelper()->getRequest();
        $controller = $controller ?? function () {
        };

        return new ControllerEvent($kernel, $controller, $request, $requestType);
    }

    /**
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface|null $kernel
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param int|null $requestType
     * @param \Throwable|null $throwable
     *
     * @return \Symfony\Component\HttpKernel\Event\ExceptionEvent
     */
    public function createExceptionEvent(
        ?HttpKernelInterface $kernel = null,
        ?Request $request = null,
        ?int $requestType = Kernel::MASTER_REQUEST,
        ?Throwable $throwable = null
    ): ExceptionEvent {
        $kernel = $kernel ?? $this->getApplicationHelper()->getKernel();
        $request = $request ?? $this->getApplicationHelper()->getRequest();
        $throwable = $throwable ?? new Exception();

        return new ExceptionEvent($kernel, $request, $requestType, $throwable);
    }

    /**
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface|null $kernel
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param int|null $requestType
     *
     * @return \Symfony\Component\HttpKernel\Event\FinishRequestEvent
     */
    public function createFinishRequestEvent(
        ?HttpKernelInterface $kernel = null,
        ?Request $request = null,
        ?int $requestType = Kernel::MASTER_REQUEST
    ): FinishRequestEvent {
        $kernel = $kernel ?? $this->getApplicationHelper()->getKernel();
        $request = $request ?? $this->getApplicationHelper()->getRequest();

        return new FinishRequestEvent($kernel, $request, $requestType);
    }

    /**
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface|null $kernel
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param int|null $requestType
     *
     * @return \Symfony\Component\HttpKernel\Event\KernelEvent
     */
    public function createKernelEvent(
        ?HttpKernelInterface $kernel = null,
        ?Request $request = null,
        ?int $requestType = Kernel::MASTER_REQUEST
    ): KernelEvent {
        $kernel = $kernel ?? $this->getApplicationHelper()->getKernel();
        $request = $request ?? $this->getApplicationHelper()->getRequest();

        return new KernelEvent($kernel, $request, $requestType);
    }

    /**
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface|null $kernel
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param int|null $requestType
     *
     * @return \Symfony\Component\HttpKernel\Event\RequestEvent
     */
    public function createRequestEvent(
        ?HttpKernelInterface $kernel = null,
        ?Request $request = null,
        ?int $requestType = Kernel::MASTER_REQUEST
    ): RequestEvent {
        $kernel = $kernel ?? $this->getApplicationHelper()->getKernel();
        $request = $request ?? $this->getApplicationHelper()->getRequest();

        return new RequestEvent($kernel, $request, $requestType);
    }

    /**
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface|null $kernel
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param \Symfony\Component\HttpFoundation\Response|null $response
     * @param int|null $requestType
     *
     * @return \Symfony\Component\HttpKernel\Event\ResponseEvent
     */
    public function createResponseEvent(
        ?HttpKernelInterface $kernel = null,
        ?Request $request = null,
        ?Response $response = null,
        ?int $requestType = Kernel::MASTER_REQUEST
    ): ResponseEvent {
        $kernel = $kernel ?? $this->getApplicationHelper()->getKernel();
        $request = $request ?? $this->getApplicationHelper()->getRequest();
        $response = $response ?? new Response();

        return new ResponseEvent($kernel, $request, $requestType, $response);
    }

    /**
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface|null $kernel
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param \Symfony\Component\HttpFoundation\Response|null $response
     *
     * @return \Symfony\Component\HttpKernel\Event\TerminateEvent
     */
    public function createTerminateEvent(
        ?HttpKernelInterface $kernel = null,
        ?Request $request = null,
        ?Response $response = null
    ): TerminateEvent {
        $kernel = $kernel ?? $this->getApplicationHelper()->getKernel();
        $request = $request ?? $this->getApplicationHelper()->getRequest();
        $response = $response ?? new Response();

        return new TerminateEvent($kernel, $request, $response);
    }

    /**
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface|null $kernel
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param int|null $requestType
     * @param string $controllerResult
     *
     * @return \Symfony\Component\HttpKernel\Event\ViewEvent
     */
    public function createViewEvent(
        ?HttpKernelInterface $kernel = null,
        ?Request $request = null,
        ?int $requestType = Kernel::MASTER_REQUEST,
        $controllerResult = ''
    ): ViewEvent {
        $kernel = $kernel ?? $this->getApplicationHelper()->getKernel();
        $request = $request ?? $this->getApplicationHelper()->getRequest();

        return new ViewEvent($kernel, $request, $requestType, $controllerResult);
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
        $event = $this->getEventDispatcher()->dispatch($event);

        return $event;
    }
}
