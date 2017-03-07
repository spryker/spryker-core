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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Kernel\Communication\KernelCommunicationFactory getFactory()
 * @method \Spryker\Zed\ZedRequest\Business\ZedRequestFacade getFacade()
 */
class GatewayServiceProviderPlugin extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @var \Spryker\Zed\ZedRequest\Communication\Plugin\GatewayControllerListenerInterface
     */
    protected $controllerListener;

    /**
     * @param \Spryker\Zed\ZedRequest\Communication\Plugin\GatewayControllerListenerInterface $controllerListener
     *
     * @return void
     */
    public function setControllerListener(GatewayControllerListenerInterface $controllerListener)
    {
        $this->controllerListener = $controllerListener;
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        /** @var \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher */
        $dispatcher = $app['dispatcher'];
        $dispatcher->addListener(
            KernelEvents::CONTROLLER,
            [
                $this->controllerListener,
                'onKernelController',
            ]
        );
    }

    /**
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
