<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider;

use SprykerEngine\Yves\Application\Communication\ApplicationDependencyContainer;
use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Library\Error\ErrorLogger;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method ApplicationDependencyContainer getDependencyContainer()
 */
class ExceptionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @param Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['controller.service.error'] = $this->getDependencyContainer()->createExceptionHandlerDispatcher();

        $app['dispatcher'] = $app->share(
            $app->extend('dispatcher', function (EventDispatcherInterface $dispatcher) use ($app) {
                $exceptionListener = new ExceptionListener(
                    'controller.service.error:dispatch',
                    $app['logger']
                );
                $dispatcher->addSubscriber($exceptionListener);

                return $dispatcher;
            })
        );
    }

    /**
     * @param Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app['dispatcher']->addListener(KernelEvents::EXCEPTION, [$this, 'onKernelException'], -8);
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
     * @throws \Exception
     *
     * @return void
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }

        $exceptionHandlers = $this->getDependencyContainer()->createExceptionHandlers();
        if (!array_key_exists($statusCode, $exceptionHandlers)) {
            throw $exception;
        }

        if ($statusCode === Response::HTTP_INTERNAL_SERVER_ERROR) {
            ErrorLogger::log($exception);
        }
    }

}
