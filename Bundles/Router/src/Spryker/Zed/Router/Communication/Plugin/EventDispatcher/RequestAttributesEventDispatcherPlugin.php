<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Communication\Plugin\EventDispatcher;

use InvalidArgumentException;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Http\HttpConfig getConfig()
 * @method \Spryker\Zed\Http\Communication\HttpCommunicationFactory getFactory()
 * @method \Spryker\Zed\Router\Business\RouterFacadeInterface getFacade()
 */
class RequestAttributesEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    protected const EARLY_EVENT = 512;

    protected const MODULE = 'module';
    protected const CONTROLLER = 'controller';
    protected const ACTION = 'action';

    protected const DEFAULT_MODULE = 'application';
    protected const DEFAULT_CONTROLLER = 'index';
    protected const DEFAULT_ACTION = 'index';

    protected const POSITION_OF_ACTION = 2;
    protected const POSITION_OF_CONTROLLER = 1;
    protected const POSITION_OF_MODULE = 0;

    /**
     * {@inheritDoc}
     * - Adds a listener to the `\Symfony\Component\HttpKernel\KernelEvents::REQUEST` event to extract request specific attributes.
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(KernelEvents::REQUEST, function (RequestEvent $event) {
            return $this->addRequestAttributes($event);
        }, static::EARLY_EVENT);

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return \Symfony\Component\HttpKernel\Event\RequestEvent
     */
    protected function addRequestAttributes(RequestEvent $event): RequestEvent
    {
        if (!$this->isCli($event)) {
            return $this->parseRequestData($event);
        }

        return $this->parseCliRequestData($event);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return bool
     */
    protected function isCli(RequestEvent $event): bool
    {
        return (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg') && $event->getRequest()->server->get('argv', false);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return \Symfony\Component\HttpKernel\Event\RequestEvent
     */
    protected function parseRequestData(RequestEvent $event): RequestEvent
    {
        $request = $event->getRequest();

        $requestUriFragments = $this->getRequestUriFragments($request);

        $request->attributes->set(static::ACTION, $this->getAction($request, $requestUriFragments));
        $request->attributes->set(static::CONTROLLER, $this->getController($request, $requestUriFragments));
        $request->attributes->set(static::MODULE, $this->getModule($request, $requestUriFragments));

        return $event;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getRequestUriFragments(Request $request): array
    {
        $requestUriWithoutParameters = strtok($request->server->get('REQUEST_URI'), '?');
        $requestUriWithoutParameters = trim($requestUriWithoutParameters, '/');
        $requestUriWithoutParameters = str_replace('//', '/', $requestUriWithoutParameters);

        return explode('/', $requestUriWithoutParameters);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $requestUriFragments
     *
     * @return string
     */
    protected function getAction(Request $request, array $requestUriFragments): string
    {
        if (count($requestUriFragments) < 3) {
            return static::DEFAULT_ACTION;
        }

        return $requestUriFragments[static::POSITION_OF_ACTION];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $requestUriFragments
     *
     * @return string
     */
    protected function getController(Request $request, array $requestUriFragments): string
    {
        if (count($requestUriFragments) < 2) {
            return static::DEFAULT_CONTROLLER;
        }

        return $requestUriFragments[static::POSITION_OF_CONTROLLER];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $requestUriFragments
     *
     * @return string
     */
    protected function getModule(Request $request, array $requestUriFragments): string
    {
        if (count($requestUriFragments) < 1) {
            return static::DEFAULT_MODULE;
        }

        return $requestUriFragments[static::POSITION_OF_MODULE];
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return \Symfony\Component\HttpKernel\Event\RequestEvent
     */
    protected function parseCliRequestData(RequestEvent $event): RequestEvent
    {
        $request = $event->getRequest();
        $request = $this->setCliRequestAttributes($request);

        $this->assertCliRequestAttributes($request);

        return $event;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function setCliRequestAttributes(Request $request): Request
    {
        foreach ($request->server->get('argv') as $argument) {
            preg_match_all('/--(.*)=(.*)/', $argument, $matches);

            if ($matches[0]) {
                $key = $matches[1][0];
                $value = $matches[static::POSITION_OF_ACTION][0];
                $request->attributes->set($key, $value);
            }
        }

        return $request;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function assertCliRequestAttributes(Request $request): void
    {
        $requiredParameters = [
            static::MODULE,
            static::CONTROLLER,
            static::ACTION,
        ];

        foreach ($requiredParameters as $parameter) {
            if (!$request->attributes->has($parameter)) {
                throw new InvalidArgumentException(sprintf('Required parameter --%s is missing!', $parameter));
            }
        }
    }
}
