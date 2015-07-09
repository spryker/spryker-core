<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider;

use SprykerFeature\Shared\Library\Error\ErrorLogger;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionServiceProvider implements ServiceProviderInterface
{

    /**
     * @var string
     */
    private $controllerName;

    /**
     * @param string $controllerName
     */
    public function __construct($controllerName = '\SprykerFeature\Yves\Library\Controller\ExceptionController')
    {
        $this->controllerName = $controllerName;
    }

    public function register(Application $app)
    {
        $controllerName = $this->controllerName;
        $app['controller.service.404error'] = $app->share(function () use ($app, $controllerName) {
            return new $controllerName($app);
        });
        $app['dispatcher'] = $app->share($app->extend('dispatcher', function ($dispatcher) use ($app) {
            $dispatcher->addSubscriber(new ExceptionListener('controller.service.404error:showAction', $app['logger']));

            return $dispatcher;
        }));
    }

    public function boot(Application $app)
    {
        $app['dispatcher']->addListener(KernelEvents::EXCEPTION, [$this, 'onKernelException'], -8);
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $code = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;

        if ($code === 404) {
            ErrorLogger::log($exception);
        } else {
            throw $exception;
        }
    }

}
