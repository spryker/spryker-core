<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ErrorHandler\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\ErrorHandler\Communication\ErrorHandlerCommunicationFactory getFactory()
 * @method \Spryker\Zed\ErrorHandler\ErrorHandlerConfig getConfig()
 */
class ErrorPageEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * @var int
     */
    protected const PRIORITY = 50;

    /**
     * {@inheritDoc}
     * - Adds a listener for the `\Symfony\Component\HttpKernel\KernelEvents::EXCEPTION` event.
     * - Executes `\Spryker\Shared\ErrorHandler\Dependency\Plugin\ExceptionHandlerPluginInterface` which is able to handle the current status code.
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
        $eventDispatcher->addListener(KernelEvents::EXCEPTION, function (ExceptionEvent $event) {
            $this->onKernelException($event);
        }, static::PRIORITY);

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ExceptionEvent $event
     *
     * @return void
     */
    protected function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $exceptionHandlerStrategyPlugins = $this->getFactory()->getExceptionHandlerStrategyPlugins();
        foreach ($exceptionHandlerStrategyPlugins as $exceptionHandlerStrategyPlugin) {
            if (!$exceptionHandlerStrategyPlugin->canHandle($exception)) {
                continue;
            }

            $response = $exceptionHandlerStrategyPlugin->handleException(FlattenException::createFromThrowable($exception));

            $event->setResponse($response);
            $event->stopPropagation();

            break;
        }
    }
}
