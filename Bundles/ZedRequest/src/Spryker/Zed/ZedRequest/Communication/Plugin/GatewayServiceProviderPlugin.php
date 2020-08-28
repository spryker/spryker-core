<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Communication\Plugin;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ZedRequest\Communication\Plugin\TransferObject\TransferServer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @deprecated Use {@link \Spryker\Zed\ZedRequest\Communication\Plugin\EventDispatcher\GatewayControllerEventDispatcherPlugin} instead.
 *
 * @method \Spryker\Zed\ZedRequest\Communication\ZedRequestCommunicationFactory getFactory()
 * @method \Spryker\Zed\ZedRequest\Business\ZedRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\ZedRequest\ZedRequestConfig getConfig()
 */
class GatewayServiceProviderPlugin extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @deprecated Please don't use this property anymore. The needed ControllerListenerInterface is now retrieved by the Factory.
     *
     * @var \Spryker\Zed\ZedRequest\Communication\Plugin\GatewayControllerListenerInterface|null
     */
    protected $controllerListener;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Please remove usage of this setter. The needed ControllerListenerInterface is now retrieved by the Factory.
     *
     * @param \Spryker\Zed\ZedRequest\Communication\Plugin\GatewayControllerListenerInterface $controllerListener
     *
     * @return void
     */
    public function setControllerListener(GatewayControllerListenerInterface $controllerListener)
    {
        $this->controllerListener = $controllerListener;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app->extend('dispatcher', function (EventDispatcherInterface $eventDispatcher) {
            $eventDispatcher->addListener(
                KernelEvents::CONTROLLER,
                [
                    $this->getControllerListener(),
                    'onKernelController',
                ]
            );

            return $eventDispatcher;
        });
    }

    /**
     * @return \Spryker\Zed\ZedRequest\Communication\Plugin\GatewayControllerListenerInterface
     */
    protected function getControllerListener()
    {
        if (!$this->controllerListener) {
            return $this->getFactory()->createControllerListener();
        }

        return $this->controllerListener;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app->before(function (Request $request) {
            TransferServer::getInstance()->setRequest($request);
        });
    }
}
