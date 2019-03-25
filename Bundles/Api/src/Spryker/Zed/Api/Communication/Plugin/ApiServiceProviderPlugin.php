<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Plugin;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Api\Communication\ApiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Api\Business\ApiFacadeInterface getFacade()
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 * @method \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface getQueryContainer()
 */
class ApiServiceProviderPlugin extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @deprecated Please don't use this property anymore. The needed ControllerListenerInterface is now retrieved by the Factory.
     *
     * @var \Spryker\Zed\Api\Communication\Plugin\ApiControllerListenerInterface
     */
    protected $controllerListener;

    /**
     * @api
     *
     * @deprecated Please remove usage of this setter. The needed ControllerListenerInterface is now retrieved by the Factory.
     *
     * @param \Spryker\Zed\Api\Communication\Plugin\ApiControllerListenerInterface $controllerListener
     *
     * @return void
     */
    public function setControllerListener(ApiControllerListenerInterface $controllerListener)
    {
        $this->controllerListener = $controllerListener;
    }

    /**
     * @api
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['dispatcher'] = $app->share($app->extend('dispatcher', function (EventDispatcherInterface $dispatcher) {
            $dispatcher->addListener(
                KernelEvents::CONTROLLER,
                [
                    $this->getControllerListener(),
                    'onKernelController',
                ]
            );

            return $dispatcher;
        }));
    }

    /**
     * @return \Spryker\Zed\Api\Communication\Plugin\ApiControllerListenerInterface
     */
    protected function getControllerListener()
    {
        if (!$this->controllerListener) {
            return $this->getFactory()->createControllerListener();
        }

        return $this->controllerListener;
    }

    /**
     * @api
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
