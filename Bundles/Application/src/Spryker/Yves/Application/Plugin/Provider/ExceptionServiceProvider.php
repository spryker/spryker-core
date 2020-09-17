<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Yves\Application\ApplicationFactory getFactory()
 */
class ExceptionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['controller.service.error'] = $this->getFactory()->createExceptionHandlerDispatcher();

        $app['dispatcher'] = $app->share(
            $app->extend('dispatcher', function (EventDispatcherInterface $dispatcher) {
                $dispatcher->addSubscriber($this->getListener());

                return $dispatcher;
            })
        );
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    protected function getListener(): EventSubscriberInterface
    {
        if (class_exists(ErrorListener::class)) {
            return new ErrorListener('controller.service.error:dispatch');
        }

        return new ExceptionListener('controller.service.error:dispatch');
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app['dispatcher']->addListener(KernelEvents::EXCEPTION, [$this, 'onKernelException'], -8);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ExceptionEvent $event
     *
     * @throws \Exception
     *
     * @return void
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }

        $exceptionHandlers = $this->getFactory()->createExceptionHandlers();
        if (!array_key_exists($statusCode, $exceptionHandlers)) {
            throw $exception;
        }
    }
}
