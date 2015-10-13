<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider;

use SprykerEngine\Yves\Application\Communication\Application as YvesApplication;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CookieServiceProvider implements ServiceProviderInterface
{

    /**
     * @var YvesApplication
     */
    private $app;

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $this->app = $app;
        $app['cookies'] = $app->share(function () {
            return new \ArrayObject();
        });
    }

    /**
     * Handles transparent Cookie insertion
     *
     * @param FilterResponseEvent $event The event to handle
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        foreach ($this->app['cookies'] as $cookie) {
            $response->headers->setCookie($cookie);
        }
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app['dispatcher']->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse'], -255);
    }

}
