<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider;

use SprykerEngine\Yves\Application\Communication\ApplicationDependencyContainer;
use SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider\ExceptionService\ExceptionHandlerInterface;
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
        foreach ($this->getExceptionHandlers() as $statusCode => $exceptionHandler) {
            $app['controller.service.error' . $statusCode] = $exceptionHandler;

            $app['dispatcher'] = $app->share($app->extend('dispatcher', function ($dispatcher) use ($app, $statusCode) {
                /** @var EventDispatcherInterface $dispatcher */
                $dispatcher->addSubscriber(new ExceptionListener('controller.service.error' . $statusCode . ':handleException', $app['logger']));

                return $dispatcher;
            }));
        }
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

        $code = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        if (array_key_exists($code, $this->getExceptionHandlers())) {
            ErrorLogger::log($exception);
        } else {
            throw $exception;
        }
    }

    /**
     * @return ExceptionHandlerInterface[]
     */
    protected function getExceptionHandlers()
    {
        return $this->getDependencyContainer()->getExceptionHandlers();
    }

}
