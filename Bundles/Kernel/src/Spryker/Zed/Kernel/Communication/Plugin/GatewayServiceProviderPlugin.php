<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication\Plugin;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Application\Communication\Plugin\TransferObject\TransferServer;
use Spryker\Zed\Kernel\Communication\GatewayControllerListenerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Kernel\Communication\KernelCommunicationFactory getFactory()
 */
class GatewayServiceProviderPlugin extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @var \Spryker\Zed\Kernel\Communication\GatewayControllerListenerInterface
     */
    protected $controllerListener;

    /**
     * @param \Spryker\Zed\Kernel\Communication\GatewayControllerListenerInterface $controllerListener
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
        /* @var EventDispatcher $dispatcher */
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
